<?php

namespace App\Services\Order;

use App\Repositories\OrderRepository;
use App\Services\BaseService;
use App\Validators\OrderValidator;

/**
 * 주문 상태 변경 Service
 *
 * PATCH /api/orders/{orderId}/status
 */
class UpdateOrderStatusService extends BaseService
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
     * 주문 상태 변경
     *
     * @param int $orderId
     * @param array $data ["status"]
     * @return array
     */
    protected function execute(...$args): array
    {
        $orderId = $args[0];
        $data = $args[1];

        // 주문 존재 여부 확인
        $order = $this->orderValidator->validateOrderExists($orderId);

        // 상태 업데이트
        $this->orderRepo->updateStatus($orderId, $data["status"]);

        // 업데이트된 주문 조회
        $updatedOrder = $this->orderRepo->findById($orderId);

        return [
            "order_id" => $updatedOrder->orderId,
            "customer_email" => $updatedOrder->customerEmail,
            "total_amount" => $updatedOrder->totalAmount,
            "discount_amount" => $updatedOrder->discountAmount,
            "final_amount" => $updatedOrder->finalAmount,
            "status" => $updatedOrder->status,
            "ordered_at" => $updatedOrder->orderedAt,
            "created_at" => $updatedOrder->createdAt,
            "updated_at" => $updatedOrder->updatedAt
        ];
    }
}
