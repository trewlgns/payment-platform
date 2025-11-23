<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessage;
use App\Http\Requests\PgProvider\CreatePgProviderRequest;
use App\Http\Requests\PgProvider\ListPgProvidersRequest;
use App\Http\Requests\PgProvider\UpdatePgProviderRequest;
use App\Services\PgProvider\ActivatePgProviderService;
use App\Services\PgProvider\CreatePgProviderService;
use App\Services\PgProvider\DeactivatePgProviderService;
use App\Services\PgProvider\DeletePgProviderService;
use App\Services\PgProvider\GetPgProviderService;
use App\Services\PgProvider\ListPgProvidersService;
use App\Services\PgProvider\UpdatePgProviderService;
use Illuminate\Http\JsonResponse;

class PgProviderController extends BaseController
{
    /**
     * PG 제공자 목록 조회
     *
     * GET /api/pg-providers
     */
    public function index(ListPgProvidersRequest $request, ListPgProvidersService $service): JsonResponse
    {
        $filters = $request->getFilters();
        $result = $service->handle($filters);

        return $this->success($result, ResponseMessage::PG_PROVIDER_LIST_SUCCESS);
    }

    /**
     * PG 제공자 상세 조회
     *
     * GET /api/pg-providers/{pgProviderCode}
     */
    public function show(string $pgProviderCode, GetPgProviderService $service): JsonResponse
    {
        $pgProvider = $service->handle($pgProviderCode);

        return $this->success($pgProvider, ResponseMessage::PG_PROVIDER_DETAIL_SUCCESS);
    }

    /**
     * PG 제공자 생성
     *
     * POST /api/pg-providers
     */
    public function store(CreatePgProviderRequest $request, CreatePgProviderService $service): JsonResponse
    {
        $validated = $request->validated();
        $pgProvider = $service->handle($validated);

        return $this->success($pgProvider, ResponseMessage::PG_PROVIDER_CREATED);
    }

    /**
     * PG 제공자 정보 수정
     *
     * PUT /api/pg-providers/{pgProviderCode}
     */
    public function update(string $pgProviderCode, UpdatePgProviderRequest $request, UpdatePgProviderService $service): JsonResponse
    {
        $validated = $request->validated();
        $validated["pg_provider_code"] = $pgProviderCode;
        $pgProvider = $service->handle($validated);

        return $this->success($pgProvider, ResponseMessage::PG_PROVIDER_UPDATED);
    }

    /**
     * PG 제공자 활성화
     *
     * PATCH /api/pg-providers/{pgProviderCode}/activate
     */
    public function activate(string $pgProviderCode, ActivatePgProviderService $service): JsonResponse
    {
        $pgProvider = $service->handle(["pg_provider_code" => $pgProviderCode]);

        return $this->success($pgProvider, ResponseMessage::PG_PROVIDER_ACTIVATED);
    }

    /**
     * PG 제공자 비활성화
     *
     * PATCH /api/pg-providers/{pgProviderCode}/deactivate
     */
    public function deactivate(string $pgProviderCode, DeactivatePgProviderService $service): JsonResponse
    {
        $pgProvider = $service->handle(["pg_provider_code" => $pgProviderCode]);

        return $this->success($pgProvider, ResponseMessage::PG_PROVIDER_DEACTIVATED);
    }

    /**
     * PG 제공자 삭제
     *
     * DELETE /api/pg-providers/{pgProviderCode}
     */
    public function destroy(string $pgProviderCode, DeletePgProviderService $service): JsonResponse
    {
        $service->handle(["pg_provider_code" => $pgProviderCode]);

        return $this->success(null, ResponseMessage::PG_PROVIDER_DELETED);
    }
}
