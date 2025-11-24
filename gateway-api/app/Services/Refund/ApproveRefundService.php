<?php

namespace App\Services\Refund;

use App\Repositories\RefundRepository;
use App\Services\BaseService;
use App\Validators\RefundValidator;

/**
 * 환불 요청 승인 Service
 *
 * PATCH /api/refunds/{refundRequestId}/approve
 */
class ApproveRefundService extends BaseService
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
     * 환불 요청 승인
     *
     * @param int $refundRequestId
     * @return array
     */
    protected function execute(...$args): array
    {
        $refundRequestId = $args[0];

        // 승인 가능한 환불 요청인지 확인 (존재 + requested 상태)
        $refund = $this->refundValidator->validateApprovableRefund($refundRequestId);

        $now = date("Y-m-d H:i:s");

        // 상태를 approved로 변경
        $this->refundRepo->updateStatus($refundRequestId, "approved", $now);

        // 승인된 환불 요청 조회
        $approvedRefund = $this->refundRepo->findById($refundRequestId);

        return [
            "refund_request_id" => $approvedRefund->refundRequestId,
            "order_id" => $approvedRefund->orderId,
            "payment_id" => $approvedRefund->paymentId,
            "requested_amount" => $approvedRefund->requestedAmount,
            "reason" => $approvedRefund->reason,
            "status" => $approvedRefund->status,
            "requested_at" => $approvedRefund->requestedAt,
            "processed_at" => $approvedRefund->processedAt,
            "created_at" => $approvedRefund->createdAt,
            "updated_at" => $approvedRefund->updatedAt
        ];
    }
}
