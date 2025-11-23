<?php

namespace App\Validators;

use App\Database\DB;
use App\Entities\PgProviderEntity;
use App\Exceptions\ConflictException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Repositories\PgProviderRepository;

/**
 * PG Provider 도메인 Validator
 *
 * PG 제공자 존재 여부, 상태, 중복 여부 등 Semantic 검증을 담당한다.
 */
class PgProviderValidator extends BaseValidator
{
    private PgProviderRepository $pgProviderRepo;

    public function __construct(DB &$db)
    {
        parent::__construct($db);
        $this->pgProviderRepo = new PgProviderRepository($db);
    }

    // ========================================
    // 존재성 검증
    // ========================================

    /**
     * PG 제공자 존재 여부 확인 (존재하지 않으면 예외)
     *
     * @param string $pgProviderCode
     * @return PgProviderEntity
     * @throws NotFoundException
     */
    public function validatePgProviderExists(string $pgProviderCode): PgProviderEntity
    {
        $pgProvider = $this->pgProviderRepo->findByCode($pgProviderCode);

        if (!$pgProvider) {
            throw new NotFoundException(__("messages.pg_provider_not_found"));
        }

        return $pgProvider;
    }

    /**
     * PG 제공자 코드 중복 여부 확인
     *
     * @param string $pgProviderCode
     * @return void
     * @throws ConflictException
     */
    public function validatePgProviderCodeNotExists(string $pgProviderCode): void
    {
        $existing = $this->pgProviderRepo->findByCode($pgProviderCode);

        if ($existing) {
            throw new ConflictException(__("messages.pg_provider_code_exists"));
        }
    }

    // ========================================
    // 상태 검증
    // ========================================

    /**
     * PG 제공자가 활성 상태인지 확인
     *
     * @param PgProviderEntity $pgProvider
     * @return void
     * @throws ForbiddenException
     */
    public function validatePgProviderActive(PgProviderEntity $pgProvider): void
    {
        if (!$pgProvider->isActive()) {
            throw new ForbiddenException(__("messages.pg_provider_inactive"));
        }
    }

    /**
     * PG 제공자가 비활성 상태인지 확인
     *
     * @param PgProviderEntity $pgProvider
     * @return void
     * @throws ForbiddenException
     */
    public function validatePgProviderInactive(PgProviderEntity $pgProvider): void
    {
        if ($pgProvider->isActive()) {
            throw new ForbiddenException(__("messages.pg_provider_already_active"));
        }
    }
}
