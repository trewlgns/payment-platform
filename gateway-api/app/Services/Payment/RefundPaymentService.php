<?php

namespace App\Services\Payment;

use App\Repositories\PaymentRepository;
use App\Services\BaseService;
use App\Validators\PaymentValidator;

/**
 * 결제 환불 Service
 *
 * POST /api/payments/{paymentId}/refund
 */
class RefundPaymentService extends BaseService
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
     * 결제 환불
     *
     * @param int $paymentId
     * @return array
     */
    protected function execute(...$args): array
    {
        $paymentId = $args[0];

        // 환불 가능한 결제인지 확인 (존재 + 승인 완료 + 미환불 + 미취소)
        $payment = $this->paymentValidator->validateRefundablePayment($paymentId);

        // 상태를 refunded로 변경
        $this->paymentRepo->updateStatus($paymentId, "refunded");

        // 환불된 결제 조회
        $refundedPayment = $this->paymentRepo->findById($paymentId);

        return [
            "payment_id" => $refundedPayment->paymentId,
            "order_id" => $refundedPayment->orderId,
            "pg_provider_code" => $refundedPayment->pgProviderCode,
            "amount" => $refundedPayment->amount,
            "status" => $refundedPayment->status,
            "payment_method" => $refundedPayment->paymentMethod,
            "idempotency_key" => $refundedPayment->idempotencyKey,
            "paid_at" => $refundedPayment->paidAt,
            "created_at" => $refundedPayment->createdAt,
            "updated_at" => $refundedPayment->updatedAt
        ];
    }
}
