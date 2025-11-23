<?php

namespace App\Services\PgProvider;

use App\Database\DB;
use App\Repositories\PgProviderRepository;
use App\Services\BaseService;
use App\Validators\PgProviderValidator;

/**
 * PG 제공자 수정 Service
 */
class UpdatePgProviderService extends BaseService
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
     * PG 제공자 수정
     *
     * @param array $data ["pg_provider_code" => string, "name" => string, "priority" => int]
     * @return array
     */
    protected function execute(...$args): array
    {
        $data = $args[0];

        // 존재 검증
        $this->pgProviderValidator->validatePgProviderExists($data["pg_provider_code"]);

        // PG 제공자 수정
        $this->pgProviderRepo->update($data["pg_provider_code"], [
            "name" => $data["name"],
            "priority" => $data["priority"]
        ]);

        // 수정된 PG 제공자 조회 및 반환
        $pgProvider = $this->pgProviderRepo->findByCode($data["pg_provider_code"]);

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
