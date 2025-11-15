<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use App\Exceptions\NotFoundException;
use App\Exceptions\ServerErrorException;

/**
 * 사용자 정보 수정 Service
 *
 * PUT /api/users/{email}
 */
class UpdateUserService extends BaseService
{
    private UserRepository $userRepo;
    private UserValidator $validator;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository($this->db);
        $this->validator = new UserValidator($this->db);
    }

    /**
     * 사용자 정보 업데이트
     *
     * @param string $email
     * @param array $data ["name"?, "phone"?]
     * @return array
     * @throws NotFoundException
     */
    protected function execute(...$args): array
    {
        $email = $args[0];
        $data = $args[1];

        // 1. 사용자 존재 검증 (Entity 반환)
        $user = $this->validator->validateUserExists($email);

        // 2. Entity 속성 수정 (기존 값 유지)
        $user->name = $data["name"] ?? $user->name;
        $user->phone = $data["phone"] ?? $user->phone;
        $user->updatedAt = date("Y-m-d H:i:s");

        // 3. 업데이트 데이터 준비
        $updateData = [
            "name" => $user->name,
            "phone" => $user->phone
        ];

        // 4. 사용자 정보 업데이트
        $affectedRows = $this->userRepo->update($email, $updateData);

        if ($affectedRows === 0) {
            throw new ServerErrorException(__("messages.user_update_failed")); // 사용자 정보 업데이트에 실패했습니다
        }

        // 5. 응답 데이터 반환 (민감정보 제외)
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
