<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessage;
use App\Services\Payment\GetPaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends BaseController
{
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
}
