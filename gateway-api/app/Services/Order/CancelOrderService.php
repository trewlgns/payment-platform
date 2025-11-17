<?php

namespace App\Services\Order;

use App\Repositories\OrderRepository;
use App\Services\BaseService;
use App\Validators\OrderValidator;

/**
 * 주문 취소 Service
 *
 * POST /api/orders/{orderId}/cancel
 */
class CancelOrderService extends BaseService
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
     * 주문 취소
     *
     * @param int $orderId
     * @return array
     */
    protected function execute(...$args): array
    {
        $orderId = $args[0];

        // 취소 가능한 주문인지 확인 (존재 + 취소 가능 상태)
        $order = $this->orderValidator->validateCancellableOrder($orderId);

        // 상태를 cancelled로 변경
        $this->orderRepo->updateStatus($orderId, "cancelled");

        // 취소된 주문 조회
        $cancelledOrder = $this->orderRepo->findById($orderId);

        return [
            "order_id" => $cancelledOrder->orderId,
            "customer_email" => $cancelledOrder->customerEmail,
            "total_amount" => $cancelledOrder->totalAmount,
            "discount_amount" => $cancelledOrder->discountAmount,
            "final_amount" => $cancelledOrder->finalAmount,
            "status" => $cancelledOrder->status,
            "ordered_at" => $cancelledOrder->orderedAt,
            "created_at" => $cancelledOrder->createdAt,
            "updated_at" => $cancelledOrder->updatedAt
        ];
    }
}
