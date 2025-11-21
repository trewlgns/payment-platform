<?php

namespace App\Adapters;

/**
 * Payment Gateway Adapter Interface
 *
 * 여러 PG사(토스페이먼츠, 카카오페이 등)의 서로 다른 API를 통일된 인터페이스로 추상화
 */
interface PaymentGatewayInterface
{
    /**
     * 결제 승인 요청
     *
     * @param array $paymentData 결제 데이터
     *   - order_id: 주문 ID
     *   - amount: 결제 금액
     *   - payment_method: 결제 수단 (card, transfer, virtual_account)
     *   - card_number: 카드 번호 (카드 결제 시)
     *   - card_expiry_month: 카드 만료 월 (카드 결제 시)
     *   - card_expiry_year: 카드 만료 년 (카드 결제 시)
     *   - card_cvv: 카드 CVV (카드 결제 시)
     *
     * @return array 승인 결과
     *   - pg_transaction_id: PG사 거래 ID
     *   - status: 결제 상태 (approved, failed)
     *   - approved_at: 승인 시각 (ISO 8601 형식)
     *   - card_masked: 마스킹된 카드 번호 (카드 결제 시)
     *   - card_issuer_code: 카드사 코드 (카드 결제 시)
     *   - error_code: 에러 코드 (실패 시)
     *   - error_message: 에러 메시지 (실패 시)
     */
    public function approve(array $paymentData): array;

    /**
     * 결제 취소
     *
     * @param string $pgTransactionId PG사 거래 ID
     * @param int $amount 취소 금액
     * @param string|null $reason 취소 사유
     *
     * @return array 취소 결과
     *   - pg_transaction_id: PG사 거래 ID
     *   - status: 결제 상태 (cancelled)
     *   - cancelled_at: 취소 시각 (ISO 8601 형식)
     */
    public function cancel(string $pgTransactionId, int $amount, ?string $reason = null): array;

    /**
     * 결제 환불
     *
     * @param string $pgTransactionId PG사 거래 ID
     * @param int $amount 환불 금액
     * @param string|null $reason 환불 사유
     *
     * @return array 환불 결과
     *   - pg_transaction_id: PG사 거래 ID
     *   - status: 결제 상태 (refunded)
     *   - refunded_at: 환불 시각 (ISO 8601 형식)
     */
    public function refund(string $pgTransactionId, int $amount, ?string $reason = null): array;
}
