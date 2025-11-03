<?php

namespace App\Exceptions;

use App\Enums\ResponseMessage;

/**
 * 서버 내부 오류 예외
 *
 * 사용 예: 예상치 못한 시스템 오류, 외부 서비스 장애 등
 */
class ServerErrorException extends BaseException
{
    public function __construct(string $message = "Internal server error", array $context = [])
    {
        $this->messageEnum = ResponseMessage::SERVER_ERROR;

        parent::__construct($message, $context);
    }
}
