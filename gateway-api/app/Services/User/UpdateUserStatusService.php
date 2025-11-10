<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
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

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository($this->db);
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

        // 1. 사용자 존재 여부 확인
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            throw new NotFoundException("사용자를 찾을 수 없습니다");
        }

        // 2. 상태 변경
        $affectedRows = $this->userRepo->updateStatus($email, $status);

        if ($affectedRows === 0) {
            throw new ServerErrorException("사용자 상태 변경에 실패했습니다");
        }

        // 3. 업데이트된 사용자 조회 및 반환
        $updatedUser = $this->userRepo->findByEmail($email);

        // 민감정보 제거
        unset($updatedUser["password_hash"]);

        return $updatedUser;
    }
}
