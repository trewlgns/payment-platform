<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessage;
use App\Http\Requests\Promotion\CreatePromotionRequest;
use App\Http\Requests\Promotion\ListPromotionsRequest;
use App\Http\Requests\Promotion\UpdatePromotionRequest;
use App\Services\Promotion\ActivatePromotionService;
use App\Services\Promotion\CreatePromotionService;
use App\Services\Promotion\DeactivatePromotionService;
use App\Services\Promotion\DeletePromotionService;
use App\Services\Promotion\GetPromotionService;
use App\Services\Promotion\ListPromotionsService;
use App\Services\Promotion\UpdatePromotionService;
use Illuminate\Http\JsonResponse;

/**
 * 프로모션 관리 Controller
 */
class PromotionController extends BaseController
{
    /**
     * 프로모션 목록 조회
     *
     * GET /api/promotions
     */
    public function index(ListPromotionsRequest $request, ListPromotionsService $service): JsonResponse
    {
        $filters = $request->getFilters();
        $result = $service->handle($filters);

        return $this->success($result, ResponseMessage::PROMOTION_LIST_SUCCESS);
    }

    /**
     * 프로모션 상세 조회
     *
     * GET /api/promotions/{promotionCode}
     */
    public function show(string $promotionCode, GetPromotionService $service): JsonResponse
    {
        $promotion = $service->handle($promotionCode);

        return $this->success($promotion, ResponseMessage::PROMOTION_DETAIL_SUCCESS);
    }

    /**
     * 프로모션 생성
     *
     * POST /api/promotions
     */
    public function store(CreatePromotionRequest $request, CreatePromotionService $service): JsonResponse
    {
        $validated = $request->validated();
        $promotion = $service->handle($validated);

        return $this->success($promotion, ResponseMessage::PROMOTION_CREATED);
    }

    /**
     * 프로모션 정보 수정
     *
     * PUT /api/promotions/{promotionCode}
     */
    public function update(string $promotionCode, UpdatePromotionRequest $request, UpdatePromotionService $service): JsonResponse
    {
        $validated = $request->validated();
        $promotion = $service->handle($promotionCode, $validated);

        return $this->success($promotion, ResponseMessage::PROMOTION_UPDATED);
    }

    /**
     * 프로모션 활성화
     *
     * PATCH /api/promotions/{promotionCode}/activate
     */
    public function activate(string $promotionCode, ActivatePromotionService $service): JsonResponse
    {
        $promotion = $service->handle($promotionCode);

        return $this->success($promotion, ResponseMessage::PROMOTION_ACTIVATED);
    }

    /**
     * 프로모션 비활성화
     *
     * PATCH /api/promotions/{promotionCode}/deactivate
     */
    public function deactivate(string $promotionCode, DeactivatePromotionService $service): JsonResponse
    {
        $promotion = $service->handle($promotionCode);

        return $this->success($promotion, ResponseMessage::PROMOTION_DEACTIVATED);
    }

    /**
     * 프로모션 삭제
     *
     * DELETE /api/promotions/{promotionCode}
     */
    public function destroy(string $promotionCode, DeletePromotionService $service): JsonResponse
    {
        $result = $service->handle($promotionCode);

        return $this->success($result, ResponseMessage::PROMOTION_DELETED);
    }
}
