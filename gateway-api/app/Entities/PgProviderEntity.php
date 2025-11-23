<?php

namespace App\Entities;

/**
 * PG 제공자 Entity
 *
 * PG Provider 테이블의 데이터를 타입 안전하게 표현
 */
class PgProviderEntity
{
    public function __construct(
        public string $pg_provider_code,
        public string $name,
        public int $is_active,
        public int $priority,
        public string $created_at,
        public string $updated_at
    ) {}

    /**
     * 활성화 여부 확인
     */
    public function isActive(): bool
    {
        return $this->is_active === 1;
    }

    /**
     * 비활성화 여부 확인
     */
    public function isInactive(): bool
    {
        return $this->is_active === 0;
    }
}
