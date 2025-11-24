<?php

namespace App\Services\Refund;

use App\Repositories\RefundRepository;
use App\Services\BaseService;

/**
 * 환불 요청 목록 조회 Service
 *
 * GET /api/refunds
 */
class ListRefundsService extends BaseService
{
    private RefundRepository $refundRepo;

    public function __construct()
    {
        parent::__construct();
        $this->refundRepo = new RefundRepository($this->db);
    }

    /**
     * 환불 요청 목록 조회 (필터 + 페이지네이션)
     *
     * @param array $filters ["status"?, "order_id"?, "payment_id"?, "page"?, "per_page"?]
     * @return array
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];

        $refunds = $this->refundRepo->findAll($filters);
        $totalCount = $this->refundRepo->count($filters);

        $page = $filters["page"] ?? 1;
        $perPage = $filters["per_page"] ?? 20;

        return [
            "data" => array_map(fn($refund) => [
                "refund_request_id" => $refund->refundRequestId,
                "order_id" => $refund->orderId,
                "payment_id" => $refund->paymentId,
                "requested_amount" => $refund->requestedAmount,
                "reason" => $refund->reason,
                "status" => $refund->status,
                "requested_at" => $refund->requestedAt,
                "processed_at" => $refund->processedAt,
                "created_at" => $refund->createdAt,
                "updated_at" => $refund->updatedAt
            ], $refunds),
            "pagination" => [
                "page" => $page,
                "per_page" => $perPage,
                "total" => $totalCount,
                "total_pages" => (int) ceil($totalCount / $perPage)
            ]
        ];
    }
}
