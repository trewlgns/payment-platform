<?php

namespace App\Entities;

/**
 * User Entity - 읽기/쓰기 가능한 데이터 객체
 *
 * DB 쿼리를 수행하지 않고, 순수하게 데이터만 담는 객체
 * Repository에서 조회한 데이터를 Entity로 변환하고,
 * Service에서 Entity를 수정한 후 Repository에 전달
 *
 * 사용 흐름:
 * 1. Repository::findByEmail() → array 반환
 * 2. new UserEntity($array) → Entity 생성
 * 3. Service에서 Entity 속성 수정
 * 4. Repository::update($entity) → Entity를 DB에 저장
 */
class UserEntity
{
    /**
     * 이메일 (Primary Key)
     */
    public string $email;

    /**
     * 암호화된 비밀번호
     */
    public string $passwordHash;

    /**
     * 회원명
     */
    public string $name;

    /**
     * 연락처
     */
    public string $phone;

    /**
     * 회원 상태 (active, inactive, banned)
     */
    public string $status;

    /**
     * 이메일 인증 시각
     */
    public ?string $emailVerifiedAt;

    /**
     * 삭제 시각 (Soft Delete)
     */
    public ?string $deletedAt;

    /**
     * 생성 시각
     */
    public string $createdAt;

    /**
     * 수정 시각
     */
    public string $updatedAt;

    /**
     * 생성자 - 배열 데이터로부터 Entity 생성
     *
     * @param array $data DB에서 조회한 배열 데이터
     */
    public function __construct(array $data)
    {
        $this->email = $data["email"];
        $this->passwordHash = $data["password_hash"];
        $this->name = $data["name"];
        $this->phone = $data["phone"];
        $this->status = $data["status"];
        $this->emailVerifiedAt = $data["email_verified_at"] ?? null;
        $this->deletedAt = $data["deleted_at"] ?? null;
        $this->createdAt = $data["created_at"];
        $this->updatedAt = $data["updated_at"];
    }

    // ========================================
    // 헬퍼 메서드 (상태 확인)
    // ========================================

    /**
     * 활성 사용자인지 확인
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === "active";
    }

    /**
     * 비활성 사용자인지 확인
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return $this->status === "inactive";
    }

    /**
     * 차단된 사용자인지 확인
     *
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->status === "banned";
    }

    /**
     * 삭제된 사용자인지 확인
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    /**
     * 이메일 인증 여부 확인
     *
     * @return bool
     */
    public function isEmailVerified(): bool
    {
        return $this->emailVerifiedAt !== null;
    }
}
