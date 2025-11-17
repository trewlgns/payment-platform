<?php

namespace App\Services\Order;

use App\Repositories\OrderRepository;
use App\Services\BaseService;

/**
 * 주문 목록 조회 Service
 *
 * GET /api/orders
 */
class ListOrdersService extends BaseService
{
    private OrderRepository $orderRepo;

    public function __construct()
    {
        parent::__construct();
        $this->orderRepo = new OrderRepository($this->db);
    }

    /**
     * 주문 목록 조회 (필터 + 페이지네이션)
     *
     * @param array $filters ["status"?, "customer_email"?, "page"?, "per_page"?]
     * @return array
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];

        $orders = $this->orderRepo->findAll($filters);
        $totalCount = $this->orderRepo->count($filters);

        $page = $filters["page"] ?? 1;
        $perPage = $filters["per_page"] ?? 20;

        return [
            "data" => array_map(fn($order) => [
                "order_id" => $order->orderId,
                "customer_email" => $order->customerEmail,
                "total_amount" => $order->totalAmount,
                "discount_amount" => $order->discountAmount,
                "final_amount" => $order->finalAmount,
                "status" => $order->status,
                "ordered_at" => $order->orderedAt,
                "created_at" => $order->createdAt,
                "updated_at" => $order->updatedAt
            ], $orders),
            "pagination" => [
                "page" => $page,
                "per_page" => $perPage,
                "total" => $totalCount,
                "total_pages" => (int) ceil($totalCount / $perPage)
            ]
        ];
    }
}
