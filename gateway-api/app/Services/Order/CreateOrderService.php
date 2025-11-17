<?php

namespace App\Services\Order;

use App\Repositories\OrderRepository;
use App\Services\BaseService;
use App\Validators\UserValidator;

/**
 * 주문 생성 Service
 *
 * POST /api/orders
 */
class CreateOrderService extends BaseService
{
    private OrderRepository $orderRepo;
    private UserValidator $userValidator;

    public function __construct()
    {
        parent::__construct();
        $this->orderRepo = new OrderRepository($this->db);
        $this->userValidator = new UserValidator($this->db);
    }

    /**
     * 주문 생성
     *
     * @param array $data ["customer_email", "total_amount", "discount_amount", "final_amount"]
     * @return array
     */
    protected function execute(...$args): array
    {
        $data = $args[0];

        // 고객 존재 여부 확인
        $this->userValidator->validateUserExists($data["customer_email"]);

        // 주문 생성
        $orderId = $this->orderRepo->create([
            "customer_email" => $data["customer_email"],
            "total_amount" => $data["total_amount"],
            "discount_amount" => $data["discount_amount"] ?? "0.00",
            "final_amount" => $data["final_amount"],
            "status" => "pending"
        ]);

        // 생성된 주문 조회
        $order = $this->orderRepo->findById($orderId);

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
