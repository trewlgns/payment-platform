<?php

namespace App\Adapters;

use Illuminate\Support\Facades\Http;
use App\Exceptions\ServerErrorException;

/**
 * Kakao Pay Gateway Adapter
 *
 * 카카오페이 API 연동 (API 스펙 기반 구현)
 * API 문서: https://developers.kakao.com/docs/latest/ko/kakaopay/common
 *
 * 주의: 실제 API 호출이 아닌 구조 예시용
 */
class KakaoPayAdapter implements PaymentGatewayInterface
{
    private string $adminKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->adminKey = config("pg.kakao.admin_key", "");
        $this->baseUrl = config("pg.kakao.base_url", "https://mock-api.kakaopay.example.com");
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
            // 카카오페이는 사전에 ready API로 받은 tid가 필요
            $response = Http::withHeaders([
                "Authorization" => "KakaoAK " . $this->adminKey,
                "Content-Type" => "application/x-www-form-urlencoded;charset=utf-8"
            ])->asForm()->post("{$this->baseUrl}/v1/payment/approve", [
                "cid" => config("pg.kakao.cid", "TC0ONETIME"), // 가맹점 코드
                "tid" => $paymentData["tid"] ?? "", // 결제 고유번호 (ready에서 받음)
                "partner_order_id" => $paymentData["order_id"],
                "partner_user_id" => $paymentData["user_id"] ?? "guest",
                "pg_token" => $paymentData["pg_token"] ?? "", // 사용자 인증 토큰
            ]);

            if (!$response->successful()) {
                $error = $response->json();
                return [
                    "pg_transaction_id" => null,
                    "status" => "failed",
                    "approved_at" => null,
                    "error_code" => $error["error_code"] ?? "KAKAO_ERROR",
                    "error_message" => $error["error_message"] ?? "카카오페이 승인 실패",
                ];
            }

            $data = $response->json();

            return [
                "pg_transaction_id" => $data["tid"],
                "status" => "approved",
                "approved_at" => $data["approved_at"],
                "card_masked" => $data["card_info"]["card_bin"] ?? null, // 카드 BIN
                "card_issuer_code" => $data["card_info"]["issuer_corp"] ?? null, // 카드사
            ];

        } catch (\Exception $e) {
            throw new ServerErrorException(
                "PG 승인 요청 중 오류가 발생했습니다",
                context: ["error" => $e->getMessage(), "pg" => "KAKAO"]
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
            $response = Http::withHeaders([
                "Authorization" => "KakaoAK " . $this->adminKey,
                "Content-Type" => "application/x-www-form-urlencoded;charset=utf-8"
            ])->asForm()->post("{$this->baseUrl}/v1/payment/cancel", [
                "cid" => config("pg.kakao.cid", "TC0ONETIME"),
                "tid" => $pgTransactionId,
                "cancel_amount" => $amount,
                "cancel_tax_free_amount" => 0,
            ]);

            if (!$response->successful()) {
                $error = $response->json();
                throw new ServerErrorException(
                    "PG 취소 요청 실패",
                    context: ["error" => $error["error_message"] ?? "Unknown error", "pg" => "KAKAO"]
                );
            }

            $data = $response->json();

            return [
                "pg_transaction_id" => $data["tid"],
                "status" => "cancelled",
                "cancelled_at" => $data["canceled_at"] ?? date("Y-m-d H:i:s"),
            ];

        } catch (\Exception $e) {
            throw new ServerErrorException(
                "PG 취소 요청 중 오류가 발생했습니다",
                context: ["error" => $e->getMessage(), "pg" => "KAKAO"]
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
        // 카카오페이는 취소와 환불이 동일한 API 사용
        $result = $this->cancel($pgTransactionId, $amount, $reason);
        $result["status"] = "refunded";
        $result["refunded_at"] = $result["cancelled_at"];
        unset($result["cancelled_at"]);

        return $result;
    }
}
