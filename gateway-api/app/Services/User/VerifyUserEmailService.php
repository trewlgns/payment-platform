<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;
use App\Exceptions\NotFoundException;
use App\Exceptions\InvalidParameterException;
use App\Exceptions\ServerErrorException;

/**
 * 이메일 인증 처리 Service
 *
 * POST /api/users/{email}/verify-email
 */
class VerifyUserEmailService extends BaseService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository($this->db);
    }

    /**
     * 이메일 인증 처리
     *
     * @param string $email
     * @param string $verificationToken
     * @return array
     * @throws NotFoundException
     * @throws InvalidParameterException
     */
    protected function execute(...$args): array
    {
        $email = $args[0];
        $verificationToken = $args[1];

        // 1. 사용자 존재 여부 확인
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            throw new NotFoundException("사용자를 찾을 수 없습니다");
        }

        // 2. 이미 인증된 사용자인지 확인
        if ($user["email_verified_at"] !== null) {
            throw new InvalidParameterException("이미 인증된 이메일입니다");
        }

        // 3. 인증 토큰 검증 (실제 구현에서는 DB에 저장된 토큰과 비교)
        // TODO: 실제로는 email_verification_tokens 테이블과 비교해야 함
        // 여기서는 단순 검증만 수행
        if (empty($verificationToken)) {
            throw new InvalidParameterException("유효하지 않은 인증 토큰입니다");
        }

        // 4. 이메일 인증 완료 처리
        $affectedRows = $this->userRepo->markEmailAsVerified($email);

        if ($affectedRows === 0) {
            throw new ServerErrorException("이메일 인증 처리에 실패했습니다");
        }

        // 5. 업데이트된 사용자 조회 및 반환
        $verifiedUser = $this->userRepo->findByEmail($email);

        // 민감정보 제거
        unset($verifiedUser["password_hash"]);

        return $verifiedUser;
    }
}
