<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use App\Exceptions\NotFoundException;
use App\Exceptions\InvalidParameterException;
use App\Exceptions\ServerErrorException;
use App\Exceptions\ConflictException;

/**
 * 이메일 인증 처리 Service
 *
 * POST /api/users/{email}/verify-email
 */
class VerifyUserEmailService extends BaseService
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
     * 이메일 인증 처리
     *
     * @param string $email
     * @param string $verificationToken
     * @return array
     * @throws NotFoundException
     * @throws ConflictException
     * @throws InvalidParameterException
     */
    protected function execute(...$args): array
    {
        $email = $args[0];
        $verificationToken = $args[1];

        // 1. 사용자 존재 검증 (Entity 반환)
        $user = $this->validator->validateUserExists($email);

        // 2. 이미 인증된 사용자인지 확인
        $this->validator->validateEmailNotVerified($user);

        // 3. 인증 토큰 검증 (실제 구현에서는 DB에 저장된 토큰과 비교)
        // TODO: 실제로는 email_verification_tokens 테이블과 비교해야 함
        if (empty($verificationToken)) {
            throw new InvalidParameterException("유효하지 않은 인증 토큰입니다");
        }

        // 4. 이메일 인증 완료 처리
        $affectedRows = $this->userRepo->markEmailAsVerified($email);

        if ($affectedRows === 0) {
            throw new ServerErrorException("이메일 인증 처리에 실패했습니다");
        }

        // 5. 응답 데이터 반환 (민감정보 제외)
        $now = date("Y-m-d H:i:s");
        return [
            "email" => $user->email,
            "name" => $user->name,
            "phone" => $user->phone,
            "status" => $user->status,
            "email_verified_at" => $now,
            "created_at" => $user->createdAt,
            "updated_at" => $now
        ];
    }
}
