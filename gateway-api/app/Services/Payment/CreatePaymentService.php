<?php

namespace App\Services\Payment;

use App\Repositories\PaymentRepository;
use App\Repositories\OrderRepository;
use App\Services\BaseService;
use App\Validators\PaymentValidator;
use App\Validators\OrderValidator;

/**
 * 결제 생성 Service
 *
 * POST /api/payments
 */
class CreatePaymentService extends BaseService
{
    private PaymentRepository $paymentRepo;
    private OrderRepository $orderRepo;
    private PaymentValidator $paymentValidator;
    private OrderValidator $orderValidator;

    public function __construct()
    {
        parent::__construct();
        $this->paymentRepo = new PaymentRepository($this->db);
        $this->orderRepo = new OrderRepository($this->db);
        $this->paymentValidator = new PaymentValidator($this->db);
        $this->orderValidator = new OrderValidator($this->db);
    }

    /**
     * 결제 생성
     *
     * @param array $data
     * @return array
     */
    protected function execute(...$args): array
    {
        $data = $args[0];

        // 주문 존재 여부 확인
        $order = $this->orderValidator->validateOrderExists($data["order_id"]);

        // 멱등성 키 중복 확인
        $this->paymentValidator->validateIdempotencyKeyNotExists($data["idempotency_key"]);

        // 결제 생성
        $paymentId = $this->paymentRepo->create([
            "order_id" => $data["order_id"],
            "pg_provider_code" => $data["pg_provider_code"],
            "amount" => $data["amount"],
            "status" => "pending",
            "payment_method" => $data["payment_method"],
            "idempotency_key" => $data["idempotency_key"]
        ]);

        // 생성된 결제 조회
        $payment = $this->paymentRepo->findById($paymentId);

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
