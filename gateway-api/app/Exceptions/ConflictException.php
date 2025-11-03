<?php

namespace App\Exceptions;

use App\Enums\ResponseMessage;

/**
 * 충돌 예외 (409)
 *
 * 사용 예: 중복 데이터, 이미 처리된 요청 등
 */
class ConflictException extends BaseException
{
    public function __construct(string $resource, string $reason = "Already exists", array $context = [])
    {
        $this->messageEnum = ResponseMessage::ERROR;  // 409는 별도 Enum 추가 필요 시 변경

        $message = "Conflict: {$resource} - {$reason}";

        $context["resource"] = $resource;
        $context["reason"] = $reason;

        parent::__construct($message, $context);
    }
}
