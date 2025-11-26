<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessage;
use App\Services\Webhook\ProcessWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Webhook 처리 Controller
 *
 * PG에서 보내는 웹훅을 수신하여 처리한다.
 */
class WebhookController extends BaseController
{
    /**
     * Toss Payments 웹훅 수신
     *
     * POST /api/webhooks/toss
     */
    public function handleToss(Request $request, ProcessWebhookService $service): JsonResponse
    {
        $payload = $request->all();

        $result = $service->handle([
            "pg_provider_code" => "toss",
            "event_type" => $this->mapTossEventType($payload),
            "payload" => $payload
        ]);

        return $this->success($result, ResponseMessage::WEBHOOK_PROCESSED);
    }

    /**
     * Kakao Pay 웹훅 수신
     *
     * POST /api/webhooks/kakao
     */
    public function handleKakao(Request $request, ProcessWebhookService $service): JsonResponse
    {
        $payload = $request->all();

        $result = $service->handle([
            "pg_provider_code" => "kakao",
            "event_type" => $this->mapKakaoEventType($payload),
            "payload" => $payload
        ]);

        return $this->success($result, ResponseMessage::WEBHOOK_PROCESSED);
    }

    /**
     * Mock PG 웹훅 수신 (테스트용)
     *
     * POST /api/webhooks/mock
     */
    public function handleMock(Request $request, ProcessWebhookService $service): JsonResponse
    {
        $payload = $request->all();

        $result = $service->handle([
            "pg_provider_code" => "mock",
            "event_type" => $payload["event_type"] ?? "payment.approved",
            "payload" => $payload
        ]);

        return $this->success($result, ResponseMessage::WEBHOOK_PROCESSED);
    }

    /**
     * Toss Payments 이벤트 타입 매핑
     *
     * @param array $payload
     * @return string
     */
    private function mapTossEventType(array $payload): string
    {
        $status = $payload["status"] ?? null;

        return match ($status) {
            "DONE" => "payment.approved",
            "CANCELED" => "payment.cancelled",
            "REFUNDED" => "payment.refunded",
            "FAILED" => "payment.failed",
            default => "payment.approved"
        };
    }

    /**
     * Kakao Pay 이벤트 타입 매핑
     *
     * @param array $payload
     * @return string
     */
    private function mapKakaoEventType(array $payload): string
    {
        $type = $payload["payment_action_type"] ?? null;

        return match ($type) {
            "PAYMENT" => "payment.approved",
            "CANCEL" => "payment.cancelled",
            "REFUND" => "payment.refunded",
            default => "payment.approved"
        };
    }
}
