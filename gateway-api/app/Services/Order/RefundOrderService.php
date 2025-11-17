<?php

namespace App\Services\Order;

use App\Repositories\OrderRepository;
use App\Services\BaseService;
use App\Validators\OrderValidator;

/**
 * 주문 환불 Service
 *
 * POST /api/orders/{orderId}/refund
 */
class RefundOrderService extends BaseService
{
    private OrderRepository $orderRepo;
    private OrderValidator $orderValidator;

    public function __construct()
    {
        parent::__construct();
        $this->orderRepo = new OrderRepository($this->db);
        $this->orderValidator = new OrderValidator($this->db);
    }

    /**
     * 주문 환불
     *
     * @param int $orderId
     * @return array
     */
    protected function execute(...$args): array
    {
        $orderId = $args[0];

        // 환불 가능한 주문인지 확인 (존재 + 환불 가능 상태)
        $order = $this->orderValidator->validateRefundableOrder($orderId);

        // 상태를 refunded로 변경
        $this->orderRepo->updateStatus($orderId, "refunded");

        // 환불된 주문 조회
        $refundedOrder = $this->orderRepo->findById($orderId);

        return [
            "order_id" => $refundedOrder->orderId,
            "customer_email" => $refundedOrder->customerEmail,
            "total_amount" => $refundedOrder->totalAmount,
            "discount_amount" => $refundedOrder->discountAmount,
            "final_amount" => $refundedOrder->finalAmount,
            "status" => $refundedOrder->status,
            "ordered_at" => $refundedOrder->orderedAt,
            "created_at" => $refundedOrder->createdAt,
            "updated_at" => $refundedOrder->updatedAt
        ];
    }
}
