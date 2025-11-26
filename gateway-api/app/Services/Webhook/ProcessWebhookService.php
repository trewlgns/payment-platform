<?php

namespace App\Services\Webhook;

use App\Repositories\WebhookEventRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\OrderRepository;
use App\Services\BaseService;
use App\Validators\PaymentValidator;
use App\Exceptions\ServerErrorException;
use App\Exceptions\NotFoundException;

/**
 * 웹훅 이벤트 처리 Service
 *
 * POST /api/webhooks/{provider}
 */
class ProcessWebhookService extends BaseService
{
    private WebhookEventRepository $webhookEventRepo;
    private PaymentRepository $paymentRepo;
    private OrderRepository $orderRepo;
    private PaymentValidator $paymentValidator;

    public function __construct()
    {
        parent::__construct();
        $this->webhookEventRepo = new WebhookEventRepository($this->db);
        $this->paymentRepo = new PaymentRepository($this->db);
        $this->orderRepo = new OrderRepository($this->db);
        $this->paymentValidator = new PaymentValidator($this->db);
    }

    /**
     * 웹훅 이벤트 처리
     *
     * @param array $data [pg_provider_code, event_type, payload]
     * @return array
     * @throws ServerErrorException
     */
    protected function execute(...$args): array
    {
        $data = $args[0];

        // 1. 웹훅 이벤트 생성 (로그 저장)
        $webhookEventId = $this->webhookEventRepo->create([
            "pg_provider_code" => $data["pg_provider_code"],
            "event_type" => $data["event_type"],
            "payload" => $data["payload"],
            "status" => "received"
        ]);

        try {
            // 2. 웹훅 이벤트를 processing 상태로 변경
            $this->webhookEventRepo->updateStatus($webhookEventId, "processing");

            // 3. 이벤트 타입별 처리
            $this->processEventByType($data);

            // 4. 웹훅 이벤트를 processed 상태로 변경
            $this->webhookEventRepo->markAsProcessed($webhookEventId);

            return [
                "webhook_event_id" => $webhookEventId,
                "status" => "processed",
                "message" => "Webhook processed successfully"
            ];
        } catch (\Exception $e) {
            // 5. 처리 실패 시 failed 상태로 변경
            $this->webhookEventRepo->markAsFailed($webhookEventId);

            throw new ServerErrorException(
                // 실제 응답: "Failed to process webhook event"
                "Failed to process webhook event",
                __("messages.webhook_processing_failed"),
                ["error" => $e->getMessage()]
            );
        }
    }

    /**
     * 이벤트 타입별 처리 로직
     *
     * @param array $data
     * @return void
     */
    private function processEventByType(array $data): void
    {
        $eventType = $data["event_type"];
        $payload = $data["payload"];

        switch ($eventType) {
            case "payment.approved":
                $this->handlePaymentApproved($payload);
                break;

            case "payment.cancelled":
                $this->handlePaymentCancelled($payload);
                break;

            case "payment.refunded":
                $this->handlePaymentRefunded($payload);
                break;

            case "payment.failed":
                $this->handlePaymentFailed($payload);
                break;

            default:
                throw new ServerErrorException(
                    // 실제 응답: "Unknown event type: {$eventType}"
                    "Unknown event type: {$eventType}",
                    __("messages.webhook_unknown_event_type"),
                    ["event_type" => $eventType]
                );
        }
    }

    /**
     * payment.approved 이벤트 처리
     * PG에서 결제 승인 완료를 알려올 때 (가상계좌 입금 등)
     *
     * @param array $payload
     * @return void
     */
    private function handlePaymentApproved(array $payload): void
    {
        $pgTransactionId = $payload["pg_transaction_id"] ?? $payload["transactionId"] ?? null;

        if (!$pgTransactionId) {
            throw new ServerErrorException(
                // 실제 응답: "Missing pg_transaction_id in webhook payload"
                "Missing pg_transaction_id in webhook payload",
                __("messages.webhook_missing_pg_transaction_id")
            );
        }

        // PG 거래 ID로 결제 조회
        $payment = $this->paymentRepo->findByPgTransactionId($pgTransactionId);

        if (!$payment) {
            throw new NotFoundException(__("messages.payment_not_found"));
        }

        // 이미 승인된 경우 중복 처리 방지
        if ($payment->isApproved()) {
            return;
        }

        // 결제 상태를 approved로 업데이트
        $this->paymentRepo->update($payment->paymentId, [
            "status" => "approved",
            "paid_at" => $payload["paid_at"] ?? date("Y-m-d H:i:s")
        ]);

        // 주문 상태를 paid로 업데이트
        $this->orderRepo->update($payment->orderId, [
            "status" => "paid"
        ]);
    }

    /**
     * payment.cancelled 이벤트 처리
     * PG에서 결제 취소를 알려올 때 (고객이 카드사에 직접 취소 요청 등)
     *
     * @param array $payload
     * @return void
     */
    private function handlePaymentCancelled(array $payload): void
    {
        $pgTransactionId = $payload["pg_transaction_id"] ?? $payload["transactionId"] ?? null;

        if (!$pgTransactionId) {
            throw new ServerErrorException(
                // 실제 응답: "Missing pg_transaction_id in webhook payload"
                "Missing pg_transaction_id in webhook payload",
                __("messages.webhook_missing_pg_transaction_id")
            );
        }

        // PG 거래 ID로 결제 조회
        $payment = $this->paymentRepo->findByPgTransactionId($pgTransactionId);

        if (!$payment) {
            throw new NotFoundException(__("messages.payment_not_found"));
        }

        // 이미 취소된 경우 중복 처리 방지
        if ($payment->isCancelled()) {
            return;
        }

        // 결제 상태를 cancelled로 업데이트
        $this->paymentRepo->update($payment->paymentId, [
            "status" => "cancelled"
        ]);

        // 주문 상태를 cancelled로 업데이트
        $this->orderRepo->update($payment->orderId, [
            "status" => "cancelled"
        ]);
    }

    /**
     * payment.refunded 이벤트 처리
     * PG에서 환불 완료를 알려올 때
     *
     * @param array $payload
     * @return void
     */
    private function handlePaymentRefunded(array $payload): void
    {
        $pgTransactionId = $payload["pg_transaction_id"] ?? $payload["transactionId"] ?? null;

        if (!$pgTransactionId) {
            throw new ServerErrorException(
                // 실제 응답: "Missing pg_transaction_id in webhook payload"
                "Missing pg_transaction_id in webhook payload",
                __("messages.webhook_missing_pg_transaction_id")
            );
        }

        // PG 거래 ID로 결제 조회
        $payment = $this->paymentRepo->findByPgTransactionId($pgTransactionId);

        if (!$payment) {
            throw new NotFoundException(__("messages.payment_not_found"));
        }

        // 이미 환불된 경우 중복 처리 방지
        if ($payment->isRefunded()) {
            return;
        }

        // 결제 상태를 refunded로 업데이트
        $this->paymentRepo->update($payment->paymentId, [
            "status" => "refunded"
        ]);

        // 주문 상태를 refunded로 업데이트
        $this->orderRepo->update($payment->orderId, [
            "status" => "refunded"
        ]);
    }

    /**
     * payment.failed 이벤트 처리
     * PG에서 결제 실패를 알려올 때 (잔액 부족, 카드 한도 초과 등)
     *
     * @param array $payload
     * @return void
     */
    private function handlePaymentFailed(array $payload): void
    {
        $pgTransactionId = $payload["pg_transaction_id"] ?? $payload["transactionId"] ?? null;

        if (!$pgTransactionId) {
            throw new ServerErrorException(
                // 실제 응답: "Missing pg_transaction_id in webhook payload"
                "Missing pg_transaction_id in webhook payload",
                __("messages.webhook_missing_pg_transaction_id")
            );
        }

        // PG 거래 ID로 결제 조회
        $payment = $this->paymentRepo->findByPgTransactionId($pgTransactionId);

        if (!$payment) {
            throw new NotFoundException(__("messages.payment_not_found"));
        }

        // 이미 실패 처리된 경우 중복 처리 방지
        if ($payment->isFailed()) {
            return;
        }

        // 결제 상태를 failed로 업데이트
        $this->paymentRepo->update($payment->paymentId, [
            "status" => "failed",
            "pg_error_code" => $payload["error_code"] ?? null,
            "pg_error_message" => $payload["error_message"] ?? null
        ]);

        // 주문 상태는 pending 유지 (재시도 가능)
    }
}
