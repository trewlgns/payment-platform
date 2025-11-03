<?php

namespace App\Exceptions;

use App\Enums\ResponseMessage;

/**
 * 리소스를 찾을 수 없음 예외 (범용)
 *
 * 사용 예: 도메인 특화되지 않은 일반적인 404
 */
class NotFoundException extends BaseException
{
    public function __construct(string $resource, string $identifier = "", array $context = [])
    {
        $this->messageEnum = ResponseMessage::NOT_FOUND;

        $message = "Not found: {$resource}";
        if ($identifier) {
            $message .= " (identifier: {$identifier})";
        }

        $context["resource"] = $resource;
        if ($identifier) {
            $context["identifier"] = $identifier;
        }

        parent::__construct($message, $context);
    }
}
