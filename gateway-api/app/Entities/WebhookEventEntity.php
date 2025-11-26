<?php

namespace App\Entities;

/**
 * WebhookEvent Entity - 웹훅 이벤트 도메인 데이터 객체
 *
 * Repository에서 조회한 배열 데이터를 받아 순수 데이터 + 헬퍼 메서드만 제공한다.
 * Service/Validator 레이어가 상태를 직관적으로 판별할 수 있도록 boolean 헬퍼를 포함한다.
 */
class WebhookEventEntity
{
    /**
     * 웹훅 이벤트 ID (PRIMARY KEY)
     */
    public int $webhookEventId;

    /**
     * PG 제공자 코드 (FOREIGN KEY)
     */
    public string $pgProviderCode;

    /**
     * 이벤트 유형 (payment.approved, payment.cancelled, payment.refunded 등)
     */
    public string $eventType;

    /**
     * 웹훅 페이로드 (JSON)
     */
    public array $payload;

    /**
     * 처리 상태 (received|processing|processed|failed)
     */
    public string $status;

    /**
     * 수신일시
     */
    public string $receivedAt;

    /**
     * 처리일시 (nullable)
     */
    public ?string $processedAt;

    /**
     * 생성일시
     */
    public string $createdAt;

    /**
     * 생성자 - 배열 데이터로부터 Entity 생성
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->webhookEventId = (int) $data["webhook_event_id"];
        $this->pgProviderCode = $data["pg_provider_code"];
        $this->eventType = $data["event_type"];

        // JSON 문자열을 배열로 변환
        $this->payload = is_string($data["payload"])
            ? json_decode($data["payload"], true)
            : $data["payload"];

        $this->status = $data["status"];
        $this->receivedAt = $data["received_at"];
        $this->processedAt = $data["processed_at"] ?? null;
        $this->createdAt = $data["created_at"];
    }

    // ========================================
    // 상태 헬퍼 메서드
    // ========================================

    /**
     * 수신 완료 상태 여부 (received)
     */
    public function isReceived(): bool
    {
        return $this->status === "received";
    }

    /**
     * 처리 중 상태 여부 (processing)
     */
    public function isProcessing(): bool
    {
        return $this->status === "processing";
    }

    /**
     * 처리 완료 상태 여부 (processed)
     */
    public function isProcessed(): bool
    {
        return $this->status === "processed";
    }

    /**
     * 처리 실패 상태 여부 (failed)
     */
    public function isFailed(): bool
    {
        return $this->status === "failed";
    }

    // ========================================
    // 이벤트 타입 헬퍼 메서드
    // ========================================

    /**
     * 결제 승인 이벤트인지 확인
     */
    public function isPaymentApproved(): bool
    {
        return $this->eventType === "payment.approved";
    }

    /**
     * 결제 취소 이벤트인지 확인
     */
    public function isPaymentCancelled(): bool
    {
        return $this->eventType === "payment.cancelled";
    }

    /**
     * 결제 환불 이벤트인지 확인
     */
    public function isPaymentRefunded(): bool
    {
        return $this->eventType === "payment.refunded";
    }

    /**
     * 결제 실패 이벤트인지 확인
     */
    public function isPaymentFailed(): bool
    {
        return $this->eventType === "payment.failed";
    }

    // ========================================
    // 비즈니스 로직 헬퍼 메서드
    // ========================================

    /**
     * 처리 가능한 상태인지 확인 (received만 처리 가능)
     */
    public function isProcessable(): bool
    {
        return $this->status === "received";
    }

    /**
     * 재처리 가능한 상태인지 확인 (failed만 재처리 가능)
     */
    public function isRetryable(): bool
    {
        return $this->status === "failed";
    }

    /**
     * PG 거래 ID 추출 (payload에서)
     */
    public function getPgTransactionId(): ?string
    {
        return $this->payload["pg_transaction_id"] ?? $this->payload["transactionId"] ?? null;
    }

    /**
     * 결제 ID 추출 (payload에서)
     */
    public function getPaymentId(): ?int
    {
        return isset($this->payload["payment_id"]) ? (int) $this->payload["payment_id"] : null;
    }
}
