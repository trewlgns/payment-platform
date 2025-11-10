<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
use App\Exceptions\ConflictException;

/**
 * 사용자 생성 Service (회원가입)
 *
 * POST /api/users
 */
class CreateUserService extends BaseService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository($this->db);
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

        // 1. 이메일 중복 체크
        if ($this->userRepo->existsByEmail($data["email"])) {
            throw new ConflictException("이미 존재하는 이메일입니다");
        }

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

        // 5. 생성된 사용자 조회 및 반환
        $user = $this->userRepo->findByEmail($data["email"]);

        // 민감정보 제거
        unset($user["password_hash"]);

        return $user;
    }
}
