<?php

namespace App\Services\Payment;

use App\Repositories\PaymentRepository;
use App\Services\BaseService;
use App\Validators\PaymentValidator;

/**
 * 결제 상세 조회 Service
 *
 * GET /api/payments/{paymentId}
 */
class GetPaymentService extends BaseService
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
     * 결제 상세 조회
     *
     * @param int $paymentId
     * @return array
     */
    protected function execute(...$args): array
    {
        $paymentId = $args[0];

        // 결제 존재 여부 확인
        $payment = $this->paymentValidator->validatePaymentExists($paymentId);

        return [
            "payment_id" => $payment->paymentId,
            "order_id" => $payment->orderId,
            "pg_provider_code" => $payment->pgProviderCode,
            "amount" => $payment->amount,
            "status" => $payment->status,
            "payment_method" => $payment->paymentMethod,
            "idempotency_key" => $payment->idempotencyKey,
            "paid_at" => $payment->paidAt,
            "created_at" => $payment->createdAt,
            "updated_at" => $payment->updatedAt
        ];
    }
}
