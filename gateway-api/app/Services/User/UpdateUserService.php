<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
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

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository($this->db);
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

        // 1. 사용자 존재 여부 확인
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            throw new NotFoundException("사용자를 찾을 수 없습니다");
        }

        // 2. 업데이트할 데이터 준비 (기존 값 유지)
        $updateData = [
            "name" => $data["name"] ?? $user["name"],
            "phone" => $data["phone"] ?? $user["phone"]
        ];

        // 3. 사용자 정보 업데이트
        $affectedRows = $this->userRepo->update($email, $updateData);

        if ($affectedRows === 0) {
            throw new ServerErrorException("사용자 정보 업데이트에 실패했습니다");
        }

        // 4. 업데이트된 사용자 조회 및 반환
        $updatedUser = $this->userRepo->findByEmail($email);

        // 민감정보 제거
        unset($updatedUser["password_hash"]);

        return $updatedUser;
    }
}
