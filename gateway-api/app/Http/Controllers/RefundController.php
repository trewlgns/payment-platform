<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessage;
use App\Http\Requests\Refund\CreateRefundRequest;
use App\Http\Requests\Refund\ListRefundsRequest;
use App\Services\Refund\ApproveRefundService;
use App\Services\Refund\CreateRefundService;
use App\Services\Refund\GetRefundService;
use App\Services\Refund\ListRefundsService;
use App\Services\Refund\RejectRefundService;
use Illuminate\Http\JsonResponse;

class RefundController extends BaseController
{
    /**
     * 환불 요청 목록 조회
     *
     * GET /api/refunds
     */
    public function index(ListRefundsRequest $request, ListRefundsService $service): JsonResponse
    {
        $filters = $request->getFilters();
        $result = $service->handle($filters);

        return $this->success($result, ResponseMessage::REFUND_LIST_SUCCESS);
    }

    /**
     * 환불 요청 상세 조회
     *
     * GET /api/refunds/{refundRequestId}
     */
    public function show(int $refundRequestId, GetRefundService $service): JsonResponse
    {
        $refund = $service->handle($refundRequestId);

        return $this->success($refund, ResponseMessage::REFUND_DETAIL_SUCCESS);
    }

    /**
     * 환불 요청 생성
     *
     * POST /api/refunds
     */
    public function store(CreateRefundRequest $request, CreateRefundService $service): JsonResponse
    {
        $validated = $request->validated();
        $refund = $service->handle($validated);

        return $this->success($refund, ResponseMessage::REFUND_CREATED);
    }

    /**
     * 환불 요청 승인
     *
     * PATCH /api/refunds/{refundRequestId}/approve
     */
    public function approve(int $refundRequestId, ApproveRefundService $service): JsonResponse
    {
        $refund = $service->handle($refundRequestId);

        return $this->success($refund, ResponseMessage::REFUND_APPROVED);
    }

    /**
     * 환불 요청 거부
     *
     * PATCH /api/refunds/{refundRequestId}/reject
     */
    public function reject(int $refundRequestId, RejectRefundService $service): JsonResponse
    {
        $refund = $service->handle($refundRequestId);

        return $this->success($refund, ResponseMessage::REFUND_REJECTED);
    }
}
