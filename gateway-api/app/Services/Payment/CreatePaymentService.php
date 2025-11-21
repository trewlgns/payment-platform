<?php

namespace App\Services\Payment;

use App\Adapters\PaymentGatewayInterface;
use App\Repositories\PaymentRepository;
use App\Repositories\OrderRepository;
use App\Services\BaseService;
use App\Validators\PaymentValidator;
use App\Validators\OrderValidator;
use App\Exceptions\ServerErrorException;

/**
 * 결제 생성 Service
 *
 * POST /api/payments
 */
class CreatePaymentService extends BaseService
{
    private PaymentRepository $paymentRepo;
    private OrderRepository $orderRepo;
    private PaymentValidator $paymentValidator;
    private OrderValidator $orderValidator;
    private PaymentGatewayInterface $gateway;

    public function __construct(PaymentGatewayInterface $gateway)
    {
        parent::__construct();
        $this->paymentRepo = new PaymentRepository($this->db);
        $this->orderRepo = new OrderRepository($this->db);
        $this->paymentValidator = new PaymentValidator($this->db);
        $this->orderValidator = new OrderValidator($this->db);
        $this->gateway = $gateway;
    }

    /**
     * 결제 생성 및 승인 처리
     *
     * @param array $data
     * @return array
     * @throws ServerErrorException
     */
    protected function execute(...$args): array
    {
        $data = $args[0];

        // 1. 주문 존재 여부 확인
        $order = $this->orderValidator->validateOrderExists($data["order_id"]);

        // 2. 멱등성 키 중복 확인
        $this->paymentValidator->validateIdempotencyKeyNotExists($data["idempotency_key"]);

        // 3. 결제 레코드 생성 (status = "pending")
        $paymentId = $this->paymentRepo->create([
            "order_id" => $data["order_id"],
            "pg_provider_code" => $data["pg_provider_code"],
            "amount" => $data["amount"],
            "status" => "pending",
            "payment_method" => $data["payment_method"],
            "idempotency_key" => $data["idempotency_key"]
        ]);

        // 4. PG 승인 요청
        $pgResponse = $this->gateway->approve([
            "order_id" => $data["order_id"],
            "amount" => $data["amount"],
            "payment_method" => $data["payment_method"],
            "card_number" => $data["card_number"] ?? null,
            "card_expiry_month" => $data["card_expiry_month"] ?? null,
            "card_expiry_year" => $data["card_expiry_year"] ?? null,
            "card_cvv" => $data["card_cvv"] ?? null,
            "payment_key" => $data["payment_key"] ?? null, // Toss용
        ]);

        // 5. PG 승인 결과 처리
        if ($pgResponse["status"] === "failed") {
            // 실패: 결제 레코드를 failed로 업데이트
            $this->paymentRepo->update($paymentId, [
                "status" => "failed",
                "pg_error_code" => $pgResponse["error_code"] ?? null,
                "pg_error_message" => $pgResponse["error_message"] ?? null,
            ]);

            // 실제 에러 메시지: PG 승인 실패 (ServerErrorException)
            throw new ServerErrorException(
                "payment.pg_approval_failed", // lang/ko/messages.php 키
                context: [
                    "payment_id" => $paymentId,
                    "error_code" => $pgResponse["error_code"] ?? "UNKNOWN",
                    "error_message" => $pgResponse["error_message"] ?? "알 수 없는 오류"
                ]
            );
        }

        // 6. 승인 성공: 결제 레코드 업데이트
        $this->paymentRepo->update($paymentId, [
            "status" => "approved",
            "pg_transaction_id" => $pgResponse["pg_transaction_id"],
            "paid_at" => $pgResponse["approved_at"],
            "card_masked" => $pgResponse["card_masked"] ?? null,
            "card_issuer_code" => $pgResponse["card_issuer_code"] ?? null,
        ]);

        // 7. 최종 결제 정보 조회 및 반환
        $payment = $this->paymentRepo->findById($paymentId);

        return [
            "payment_id" => $payment->paymentId,
            "order_id" => $payment->orderId,
            "pg_provider_code" => $payment->pgProviderCode,
            "pg_transaction_id" => $payment->pgTransactionId,
            "amount" => $payment->amount,
            "status" => $payment->status,
            "payment_method" => $payment->paymentMethod,
            "card_masked" => $payment->cardMasked,
            "card_issuer_code" => $payment->cardIssuerCode,
            "idempotency_key" => $payment->idempotencyKey,
            "paid_at" => $payment->paidAt,
            "created_at" => $payment->createdAt,
            "updated_at" => $payment->updatedAt
        ];
    }
}
