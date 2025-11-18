<?php

namespace App\Validators;

use App\Database\DB;
use App\Entities\PaymentEntity;
use App\Exceptions\ConflictException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Repositories\PaymentRepository;

/**
 * Payment 도메인 Validator
 *
 * 결제 존재 여부, 상태, 취소/환불 가능 여부 등 Semantic 검증을 담당한다.
 */
class PaymentValidator extends BaseValidator
{
    private PaymentRepository $paymentRepo;

    public function __construct(DB &$db)
    {
        parent::__construct($db);
        $this->paymentRepo = new PaymentRepository($db);
    }

    // ========================================
    // 존재성 검증
    // ========================================

    /**
     * 결제 존재 여부 확인 (존재하지 않으면 예외)
     *
     * @param int $paymentId
     * @return PaymentEntity
     * @throws NotFoundException
     */
    public function validatePaymentExists(int $paymentId): PaymentEntity
    {
        $payment = $this->paymentRepo->findById($paymentId);

        if (!$payment) {
            throw new NotFoundException(__("messages.payment_not_found"));
        }

        return $payment;
    }

    /**
     * 멱등성 키가 이미 사용되었는지 확인 (중복이면 예외)
     *
     * @param string $idempotencyKey
     * @return void
     * @throws ConflictException
     */
    public function validateIdempotencyKeyNotExists(string $idempotencyKey): void
    {
        if ($this->paymentRepo->existsByIdempotencyKey($idempotencyKey)) {
            throw new ConflictException(__("messages.payment_idempotency_key_duplicate"));
        }
    }

    // ========================================
    // 상태 검증
    // ========================================

    /**
     * 결제가 승인 완료 상태인지 확인
     *
     * @param PaymentEntity $payment
     * @return void
     * @throws ForbiddenException
     */
    public function validatePaymentApproved(PaymentEntity $payment): void
    {
        if (!$payment->isApproved()) {
            throw new ForbiddenException(__("messages.payment_not_approved"));
        }
    }

    /**
     * 결제가 취소 가능한 상태인지 확인
     *
     * @param PaymentEntity $payment
     * @return void
     * @throws ForbiddenException
     */
    public function validatePaymentCancellable(PaymentEntity $payment): void
    {
        if (!$payment->isCancellable()) {
            throw new ForbiddenException(__("messages.payment_not_cancellable"));
        }
    }

    /**
     * 결제가 환불 가능한 상태인지 확인
     *
     * @param PaymentEntity $payment
     * @return void
     * @throws ForbiddenException
     */
    public function validatePaymentRefundable(PaymentEntity $payment): void
    {
        if (!$payment->isRefundable()) {
            throw new ForbiddenException(__("messages.payment_not_refundable"));
        }
    }

    /**
     * 결제가 이미 취소되지 않았는지 확인
     *
     * @param PaymentEntity $payment
     * @return void
     * @throws ConflictException
     */
    public function validatePaymentNotCancelled(PaymentEntity $payment): void
    {
        if ($payment->isCancelled()) {
            throw new ConflictException(__("messages.payment_already_cancelled"));
        }
    }

    /**
     * 결제가 이미 환불되지 않았는지 확인
     *
     * @param PaymentEntity $payment
     * @return void
     * @throws ConflictException
     */
    public function validatePaymentNotRefunded(PaymentEntity $payment): void
    {
        if ($payment->isRefunded()) {
            throw new ConflictException(__("messages.payment_already_refunded"));
        }
    }

    /**
     * 결제가 실패 상태가 아닌지 확인
     *
     * @param PaymentEntity $payment
     * @return void
     * @throws ForbiddenException
     */
    public function validatePaymentNotFailed(PaymentEntity $payment): void
    {
        if ($payment->isFailed()) {
            throw new ForbiddenException(__("messages.payment_failed"));
        }
    }

    // ========================================
    // 복합 검증
    // ========================================

    /**
     * 취소 가능한 결제인지 확인 (존재 + 승인 완료 + 미취소)
     *
     * @param int $paymentId
     * @return PaymentEntity
     */
    public function validateCancellablePayment(int $paymentId): PaymentEntity
    {
        $payment = $this->validatePaymentExists($paymentId);
        $this->validatePaymentNotCancelled($payment);
        $this->validatePaymentNotFailed($payment);
        $this->validatePaymentCancellable($payment);

        return $payment;
    }

    /**
     * 환불 가능한 결제인지 확인 (존재 + 승인 완료 + 미환불)
     *
     * @param int $paymentId
     * @return PaymentEntity
     */
    public function validateRefundablePayment(int $paymentId): PaymentEntity
    {
        $payment = $this->validatePaymentExists($paymentId);
        $this->validatePaymentNotRefunded($payment);
        $this->validatePaymentNotCancelled($payment);
        $this->validatePaymentNotFailed($payment);
        $this->validatePaymentRefundable($payment);

        return $payment;
    }
}
