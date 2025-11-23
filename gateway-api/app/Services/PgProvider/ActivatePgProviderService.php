<?php

namespace App\Services\PgProvider;

use App\Database\DB;
use App\Repositories\PgProviderRepository;
use App\Services\BaseService;
use App\Validators\PgProviderValidator;

/**
 * PG 제공자 활성화 Service
 */
class ActivatePgProviderService extends BaseService
{
    private PgProviderRepository $pgProviderRepo;
    private PgProviderValidator $pgProviderValidator;

    public function __construct()
    {
        parent::__construct();
        $this->pgProviderRepo = new PgProviderRepository($this->db);
        $this->pgProviderValidator = new PgProviderValidator($this->db);
    }

    /**
     * PG 제공자 활성화
     *
     * @param array $data ["pg_provider_code" => string]
     * @return array
     */
    protected function execute(...$args): array
    {
        $data = $args[0];
        $pgProviderCode = $data["pg_provider_code"];

        // 존재 검증
        $pgProvider = $this->pgProviderValidator->validatePgProviderExists($pgProviderCode);

        // 이미 활성화 상태인 경우 검증
        $this->pgProviderValidator->validatePgProviderInactive($pgProvider);

        // PG 제공자 활성화
        $this->pgProviderRepo->updateActiveStatus($pgProviderCode, 1);

        // 활성화된 PG 제공자 조회 및 반환
        $pgProvider = $this->pgProviderRepo->findByCode($pgProviderCode);

        return [
            "pg_provider_code" => $pgProvider->pg_provider_code,
            "name" => $pgProvider->name,
            "is_active" => $pgProvider->is_active,
            "priority" => $pgProvider->priority,
            "created_at" => $pgProvider->created_at,
            "updated_at" => $pgProvider->updated_at
        ];
    }
}
