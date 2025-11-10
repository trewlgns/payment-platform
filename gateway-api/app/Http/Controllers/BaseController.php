<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Enums\ResponseMessage;

abstract class BaseController
{
    /**
     * 성공 응답 (2xx)
     *
     * @param mixed $data 응답 데이터
     * @param ResponseMessage $message 응답 메시지 Enum
     * @param array $details 추가 상세 정보
     * @return JsonResponse
     */
    protected function success($data = null, ResponseMessage $message = ResponseMessage::SUCCESS, array $details = []): JsonResponse
    {
        $response = [
            "result" => true,
            "code" => $message->value,
            "message" => $message->message()
        ];

        if ($data !== null) {
            $response["data"] = $data;
        }

        if (!empty($details)) {
            $response["details"] = $details;
        }

        return response()->json($response, $message->statusCode());
    }

    /**
     * 실패 응답 (4xx - 클라이언트 에러)
     *
     * @param ResponseMessage $message 응답 메시지 Enum
     * @param array $details 추가 상세 정보 (검증 에러 등)
     * @return JsonResponse
     */
    protected function fail(ResponseMessage $message = ResponseMessage::ERROR, array $details = []): JsonResponse
    {
        $response = [
            "result" => false,
            "is_error" => false,
            "code" => $message->value,
            "message" => $message->message()
        ];

        if (!empty($details)) {
            $response["details"] = $details;
        }

        return response()->json($response, $message->statusCode());
    }

    /**
     * 에러 응답 (5xx - 서버 에러)
     *
     * @param ResponseMessage $message 응답 메시지 Enum
     * @param array $details 디버그 정보 (개발 환경에서만 표시)
     * @return JsonResponse
     */
    protected function error(ResponseMessage $message = ResponseMessage::SERVER_ERROR, array $details = []): JsonResponse
    {
        $response = [
            "result" => false,
            "is_error" => true,
            "code" => $message->value,
            "message" => $message->message()
        ];

        // 개발 환경에서만 디버그 정보 포함
        if (config("app.debug") && !empty($details)) {
            $response["details"] = $details;
        }

        return response()->json($response, $message->statusCode());
    }
}


