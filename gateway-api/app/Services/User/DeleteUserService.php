<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
use App\Exceptions\NotFoundException;
use App\Exceptions\ServerErrorException;

/**
 * 사용자 삭제 Service (Soft Delete)
 *
 * DELETE /api/users/{email}
 */
class DeleteUserService extends BaseService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository($this->db);
    }

    /**
     * 사용자 삭제 (Soft Delete)
     *
     * @param string $email
     * @return void
     * @throws NotFoundException
     */
    protected function execute(...$args): void
    {
        $email = $args[0];

        // 1. 사용자 존재 여부 확인
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            throw new NotFoundException("사용자를 찾을 수 없습니다");
        }

        // 2. Soft Delete 실행
        $affectedRows = $this->userRepo->softDelete($email);

        if ($affectedRows === 0) {
            throw new ServerErrorException("사용자 삭제에 실패했습니다");
        }
    }
}
