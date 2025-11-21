<?php

namespace App\Adapters;

use Illuminate\Support\Facades\Http;
use App\Exceptions\ServerErrorException;

/**
 * Toss Payments Gateway Adapter
 *
 * 토스페이먼츠 API 연동 (API 스펙 기반 구현)
 * API 문서: https://docs.tosspayments.com/reference
 *
 * 주의: 실제 API 호출이 아닌 구조 예시용
 */
class TossPaymentAdapter implements PaymentGatewayInterface
{
    private string $secretKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config("pg.toss.secret_key", "");
        $this->baseUrl = config("pg.toss.base_url", "https://mock-api.tosspayments.example.com");
    }

    /**
     * 결제 승인 요청
     *
     * @param array $paymentData
     * @return array
     * @throws ServerErrorException
     */
    public function approve(array $paymentData): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, "")
                ->post("{$this->baseUrl}/v1/payments/confirm", [
                    "paymentKey" => $paymentData["payment_key"] ?? "",
                    "orderId" => $paymentData["order_id"],
                    "amount" => $paymentData["amount"],
                ]);

            if (!$response->successful()) {
                $error = $response->json();
                return [
                    "pg_transaction_id" => null,
                    "status" => "failed",
                    "approved_at" => null,
                    "error_code" => $error["code"] ?? "UNKNOWN_ERROR",
                    "error_message" => $error["message"] ?? "알 수 없는 오류가 발생했습니다",
                ];
            }

            $data = $response->json();

            return [
                "pg_transaction_id" => $data["paymentKey"],
                "status" => "approved",
                "approved_at" => $data["approvedAt"],
                "card_masked" => $data["card"]["number"] ?? null,
                "card_issuer_code" => $data["card"]["issuerCode"] ?? null,
            ];

        } catch (\Exception $e) {
            throw new ServerErrorException(
                "PG 승인 요청 중 오류가 발생했습니다",
                context: ["error" => $e->getMessage(), "pg" => "TOSS"]
            );
        }
    }

    /**
     * 결제 취소
     *
     * @param string $pgTransactionId
     * @param int $amount
     * @param string|null $reason
     * @return array
     * @throws ServerErrorException
     */
    public function cancel(string $pgTransactionId, int $amount, ?string $reason = null): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, "")
                ->post("{$this->baseUrl}/v1/payments/{$pgTransactionId}/cancel", [
                    "cancelReason" => $reason ?? "고객 요청",
                    "cancelAmount" => $amount,
                ]);

            if (!$response->successful()) {
                $error = $response->json();
                throw new ServerErrorException(
                    "PG 취소 요청 실패",
                    context: ["error" => $error["message"] ?? "Unknown error"]
                );
            }

            $data = $response->json();

            return [
                "pg_transaction_id" => $data["paymentKey"],
                "status" => "cancelled",
                "cancelled_at" => $data["cancels"][0]["canceledAt"] ?? date("Y-m-d H:i:s"),
            ];

        } catch (\Exception $e) {
            throw new ServerErrorException(
                "PG 취소 요청 중 오류가 발생했습니다",
                context: ["error" => $e->getMessage(), "pg" => "TOSS"]
            );
        }
    }

    /**
     * 결제 환불
     *
     * @param string $pgTransactionId
     * @param int $amount
     * @param string|null $reason
     * @return array
     * @throws ServerErrorException
     */
    public function refund(string $pgTransactionId, int $amount, ?string $reason = null): array
    {
        // 토스페이먼츠는 취소와 환불이 동일한 API 사용
        $result = $this->cancel($pgTransactionId, $amount, $reason);
        $result["status"] = "refunded";
        $result["refunded_at"] = $result["cancelled_at"];
        unset($result["cancelled_at"]);

        return $result;
    }
}
