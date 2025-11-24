<?php

namespace App\Entities;

/**
 * Refund Entity - 환불 요청 도메인 데이터 객체
 *
 * Repository에서 조회한 배열 데이터를 받아 순수 데이터 + 헬퍼 메서드만 제공한다.
 * Service/Validator 레이어가 상태를 직관적으로 판별할 수 있도록 boolean 헬퍼를 포함한다.
 */
class RefundEntity
{
    /**
     * 환불 요청 ID (PRIMARY KEY)
     */
    public int $refundRequestId;

    /**
     * 주문 ID (FOREIGN KEY)
     */
    public int $orderId;

    /**
     * 결제 ID (FOREIGN KEY)
     */
    public int $paymentId;

    /**
     * 환불 요청 금액
     */
    public string $requestedAmount;

    /**
     * 환불 사유 (nullable)
     */
    public ?string $reason;

    /**
     * 환불 상태 (requested|approved|processing|completed|rejected)
     */
    public string $status;

    /**
     * 요청일시
     */
    public string $requestedAt;

    /**
     * 처리일시 (nullable)
     */
    public ?string $processedAt;

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
        $this->refundRequestId = (int) $data["refund_request_id"];
        $this->orderId = (int) $data["order_id"];
        $this->paymentId = (int) $data["payment_id"];
        $this->requestedAmount = $data["requested_amount"];
        $this->reason = $data["reason"] ?? null;
        $this->status = $data["status"];
        $this->requestedAt = $data["requested_at"];
        $this->processedAt = $data["processed_at"] ?? null;
        $this->createdAt = $data["created_at"];
        $this->updatedAt = $data["updated_at"];
    }

    // ========================================
    // 상태 헬퍼 메서드
    // ========================================

    /**
     * 요청 상태 여부 (requested)
     */
    public function isRequested(): bool
    {
        return $this->status === "requested";
    }

    /**
     * 승인 상태 여부 (approved)
     */
    public function isApproved(): bool
    {
        return $this->status === "approved";
    }

    /**
     * 처리 중 상태 여부 (processing)
     */
    public function isProcessing(): bool
    {
        return $this->status === "processing";
    }

    /**
     * 완료 상태 여부 (completed)
     */
    public function isCompleted(): bool
    {
        return $this->status === "completed";
    }

    /**
     * 거부 상태 여부 (rejected)
     */
    public function isRejected(): bool
    {
        return $this->status === "rejected";
    }

    // ========================================
    // 비즈니스 로직 헬퍼 메서드
    // ========================================

    /**
     * 승인 가능한 상태인지 확인 (requested만 승인 가능)
     */
    public function isApprovable(): bool
    {
        return $this->status === "requested";
    }

    /**
     * 거부 가능한 상태인지 확인 (requested만 거부 가능)
     */
    public function isRejectable(): bool
    {
        return $this->status === "requested";
    }

    /**
     * 처리 완료된 환불인지 확인 (completed, rejected)
     */
    public function isFinal(): bool
    {
        return in_array($this->status, ["completed", "rejected"]);
    }

    /**
     * 처리가 진행 중인지 확인 (approved, processing)
     */
    public function isInProgress(): bool
    {
        return in_array($this->status, ["approved", "processing"]);
    }
}
