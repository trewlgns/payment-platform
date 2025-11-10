<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
use App\Exceptions\NotFoundException;

/**
 * 사용자 상세 조회 Service
 *
 * GET /api/users/{email}
 */
class GetUserService extends BaseService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository($this->db);
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

        // 사용자 조회
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            throw new NotFoundException("사용자를 찾을 수 없습니다");
        }

        // 민감정보 제거
        unset($user["password_hash"]);

        return $user;
    }
}
