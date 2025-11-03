<?php

namespace App\Exceptions;

use App\Enums\ResponseMessage;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * 프로젝트 전용 예외 최상위 클래스
 *
 * 모든 커스텀 예외는 이 클래스를 상속받아야 함
 * throw 되는 순간 자동으로 로깅됨
 */
abstract class BaseException extends Exception
{
    /**
     * 응답 메시지 Enum
     */
    protected ResponseMessage $messageEnum;

    /**
     * 추가 컨텍스트 정보
     */
    protected array $context = [];

    /**
     * 생성자
     *
     * @param string $message 내부 로그용 메시지 (개발자용)
     * @param array $context 추가 컨텍스트 (order_id, product_code 등)
     */
    public function __construct(string $message = "", array $context = [])
    {
        parent::__construct($message);
        $this->context = $this->maskSensitiveData($context);

        // throw 되는 순간 자동 로깅
        $this->log();
    }

    /**
     * 민감 정보 마스킹
     *
     * @param array $data
     * @return array
     */
    protected function maskSensitiveData(array $data): array
    {
        $sensitiveKeys = [
            "password",
            "password_confirmation",
            "card_number",
            "cvv",
            "pin",
            "api_key",
            "secret",
            "token",
            "access_token",
            "refresh_token"
        ];

        foreach ($data as $key => $value) {
            // 키 이름에 민감 정보가 포함되어 있는지 확인
            foreach ($sensitiveKeys as $sensitiveKey) {
                if (stripos($key, $sensitiveKey) !== false) {
                    if (is_string($value) && strlen($value) > 4) {
                        // 마지막 4자리만 남기고 마스킹
                        $data[$key] = str_repeat("*", strlen($value) - 4) . substr($value, -4);
                    } else {
                        $data[$key] = "****";
                    }
                    break;
                }
            }

            // 중첩 배열 처리
            if (is_array($value)) {
                $data[$key] = $this->maskSensitiveData($value);
            }
        }

        return $data;
    }

    /**
     * 자동 로깅 (throw 되는 순간 호출)
     */
    protected function log(): void
    {
        $statusCode = $this->messageEnum->statusCode();
        $exceptionType = $this->getExceptionType();

        $logMessage = sprintf(
            "[%s] %s\nMessage: %s\nCode: %s (HTTP %d)\nFile: %s:%d",
            strtoupper($exceptionType),
            get_class($this),
            $this->getMessage(),
            $this->messageEnum->value,
            $statusCode,
            $this->getFile(),
            $this->getLine()
        );

        if (!empty($this->context)) {
            $logMessage .= "\nContext: " . json_encode($this->context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        // config/logging.php의 threshold 레벨에 따라 로깅
        if ($statusCode >= 500) {
            // 서버 에러 → error 채널
            Log::channel("error")->error($logMessage, [
                "exception_class" => get_class($this),
                "code" => $this->messageEnum->value,
                "trace" => $this->getTraceAsString()
            ]);
        } else {
            // 클라이언트 에러 → policy 채널
            Log::channel("policy")->warning($logMessage, [
                "exception_class" => get_class($this),
                "code" => $this->messageEnum->value
            ]);
        }
    }

    /**
     * 예외 타입 반환 (로그용)
     */
    protected function getExceptionType(): string
    {
        $statusCode = $this->messageEnum->statusCode();

        if ($statusCode >= 500) {
            return "error";
        } elseif ($statusCode >= 400) {
            return "policy_violation";
        } else {
            return "info";
        }
    }

    /**
     * ResponseMessage Enum 반환
     */
    public function getResponseMessage(): ResponseMessage
    {
        return $this->messageEnum;
    }

    /**
     * 컨텍스트 정보 반환
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
