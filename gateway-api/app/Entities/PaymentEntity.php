<?php

namespace App\Entities;

/**
 * Payment Entity - 결제 도메인 데이터 객체
 *
 * Repository에서 조회한 배열 데이터를 받아 순수 데이터 + 헬퍼 메서드만 제공한다.
 * Service/Validator 레이어가 상태를 직관적으로 판별할 수 있도록 boolean 헬퍼를 포함한다.
 */
class PaymentEntity
{
    /**
     * 결제 ID (PRIMARY KEY)
     */
    public int $paymentId;

    /**
     * 주문 ID (FOREIGN KEY)
     */
    public int $orderId;

    /**
     * PG 제공자 코드 (FOREIGN KEY)
     */
    public string $pgProviderCode;

    /**
     * 결제 금액
     */
    public string $amount;

    /**
     * 결제 상태 (pending|approved|failed|cancelled|refunded)
     */
    public string $status;

    /**
     * 결제 수단 (card|bank_transfer|virtual_account|mobile)
     */
    public string $paymentMethod;

    /**
     * 멱등성 키 (UNIQUE)
     */
    public string $idempotencyKey;

    /**
     * 결제 완료 시각 (nullable)
     */
    public ?string $paidAt;

    /**
     * 생성일시
     */
    public string $createdAt;

    /**
     * 수정일시
     */
    public string $updatedAt;

    /**
     * 생성자 - 배열 데이터로부터 Entity 생성
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->paymentId = (int) $data["payment_id"];
        $this->orderId = (int) $data["order_id"];
        $this->pgProviderCode = $data["pg_provider_code"];
        $this->amount = $data["amount"];
        $this->status = $data["status"];
        $this->paymentMethod = $data["payment_method"];
        $this->idempotencyKey = $data["idempotency_key"];
        $this->paidAt = $data["paid_at"] ?? null;
        $this->createdAt = $data["created_at"];
        $this->updatedAt = $data["updated_at"];
    }

    // ========================================
    // 상태 헬퍼 메서드
    // ========================================

    /**
     * 대기 중 상태 여부 (pending)
     */
    public function isPending(): bool
    {
        return $this->status === "pending";
    }

    /**
     * 승인 완료 상태 여부 (approved)
     */
    public function isApproved(): bool
    {
        return $this->status === "approved";
    }

    /**
     * 실패 상태 여부 (failed)
     */
    public function isFailed(): bool
    {
        return $this->status === "failed";
    }

    /**
     * 취소 상태 여부 (cancelled)
     */
    public function isCancelled(): bool
    {
        return $this->status === "cancelled";
    }

    /**
     * 환불 상태 여부 (refunded)
     */
    public function isRefunded(): bool
    {
        return $this->status === "refunded";
    }

    // ========================================
    // 비즈니스 로직 헬퍼 메서드
    // ========================================

    /**
     * 취소 가능한 상태인지 확인 (approved만 취소 가능)
     */
    public function isCancellable(): bool
    {
        return $this->status === "approved";
    }

    /**
     * 환불 가능한 상태인지 확인 (approved만 환불 가능)
     */
    public function isRefundable(): bool
    {
        return $this->status === "approved";
    }

    /**
     * 최종 완료된 결제인지 확인 (approved, cancelled, refunded, failed)
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, ["approved", "cancelled", "refunded", "failed"]);
    }

    /**
     * 카드 결제인지 확인
     */
    public function isCardPayment(): bool
    {
        return $this->paymentMethod === "card";
    }

    /**
     * 계좌이체 결제인지 확인
     */
    public function isBankTransfer(): bool
    {
        return $this->paymentMethod === "bank_transfer";
    }

    /**
     * 가상계좌 결제인지 확인
     */
    public function isVirtualAccount(): bool
    {
        return $this->paymentMethod === "virtual_account";
    }

    /**
     * 모바일 결제인지 확인
     */
    public function isMobilePayment(): bool
    {
        return $this->paymentMethod === "mobile";
    }
}
