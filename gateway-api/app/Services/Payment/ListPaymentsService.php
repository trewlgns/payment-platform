<?php

namespace App\Services\Payment;

use App\Repositories\PaymentRepository;
use App\Services\BaseService;

/**
 * 결제 목록 조회 Service
 *
 * GET /api/payments
 */
class ListPaymentsService extends BaseService
{
    private PaymentRepository $paymentRepo;

    public function __construct()
    {
        parent::__construct();
        $this->paymentRepo = new PaymentRepository($this->db);
    }

    /**
     * 결제 목록 조회 (필터 + 페이지네이션)
     *
     * @param array $filters ["status"?, "order_id"?, "pg_provider_code"?, "page"?, "per_page"?]
     * @return array
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];

        $payments = $this->paymentRepo->findAll($filters);
        $totalCount = $this->paymentRepo->count($filters);

        $page = $filters["page"] ?? 1;
        $perPage = $filters["per_page"] ?? 20;

        return [
            "data" => array_map(fn($payment) => [
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
            ], $payments),
            "pagination" => [
                "page" => $page,
                "per_page" => $perPage,
                "total" => $totalCount,
                "total_pages" => (int) ceil($totalCount / $perPage)
            ]
        ];
    }
}
