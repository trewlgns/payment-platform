<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
use App\Repositories\EmailVerificationTokenRepository;
use App\Validators\UserValidator;
use App\Exceptions\ConflictException;

/**
 * 이메일 인증 토큰 발송 Service
 *
 * POST /api/users/{email}/send-verification
 */
class SendEmailVerificationService extends BaseService
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
     * 이메일 인증 토큰 생성 및 발송
     *
     * @param string $email
     * @return array
     * @throws NotFoundException
     * @throws ConflictException
     */
    protected function execute(...$args): array
    {
        $email = $args[0];

        // 1. 사용자 존재 검증 (Entity 반환)
        $user = $this->validator->validateUserExists($email);

        // 2. 이미 인증된 사용자인지 확인
        if ($user->isEmailVerified()) {
            throw new ConflictException("이미 이메일 인증이 완료된 사용자입니다");
        }

        // 3. 랜덤 토큰 생성 (32바이트 = 64자 hex)
        $rawToken = bin2hex(random_bytes(32));

        // 4. 토큰 해시 생성 (DB에는 해시만 저장)
        $tokenHash = hash("sha256", $rawToken);

        // 5. 기존 유효한 토큰이 있으면 무효화
        $this->tokenRepo->invalidateAllByEmail($email);

        // 6. 새 토큰 생성 (24시간 유효)
        $this->tokenRepo->create($tokenHash, $email, 24);

        // 7. 실제로는 이메일 발송 (현재는 토큰만 반환)
        // TODO: 실제 운영 환경에서는 이메일 발송 후 토큰을 반환하지 않음
        // Mail::to($email)->send(new VerificationEmail($rawToken));

        // 8. 응답 데이터 반환 (개발 환경에서만 토큰 반환)
        return [
            "email" => $email,
            "message" => "인증 이메일이 발송되었습니다",
            "verification_token" => $rawToken,  // 운영 환경에서는 제거
            "expires_in_hours" => 24
        ];
    }
}
