<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
use App\Repositories\EmailVerificationTokenRepository;
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
    private EmailVerificationTokenRepository $tokenRepo;
    private UserValidator $validator;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository($this->db);
        $this->tokenRepo = new EmailVerificationTokenRepository($this->db);
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

        // 3. 입력값 검증
        if (empty($verificationToken)) {
            throw new InvalidParameterException(__("messages.verification_token_required"));
        }

        // 4. 토큰 해시 생성 (입력받은 토큰을 SHA-256 해싱)
        $tokenHash = hash("sha256", $verificationToken);

        // 5. 토큰 조회 및 검증
        $token = $this->tokenRepo->findByTokenHash($tokenHash);

        if (!$token) {
            throw new NotFoundException(__("messages.verification_token_not_found"));
        }

        if ($token->email !== $email) {
            throw new InvalidParameterException(__("messages.verification_token_mismatch"));
        }

        if ($token->isExpired()) {
            throw new InvalidParameterException(__("messages.verification_token_expired"));
        }

        if ($token->isVerified()) {
            throw new ConflictException(__("messages.verification_token_used"));
        }

        // 6. 토큰 인증 완료 처리
        $this->tokenRepo->markAsVerified($tokenHash);

        // 7. 사용자 이메일 인증 완료 처리
        $affectedRows = $this->userRepo->markEmailAsVerified($email);

        if ($affectedRows === 0) {
            throw new ServerErrorException(__("messages.email_verification_failed")); // 이메일 인증 처리에 실패했습니다
        }

        // 8. 해당 이메일의 다른 모든 토큰 무효화
        $this->tokenRepo->invalidateAllByEmail($email);

        // 9. 응답 데이터 반환 (민감정보 제외)
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
