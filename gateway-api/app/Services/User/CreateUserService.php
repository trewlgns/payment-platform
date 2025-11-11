<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use App\Exceptions\ConflictException;

/**
 * 사용자 생성 Service (회원가입)
 *
 * POST /api/users
 */
class CreateUserService extends BaseService
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
     * 사용자 생성
     *
     * @param array $data ["email", "password", "name", "phone"]
     * @return array
     * @throws ConflictException
     */
    protected function execute(...$args): array
    {
        $data = $args[0];

        // 1. 이메일 중복 검증
        $this->validator->validateEmailNotExists($data["email"]);

        // 2. 비밀번호 해시화
        $passwordHash = password_hash($data["password"], PASSWORD_BCRYPT);

        // 3. 사용자 생성 데이터 준비
        $userData = [
            "email" => $data["email"],
            "password_hash" => $passwordHash,
            "name" => $data["name"],
            "phone" => $data["phone"],
            "status" => "active"
        ];

        // 4. 사용자 생성
        $this->userRepo->create($userData);

        // 5. 응답 데이터 반환 (민감정보 제외)
        $now = date("Y-m-d H:i:s");
        return [
            "email" => $data["email"],
            "name" => $data["name"],
            "phone" => $data["phone"],
            "status" => "active",
            "email_verified_at" => null,
            "created_at" => $now,
            "updated_at" => $now
        ];
    }
}
