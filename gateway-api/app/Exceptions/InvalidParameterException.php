<?php

namespace App\Exceptions;

use App\Enums\ResponseMessage;

/**
 * 잘못된 파라미터 예외
 *
 * 사용 예: 필수 파라미터 누락, 타입 불일치, 범위 초과 등
 */
class InvalidParameterException extends BaseException
{
    public function __construct(string $parameterName, string $reason = "", array $context = [])
    {
        $this->messageEnum = ResponseMessage::VALIDATION_FAILED;

        $message = "Invalid parameter: {$parameterName}";
        if ($reason) {
            $message .= " - {$reason}";
        }

        $context["parameter"] = $parameterName;
        $context["reason"] = $reason;

        parent::__construct($message, $context);
    }
}
