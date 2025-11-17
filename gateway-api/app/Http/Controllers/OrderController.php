<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessage;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\ListOrdersRequest;
use App\Http\Requests\Order\UpdateOrderStatusRequest;
use App\Services\Order\CancelOrderService;
use App\Services\Order\CreateOrderService;
use App\Services\Order\GetOrderService;
use App\Services\Order\ListOrdersService;
use App\Services\Order\RefundOrderService;
use App\Services\Order\UpdateOrderStatusService;
use Illuminate\Http\JsonResponse;

class OrderController extends BaseController
{
    /**
     * 주문 목록 조회
     *
     * GET /api/orders
     */
    public function index(ListOrdersRequest $request, ListOrdersService $service): JsonResponse
    {
        $filters = $request->getFilters();
        $result = $service->handle($filters);

        return $this->success($result, ResponseMessage::ORDER_LIST_SUCCESS);
    }

    /**
     * 주문 상세 조회
     *
     * GET /api/orders/{orderId}
     */
    public function show(int $orderId, GetOrderService $service): JsonResponse
    {
        $order = $service->handle($orderId);

        return $this->success($order, ResponseMessage::ORDER_DETAIL_SUCCESS);
    }

    /**
     * 주문 생성
     *
     * POST /api/orders
     */
    public function store(CreateOrderRequest $request, CreateOrderService $service): JsonResponse
    {
        $validated = $request->validated();
        $order = $service->handle($validated);

        return $this->success($order, ResponseMessage::ORDER_CREATED);
    }

    /**
     * 주문 상태 변경
     *
     * PATCH /api/orders/{orderId}/status
     */
    public function updateStatus(
        int $orderId,
        UpdateOrderStatusRequest $request,
        UpdateOrderStatusService $service
    ): JsonResponse {
        $validated = $request->validated();
        $order = $service->handle($orderId, $validated);

        return $this->success($order, ResponseMessage::ORDER_STATUS_UPDATED);
    }

    /**
     * 주문 취소
     *
     * POST /api/orders/{orderId}/cancel
     */
    public function cancel(int $orderId, CancelOrderService $service): JsonResponse
    {
        $order = $service->handle($orderId);

        return $this->success($order, ResponseMessage::ORDER_CANCELLED);
    }

    /**
     * 주문 환불
     *
     * POST /api/orders/{orderId}/refund
     */
    public function refund(int $orderId, RefundOrderService $service): JsonResponse
    {
        $order = $service->handle($orderId);

        return $this->success($order, ResponseMessage::ORDER_REFUNDED);
    }
}
