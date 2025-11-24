<?php

namespace App\Services\Refund;

use App\Repositories\RefundRepository;
use App\Services\BaseService;
use App\Validators\RefundValidator;

/**
 * 환불 요청 상세 조회 Service
 *
 * GET /api/refunds/{refundRequestId}
 */
class GetRefundService extends BaseService
{
    private RefundRepository $refundRepo;
    private RefundValidator $refundValidator;

    public function __construct()
    {
        parent::__construct();
        $this->refundRepo = new RefundRepository($this->db);
        $this->refundValidator = new RefundValidator($this->db);
    }

    /**
     * 환불 요청 상세 조회
     *
     * @param int $refundRequestId
     * @return array
     */
    protected function execute(...$args): array
    {
        $refundRequestId = $args[0];

        // 환불 요청 존재 검증
        $refund = $this->refundValidator->validateRefundExists($refundRequestId);

        return [
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
        ];
    }
}
