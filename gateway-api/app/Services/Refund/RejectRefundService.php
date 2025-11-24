<?php

namespace App\Services\Refund;

use App\Repositories\RefundRepository;
use App\Services\BaseService;
use App\Validators\RefundValidator;

/**
 * 환불 요청 거부 Service
 *
 * PATCH /api/refunds/{refundRequestId}/reject
 */
class RejectRefundService extends BaseService
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
     * 환불 요청 거부
     *
     * @param int $refundRequestId
     * @return array
     */
    protected function execute(...$args): array
    {
        $refundRequestId = $args[0];

        // 거부 가능한 환불 요청인지 확인 (존재 + requested 상태)
        $refund = $this->refundValidator->validateRejectableRefund($refundRequestId);

        $now = date("Y-m-d H:i:s");

        // 상태를 rejected로 변경
        $this->refundRepo->updateStatus($refundRequestId, "rejected", $now);

        // 거부된 환불 요청 조회
        $rejectedRefund = $this->refundRepo->findById($refundRequestId);

        return [
            "refund_request_id" => $rejectedRefund->refundRequestId,
            "order_id" => $rejectedRefund->orderId,
            "payment_id" => $rejectedRefund->paymentId,
            "requested_amount" => $rejectedRefund->requestedAmount,
            "reason" => $rejectedRefund->reason,
            "status" => $rejectedRefund->status,
            "requested_at" => $rejectedRefund->requestedAt,
            "processed_at" => $rejectedRefund->processedAt,
            "created_at" => $rejectedRefund->createdAt,
            "updated_at" => $rejectedRefund->updatedAt
        ];
    }
}
