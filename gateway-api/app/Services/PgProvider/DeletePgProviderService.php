<?php

namespace App\Services\PgProvider;

use App\Database\DB;
use App\Repositories\PgProviderRepository;
use App\Services\BaseService;
use App\Validators\PgProviderValidator;

/**
 * PG 제공자 삭제 Service
 */
class DeletePgProviderService extends BaseService
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
     * PG 제공자 삭제
     *
     * @param array $data ["pg_provider_code" => string]
     * @return array
     */
    protected function execute(...$args): array
    {
        $data = $args[0];
        $pgProviderCode = $data["pg_provider_code"];

        // 존재 검증
        $this->pgProviderValidator->validatePgProviderExists($pgProviderCode);

        // PG 제공자 삭제
        $this->pgProviderRepo->delete($pgProviderCode);

        return [
            "pg_provider_code" => $pgProviderCode
        ];
    }
}
