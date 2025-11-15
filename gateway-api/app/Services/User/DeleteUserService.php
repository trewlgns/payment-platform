<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
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
    private UserValidator $validator;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository($this->db);
        $this->validator = new UserValidator($this->db);
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

        // 1. 사용자 존재 검증
        $this->validator->validateUserExists($email);

        // 2. Soft Delete 실행
        $affectedRows = $this->userRepo->softDelete($email);

        if ($affectedRows === 0) {
            throw new ServerErrorException(__("messages.user_delete_failed")); // 사용자 삭제에 실패했습니다
        }
    }
}
