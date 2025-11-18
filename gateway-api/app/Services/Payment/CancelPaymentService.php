<?php

namespace App\Services\Payment;

use App\Repositories\PaymentRepository;
use App\Services\BaseService;
use App\Validators\PaymentValidator;

/**
 * 결제 취소 Service
 *
 * POST /api/payments/{paymentId}/cancel
 */
class CancelPaymentService extends BaseService
{
    private PaymentRepository $paymentRepo;
    private PaymentValidator $paymentValidator;

    public function __construct()
    {
        parent::__construct();
        $this->paymentRepo = new PaymentRepository($this->db);
        $this->paymentValidator = new PaymentValidator($this->db);
    }

    /**
     * 결제 취소
     *
     * @param int $paymentId
     * @return array
     */
    protected function execute(...$args): array
    {
        $paymentId = $args[0];

        // 취소 가능한 결제인지 확인 (존재 + 승인 완료 + 미취소)
        $payment = $this->paymentValidator->validateCancellablePayment($paymentId);

        // 상태를 cancelled로 변경
        $this->paymentRepo->updateStatus($paymentId, "cancelled");

        // 취소된 결제 조회
        $cancelledPayment = $this->paymentRepo->findById($paymentId);

        return [
            "payment_id" => $cancelledPayment->paymentId,
            "order_id" => $cancelledPayment->orderId,
            "pg_provider_code" => $cancelledPayment->pgProviderCode,
            "amount" => $cancelledPayment->amount,
            "status" => $cancelledPayment->status,
            "payment_method" => $cancelledPayment->paymentMethod,
            "idempotency_key" => $cancelledPayment->idempotencyKey,
            "paid_at" => $cancelledPayment->paidAt,
            "created_at" => $cancelledPayment->createdAt,
            "updated_at" => $cancelledPayment->updatedAt
        ];
    }
}
