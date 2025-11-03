<?php

namespace App\Exceptions;

use App\Enums\ResponseMessage;

/**
 * 인증 실패 예외
 *
 * 사용 예: 토큰 없음, 토큰 만료, 인증 실패 등
 */
class UnauthorizedException extends BaseException
{
    public function __construct(string $reason = "Authentication required", array $context = [])
    {
        $this->messageEnum = ResponseMessage::UNAUTHORIZED;

        $context["reason"] = $reason;

        parent::__construct($reason, $context);
    }
}
