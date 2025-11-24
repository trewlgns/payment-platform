<?php

namespace App\Services\Refund;

use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\RefundRepository;
use App\Services\BaseService;
use App\Validators\OrderValidator;
use App\Validators\PaymentValidator;

/**
 * 환불 요청 생성 Service
 *
 * POST /api/refunds
 */
class CreateRefundService extends BaseService
{
    private RefundRepository $refundRepo;
    private OrderRepository $orderRepo;
    private PaymentRepository $paymentRepo;
    private OrderValidator $orderValidator;
    private PaymentValidator $paymentValidator;

    public function __construct()
    {
        parent::__construct();
        $this->refundRepo = new RefundRepository($this->db);
        $this->orderRepo = new OrderRepository($this->db);
        $this->paymentRepo = new PaymentRepository($this->db);
        $this->orderValidator = new OrderValidator($this->db);
        $this->paymentValidator = new PaymentValidator($this->db);
    }

    /**
     * 환불 요청 생성
     *
     * @param array $data ["order_id", "payment_id", "requested_amount", "reason"?]
     * @return array
     */
    protected function execute(...$args): array
    {
        $data = $args[0];

        // 주문 존재 검증
        $order = $this->orderValidator->validateOrderExists($data["order_id"]);

        // 결제 존재 검증
        $payment = $this->paymentValidator->validatePaymentExists($data["payment_id"]);

        // 환불 요청 생성
        $refundRequestId = $this->refundRepo->create([
            "order_id" => $data["order_id"],
            "payment_id" => $data["payment_id"],
            "requested_amount" => $data["requested_amount"],
            "reason" => $data["reason"] ?? null,
            "status" => "requested"
        ]);

        // 생성된 환불 요청 조회
        $refund = $this->refundRepo->findById($refundRequestId);

        return [
            "refund_request_id" => $refund->refundRequestId,
            "order_id" => $refund->orderId,
            "payment_id" => $refund->paymentId,
            "requested_amount" => $refund->requestedAmount,
            "reason" => $refund->reason,
            "status" => $refund->status,
            "requested_at" => $refund->requestedAt,
            "created_at" => $refund->createdAt,
            "updated_at" => $refund->updatedAt
        ];
    }
}
