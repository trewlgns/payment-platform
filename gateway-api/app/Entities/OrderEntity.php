<?php

namespace App\Entities;

/**
 * Order Entity - 주문 도메인 데이터 객체
 *
 * Repository에서 조회한 배열 데이터를 받아 순수 데이터 + 헬퍼 메서드만 제공한다.
 * Service/Validator 레이어가 상태를 직관적으로 판별할 수 있도록 boolean 헬퍼를 포함한다.
 */
class OrderEntity
{
    /**
     * 주문 ID (PRIMARY KEY)
     */
    public int $orderId;

    /**
     * 고객 이메일 (FOREIGN KEY)
     */
    public string $customerEmail;

    /**
     * 총 주문 금액
     */
    public string $totalAmount;

    /**
     * 할인 금액
     */
    public string $discountAmount;

    /**
     * 최종 결제 금액
     */
    public string $finalAmount;

    /**
     * 주문 상태 (pending|confirmed|paid|preparing|shipped|delivered|cancelled|refunded)
     */
    public string $status;

    /**
     * 주문일시
     */
    public string $orderedAt;

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
        $this->orderId = (int) $data["order_id"];
        $this->customerEmail = $data["customer_email"];
        $this->totalAmount = $data["total_amount"];
        $this->discountAmount = $data["discount_amount"];
        $this->finalAmount = $data["final_amount"];
        $this->status = $data["status"];
        $this->orderedAt = $data["ordered_at"];
        $this->createdAt = $data["created_at"];
        $this->updatedAt = $data["updated_at"];
    }

    // ========================================
    // 상태 헬퍼
    // ========================================

    /**
     * 대기 중 상태 여부 (pending)
     */
    public function isPending(): bool
    {
        return $this->status === "pending";
    }

    /**
     * 확정 상태 여부 (confirmed)
     */
    public function isConfirmed(): bool
    {
        return $this->status === "confirmed";
    }

    /**
     * 결제 완료 상태 여부 (paid)
     */
    public function isPaid(): bool
    {
        return $this->status === "paid";
    }

    /**
     * 준비 중 상태 여부 (preparing)
     */
    public function isPreparing(): bool
    {
        return $this->status === "preparing";
    }

    /**
     * 배송 중 상태 여부 (shipped)
     */
    public function isShipped(): bool
    {
        return $this->status === "shipped";
    }

    /**
     * 배송 완료 상태 여부 (delivered)
     */
    public function isDelivered(): bool
    {
        return $this->status === "delivered";
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

    /**
     * 취소 가능한 상태인지 확인 (pending, confirmed만 취소 가능)
     */
    public function isCancellable(): bool
    {
        return in_array($this->status, ["pending", "confirmed"]);
    }

    /**
     * 환불 가능한 상태인지 확인 (paid, preparing, shipped만 환불 가능)
     */
    public function isRefundable(): bool
    {
        return in_array($this->status, ["paid", "preparing", "shipped"]);
    }

    /**
     * 완료된 주문인지 확인 (delivered, cancelled, refunded)
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, ["delivered", "cancelled", "refunded"]);
    }
}
