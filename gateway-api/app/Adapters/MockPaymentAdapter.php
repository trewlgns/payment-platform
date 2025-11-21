<?php

namespace App\Adapters;

use App\Exceptions\InvalidParameterException;

/**
 * Mock Payment Gateway Adapter (테스트용)
 *
 * 실제 PG API 호출 없이 결제 시나리오를 시뮬레이션
 * 카드 번호 끝자리로 성공/실패 케이스 구분
 */
class MockPaymentAdapter implements PaymentGatewayInterface
{
    /**
     * 테스트 시나리오 매핑 (카드 번호 끝자리 기준)
     */
    private const SCENARIOS = [
        "0000" => ["success" => true, "description" => "정상 승인"],
        "1111" => ["success" => false, "error_code" => "INSUFFICIENT_BALANCE", "error_message" => "잔액이 부족합니다"],
        "2222" => ["success" => false, "error_code" => "CARD_EXPIRED", "error_message" => "만료된 카드입니다"],
        "3333" => ["success" => false, "error_code" => "INVALID_CVV", "error_message" => "CVV 번호가 올바르지 않습니다"],
        "4444" => ["success" => false, "error_code" => "STOLEN_CARD", "error_message" => "도난 카드로 등록되어 있습니다"],
        "5555" => ["success" => false, "error_code" => "INVALID_CARD", "error_message" => "유효하지 않은 카드입니다"],
    ];

    /**
     * 카드사 BIN 매핑 (앞 6자리)
     */
    private const CARD_ISSUERS = [
        "433812" => "SHINHAN",
        "540426" => "KB",
        "551011" => "SAMSUNG",
        "433112" => "HYUNDAI",
        "520141" => "LOTTE",
        "406648" => "HANA",
    ];

    /**
     * 결제 승인 요청 (시뮬레이션)
     *
     * @param array $paymentData
     * @return array
     * @throws InvalidParameterException
     */
    public function approve(array $paymentData): array
    {
        // 카드 결제만 지원 (Mock)
        if ($paymentData["payment_method"] !== "card") {
            throw new InvalidParameterException("Mock PG는 카드 결제만 지원합니다");
        }

        $cardNumber = $paymentData["card_number"] ?? "";

        // 카드 번호 끝 4자리로 시나리오 판단
        $lastFour = substr($cardNumber, -4);
        $scenario = self::SCENARIOS[$lastFour] ?? self::SCENARIOS["0000"]; // 기본: 성공

        // 실패 시나리오
        if (!$scenario["success"]) {
            return [
                "pg_transaction_id" => null,
                "status" => "failed",
                "approved_at" => null,
                "error_code" => $scenario["error_code"],
                "error_message" => $scenario["error_message"],
            ];
        }

        // 성공 시나리오
        $bin = substr($cardNumber, 0, 6);
        $issuerCode = self::CARD_ISSUERS[$bin] ?? "UNKNOWN";

        return [
            "pg_transaction_id" => "MOCK_" . strtoupper(uniqid()),
            "status" => "approved",
            "approved_at" => date("Y-m-d H:i:s"),
            "card_masked" => $this->maskCardNumber($cardNumber),
            "card_issuer_code" => $issuerCode,
        ];
    }

    /**
     * 결제 취소 (시뮬레이션)
     *
     * @param string $pgTransactionId
     * @param int $amount
     * @param string|null $reason
     * @return array
     */
    public function cancel(string $pgTransactionId, int $amount, ?string $reason = null): array
    {
        // Mock: 항상 성공
        return [
            "pg_transaction_id" => $pgTransactionId,
            "status" => "cancelled",
            "cancelled_at" => date("Y-m-d H:i:s"),
        ];
    }

    /**
     * 결제 환불 (시뮬레이션)
     *
     * @param string $pgTransactionId
     * @param int $amount
     * @param string|null $reason
     * @return array
     */
    public function refund(string $pgTransactionId, int $amount, ?string $reason = null): array
    {
        // Mock: 항상 성공
        return [
            "pg_transaction_id" => $pgTransactionId,
            "status" => "refunded",
            "refunded_at" => date("Y-m-d H:i:s"),
        ];
    }

    /**
     * 카드 번호 마스킹
     *
     * @param string $cardNumber
     * @return string
     */
    private function maskCardNumber(string $cardNumber): string
    {
        $length = strlen($cardNumber);
        if ($length < 8) {
            return str_repeat("*", $length);
        }

        $first = substr($cardNumber, 0, 4);
        $last = substr($cardNumber, -4);
        $masked = $first . str_repeat("*", $length - 8) . $last;

        return $masked;
    }
}
