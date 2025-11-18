<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessage;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Requests\Payment\ListPaymentsRequest;
use App\Services\Payment\CancelPaymentService;
use App\Services\Payment\CreatePaymentService;
use App\Services\Payment\GetPaymentService;
use App\Services\Payment\ListPaymentsService;
use App\Services\Payment\RefundPaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends BaseController
{
    /**
     * 결제 목록 조회
     *
     * GET /api/payments
     */
    public function index(ListPaymentsRequest $request, ListPaymentsService $service): JsonResponse
    {
        $filters = $request->getFilters();
        $result = $service->handle($filters);

        return $this->success($result, ResponseMessage::SUCCESS);
    }

    /**
     * 결제 상세 조회
     *
     * GET /api/payments/{paymentId}
     */
    public function show(int $paymentId, GetPaymentService $service): JsonResponse
    {
        $payment = $service->handle($paymentId);

        return $this->success($payment, ResponseMessage::PAYMENT_DETAIL_SUCCESS);
    }

    /**
     * 결제 생성
     *
     * POST /api/payments
     */
    public function store(CreatePaymentRequest $request, CreatePaymentService $service): JsonResponse
    {
        $validated = $request->validated();
        $payment = $service->handle($validated);

        return $this->success($payment, ResponseMessage::PAYMENT_SUCCESS);
    }

    /**
     * 결제 취소
     *
     * POST /api/payments/{paymentId}/cancel
     */
    public function cancel(int $paymentId, CancelPaymentService $service): JsonResponse
    {
        $payment = $service->handle($paymentId);

        return $this->success($payment, ResponseMessage::PAYMENT_CANCELLED);
    }

    /**
     * 결제 환불
     *
     * POST /api/payments/{paymentId}/refund
     */
    public function refund(int $paymentId, RefundPaymentService $service): JsonResponse
    {
        $payment = $service->handle($paymentId);

        return $this->success($payment, ResponseMessage::PAYMENT_REFUNDED);
    }
}
