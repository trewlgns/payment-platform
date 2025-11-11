<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use App\Exceptions\NotFoundException;
use App\Exceptions\ServerErrorException;

/**
 * 사용자 상태 변경 Service
 *
 * PATCH /api/users/{email}/status
 */
class UpdateUserStatusService extends BaseService
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
     * 사용자 상태 변경
     *
     * @param string $email
     * @param string $status "active" | "inactive" | "banned"
     * @return array
     * @throws NotFoundException
     */
    protected function execute(...$args): array
    {
        $email = $args[0];
        $status = $args[1];

        // 1. 사용자 존재 검증 (Entity 반환)
        $user = $this->validator->validateUserExists($email);

        // 2. 상태 변경
        $affectedRows = $this->userRepo->updateStatus($email, $status);

        if ($affectedRows === 0) {
            throw new ServerErrorException("사용자 상태 변경에 실패했습니다");
        }

        // 3. 응답 데이터 반환 (민감정보 제외)
        return [
            "email" => $user->email,
            "name" => $user->name,
            "phone" => $user->phone,
            "status" => $status,
            "email_verified_at" => $user->emailVerifiedAt,
            "created_at" => $user->createdAt,
            "updated_at" => date("Y-m-d H:i:s")
        ];
    }
}
