<?php

namespace App\Exceptions;

use App\Enums\ResponseMessage;

/**
 * 권한 없음 예외
 *
 * 사용 예: 접근 권한 부족, 관리자 전용 기능 등
 */
class ForbiddenException extends BaseException
{
    public function __construct(string $resource, string $action = "access", array $context = [])
    {
        $this->messageEnum = ResponseMessage::FORBIDDEN;

        $message = "Forbidden: Cannot {$action} {$resource}";

        $context["resource"] = $resource;
        $context["action"] = $action;

        parent::__construct($message, $context);
    }
}
