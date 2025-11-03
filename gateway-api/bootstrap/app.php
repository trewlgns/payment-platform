<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 전역 미들웨어로 SetLocale 추가
        $middleware->append(\App\Http\Middleware\SetLocale::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 1. BaseException 처리 (커스텀 예외)
        $exceptions->render(function (\App\Exceptions\BaseException $e, \Illuminate\Http\Request $request) {
            // 트랜잭션 롤백 (진행 중인 경우)
            if (app()->bound("db")) {
                $db = app("db");
                if (method_exists($db, "inTransaction") && $db->inTransaction()) {
                    $db->rollBack();
                }
            }

            $statusCode = $e->getResponseMessage()->statusCode();

            $response = [
                "result" => false,
                "is_error" => $statusCode >= 500,
                "code" => $e->getResponseMessage()->value,
                "message" => $e->getResponseMessage()->message()
            ];

            // 개발 환경에서만 컨텍스트 정보 포함
            if (config("app.debug") && !empty($e->getContext())) {
                $response["details"] = $e->getContext();
            }

            return response()->json($response, $statusCode);
        });

        // 2. ValidationException 처리 (FormRequest 검증 실패)
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, \Illuminate\Http\Request $request) {
            return response()->json([
                "result" => false,
                "is_error" => false,
                "code" => "validation_failed",
                "message" => __("messages.validation_failed"),
                "details" => $e->errors()
            ], 422);
        });

        // 3. 기타 모든 Exception (안전망)
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            // 트랜잭션 롤백
            if (app()->bound("db")) {
                $db = app("db");
                if (method_exists($db, "inTransaction") && $db->inTransaction()) {
                    $db->rollBack();
                }
            }

            // 로그 기록
            \Illuminate\Support\Facades\Log::channel("error")->error("Unhandled Exception", [
                "exception_class" => get_class($e),
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                "trace" => $e->getTraceAsString()
            ]);

            // 개발 환경에서만 상세 정보
            $details = null;
            if (config("app.debug")) {
                $details = [
                    "exception" => get_class($e),
                    "message" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine()
                ];
            }

            return response()->json([
                "result" => false,
                "is_error" => true,
                "code" => "server_error",
                "message" => __("messages.server_error"),
                "details" => $details
            ], 500);
        });
    })->create();
