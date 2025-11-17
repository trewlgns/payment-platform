<?php

namespace App\Validators;

use App\Database\DB;
use App\Entities\OrderEntity;
use App\Exceptions\ConflictException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Repositories\OrderRepository;

/**
 * Order 도메인 Validator
 *
 * 주문 존재 여부, 상태, 취소/환불 가능 여부 등 Semantic 검증을 담당한다.
 */
class OrderValidator extends BaseValidator
{
    private OrderRepository $orderRepo;

    public function __construct(DB &$db)
    {
        parent::__construct($db);
        $this->orderRepo = new OrderRepository($db);
    }

    // ========================================
    // 존재성 검증
    // ========================================

    /**
     * 주문 존재 여부 확인 (존재하지 않으면 예외)
     *
     * @param int $orderId
     * @return OrderEntity
     * @throws NotFoundException
     */
    public function validateOrderExists(int $orderId): OrderEntity
    {
        $order = $this->orderRepo->findById($orderId);

        if (!$order) {
            throw new NotFoundException(__("messages.order_not_found"));
        }

        return $order;
    }

    // ========================================
    // 상태 검증
    // ========================================

    /**
     * 주문이 취소 가능한 상태인지 확인
     *
     * @param OrderEntity $order
     * @return void
     * @throws ForbiddenException
     */
    public function validateOrderCancellable(OrderEntity $order): void
    {
        if (!$order->isCancellable()) {
            throw new ForbiddenException(__("messages.order_not_cancellable"));
        }
    }

    /**
     * 주문이 환불 가능한 상태인지 확인
     *
     * @param OrderEntity $order
     * @return void
     * @throws ForbiddenException
     */
    public function validateOrderRefundable(OrderEntity $order): void
    {
        if (!$order->isRefundable()) {
            throw new ForbiddenException(__("messages.order_not_refundable"));
        }
    }

    /**
     * 주문이 이미 취소되지 않았는지 확인
     *
     * @param OrderEntity $order
     * @return void
     * @throws ConflictException
     */
    public function validateOrderNotCancelled(OrderEntity $order): void
    {
        if ($order->isCancelled()) {
            throw new ConflictException(__("messages.order_already_cancelled"));
        }
    }

    /**
     * 주문이 이미 환불되지 않았는지 확인
     *
     * @param OrderEntity $order
     * @return void
     * @throws ConflictException
     */
    public function validateOrderNotRefunded(OrderEntity $order): void
    {
        if ($order->isRefunded()) {
            throw new ConflictException(__("messages.order_already_refunded"));
        }
    }

    /**
     * 주문이 결제 완료 상태인지 확인
     *
     * @param OrderEntity $order
     * @return void
     * @throws ForbiddenException
     */
    public function validateOrderPaid(OrderEntity $order): void
    {
        if (!$order->isPaid()) {
            throw new ForbiddenException(__("messages.order_not_paid"));
        }
    }

    // ========================================
    // 복합 검증
    // ========================================

    /**
     * 취소 가능한 주문인지 확인 (존재 + 취소 가능 상태 + 미취소)
     *
     * @param int $orderId
     * @return OrderEntity
     */
    public function validateCancellableOrder(int $orderId): OrderEntity
    {
        $order = $this->validateOrderExists($orderId);
        $this->validateOrderNotCancelled($order);
        $this->validateOrderCancellable($order);

        return $order;
    }

    /**
     * 환불 가능한 주문인지 확인 (존재 + 환불 가능 상태 + 미환불)
     *
     * @param int $orderId
     * @return OrderEntity
     */
    public function validateRefundableOrder(int $orderId): OrderEntity
    {
        $order = $this->validateOrderExists($orderId);
        $this->validateOrderNotRefunded($order);
        $this->validateOrderRefundable($order);

        return $order;
    }
}
