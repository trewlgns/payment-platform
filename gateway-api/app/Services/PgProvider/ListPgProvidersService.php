<?php

namespace App\Services\PgProvider;

use App\Database\DB;
use App\Repositories\PgProviderRepository;
use App\Services\BaseService;

/**
 * PG 제공자 목록 조회 Service
 */
class ListPgProvidersService extends BaseService
{
    private PgProviderRepository $pgProviderRepo;

    public function __construct()
    {
        parent::__construct();
        $this->pgProviderRepo = new PgProviderRepository($this->db);
    }

    /**
     * PG 제공자 목록 조회
     *
     * @param array $filters
     * @return array
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];
        $pgProviders = $this->pgProviderRepo->findAll($filters);

        return array_map(fn($pgProvider) => [
            "pg_provider_code" => $pgProvider->pg_provider_code,
            "name" => $pgProvider->name,
            "is_active" => $pgProvider->is_active,
            "priority" => $pgProvider->priority,
            "created_at" => $pgProvider->created_at,
            "updated_at" => $pgProvider->updated_at
        ], $pgProviders);
    }
}
