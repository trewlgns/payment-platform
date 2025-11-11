<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Validators\UserValidator;
use App\Exceptions\NotFoundException;

/**
 * 사용자 상세 조회 Service
 *
 * GET /api/users/{email}
 */
class GetUserService extends BaseService
{
    private UserValidator $validator;

    public function __construct()
    {
        parent::__construct();
        $this->validator = new UserValidator($this->db);
    }

    /**
     * 이메일로 사용자 조회
     *
     * @param string $email
     * @return array
     * @throws NotFoundException
     */
    protected function execute(...$args): array
    {
        $email = $args[0];

        // 사용자 존재 검증 (Entity 반환)
        $user = $this->validator->validateUserExists($email);

        // 응답 데이터 반환 (민감정보 제외)
        return [
            "email" => $user->email,
            "name" => $user->name,
            "phone" => $user->phone,
            "status" => $user->status,
            "email_verified_at" => $user->emailVerifiedAt,
            "created_at" => $user->createdAt,
            "updated_at" => $user->updatedAt
        ];
    }
}
