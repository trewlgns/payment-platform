<?php

namespace App\Validators;

use App\Database\DB;
use App\Entities\RefundEntity;
use App\Exceptions\ConflictException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Repositories\RefundRepository;

/**
 * Refund 도메인 Validator
 *
 * 환불 요청 존재 여부, 상태, 승인/거부 가능 여부 등 Semantic 검증을 담당한다.
 */
class RefundValidator extends BaseValidator
{
    private RefundRepository $refundRepo;

    public function __construct(DB &$db)
    {
        parent::__construct($db);
        $this->refundRepo = new RefundRepository($db);
    }

    // ========================================
    // 존재성 검증
    // ========================================

    /**
     * 환불 요청 존재 여부 확인 (존재하지 않으면 예외)
     *
     * @param int $refundRequestId
     * @return RefundEntity
     * @throws NotFoundException
     */
    public function validateRefundExists(int $refundRequestId): RefundEntity
    {
        $refund = $this->refundRepo->findById($refundRequestId);

        if (!$refund) {
            throw new NotFoundException(__("messages.refund_not_found"));
        }

        return $refund;
    }

    // ========================================
    // 상태 검증
    // ========================================

    /**
     * 환불 요청이 승인 가능한 상태인지 확인
     *
     * @param RefundEntity $refund
     * @return void
     * @throws ForbiddenException
     */
    public function validateRefundApprovable(RefundEntity $refund): void
    {
        if (!$refund->isApprovable()) {
            throw new ForbiddenException(__("messages.refund_not_approvable"));
        }
    }

    /**
     * 환불 요청이 거부 가능한 상태인지 확인
     *
     * @param RefundEntity $refund
     * @return void
     * @throws ForbiddenException
     */
    public function validateRefundRejectable(RefundEntity $refund): void
    {
        if (!$refund->isRejectable()) {
            throw new ForbiddenException(__("messages.refund_not_rejectable"));
        }
    }

    /**
     * 환불 요청이 이미 완료되지 않았는지 확인
     *
     * @param RefundEntity $refund
     * @return void
     * @throws ConflictException
     */
    public function validateRefundNotCompleted(RefundEntity $refund): void
    {
        if ($refund->isCompleted()) {
            throw new ConflictException(__("messages.refund_already_completed"));
        }
    }

    /**
     * 환불 요청이 이미 거부되지 않았는지 확인
     *
     * @param RefundEntity $refund
     * @return void
     * @throws ConflictException
     */
    public function validateRefundNotRejected(RefundEntity $refund): void
    {
        if ($refund->isRejected()) {
            throw new ConflictException(__("messages.refund_already_rejected"));
        }
    }

    /**
     * 환불 요청이 처리 중인 상태인지 확인
     *
     * @param RefundEntity $refund
     * @return void
     * @throws ForbiddenException
     */
    public function validateRefundInProgress(RefundEntity $refund): void
    {
        if (!$refund->isInProgress()) {
            throw new ForbiddenException(__("messages.refund_not_in_progress"));
        }
    }

    /**
     * 환불 요청이 최종 상태가 아닌지 확인
     *
     * @param RefundEntity $refund
     * @return void
     * @throws ConflictException
     */
    public function validateRefundNotFinal(RefundEntity $refund): void
    {
        if ($refund->isFinal()) {
            throw new ConflictException(__("messages.refund_already_final"));
        }
    }

    // ========================================
    // 복합 검증
    // ========================================

    /**
     * 승인 가능한 환불 요청인지 확인 (존재 + requested 상태)
     *
     * @param int $refundRequestId
     * @return RefundEntity
     */
    public function validateApprovableRefund(int $refundRequestId): RefundEntity
    {
        $refund = $this->validateRefundExists($refundRequestId);
        $this->validateRefundApprovable($refund);

        return $refund;
    }

    /**
     * 거부 가능한 환불 요청인지 확인 (존재 + requested 상태)
     *
     * @param int $refundRequestId
     * @return RefundEntity
     */
    public function validateRejectableRefund(int $refundRequestId): RefundEntity
    {
        $refund = $this->validateRefundExists($refundRequestId);
        $this->validateRefundRejectable($refund);

        return $refund;
    }
}
