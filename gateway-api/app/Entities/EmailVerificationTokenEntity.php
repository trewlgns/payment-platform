<?php

namespace App\Entities;

/**
 * EmailVerificationToken Entity - 이메일 인증 토큰 데이터 객체
 *
 * 순수하게 데이터만 담는 객체 (DB 쿼리 수행 안함)
 */
class EmailVerificationTokenEntity
{
    /**
     * 토큰 해시 (Primary Key)
     */
    public string $tokenHash;

    /**
     * 이메일 (Foreign Key)
     */
    public string $email;

    /**
     * 만료 시각
     */
    public string $expiresAt;

    /**
     * 인증 완료 시각
     */
    public ?string $verifiedAt;

    /**
     * 생성일시
     */
    public string $createdAt;

    /**
     * 생성자 - 배열 데이터로부터 Entity 생성
     *
     * @param array $data DB에서 조회한 배열 데이터
     */
    public function __construct(array $data)
    {
        $this->tokenHash = $data["token_hash"];
        $this->email = $data["email"];
        $this->expiresAt = $data["expires_at"];
        $this->verifiedAt = $data["verified_at"] ?? null;
        $this->createdAt = $data["created_at"];
    }

    // ========================================
    // 헬퍼 메서드 (상태 확인)
    // ========================================

    /**
     * 토큰이 만료되었는지 확인
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return strtotime($this->expiresAt) < time();
    }

    /**
     * 토큰이 이미 사용되었는지 확인
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verifiedAt !== null;
    }

    /**
     * 토큰이 유효한지 확인 (만료되지 않고, 사용되지 않음)
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->isVerified();
    }
}
