<?php

namespace App\Services\Order;

use App\Repositories\OrderRepository;
use App\Services\BaseService;
use App\Validators\OrderValidator;

/**
 * 주문 상세 조회 Service
 *
 * GET /api/orders/{orderId}
 */
class GetOrderService extends BaseService
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
     * 주문 상세 조회
     *
     * @param int $orderId
     * @return array
     */
    protected function execute(...$args): array
    {
        $orderId = $args[0];

        // 주문 존재 여부 확인
        $order = $this->orderValidator->validateOrderExists($orderId);

        return [
            "order_id" => $order->orderId,
            "customer_email" => $order->customerEmail,
            "total_amount" => $order->totalAmount,
            "discount_amount" => $order->discountAmount,
            "final_amount" => $order->finalAmount,
            "status" => $order->status,
            "ordered_at" => $order->orderedAt,
            "created_at" => $order->createdAt,
            "updated_at" => $order->updatedAt
        ];
    }
}
