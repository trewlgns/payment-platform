<?php

namespace App\Http\Controllers;

use App\Adapters\KakaoPayAdapter;
use App\Adapters\MockPaymentAdapter;
use App\Adapters\TossPaymentAdapter;
use App\Enums\PgProviderCode;
use App\Enums\ResponseMessage;
use App\Exceptions\InvalidParameterException;
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
    public function store(CreatePaymentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // PG 코드를 Enum으로 변환
        $pgCode = PgProviderCode::fromString($validated["pg_provider_code"]);

        if ($pgCode === null) {
            throw new InvalidParameterException("payment.unsupported_pg_provider"); // 실제 응답 문구: 지원하지 않는 PG사입니다
        }

        // PG 코드에 따라 적절한 Adapter 선택
        $gateway = match ($pgCode) {
            PgProviderCode::MOCK => new MockPaymentAdapter(),
            PgProviderCode::TOSS => new TossPaymentAdapter(),
            PgProviderCode::KAKAO => new KakaoPayAdapter(),
        };

        // Service에 Adapter 주입
        $service = new CreatePaymentService($gateway);
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
