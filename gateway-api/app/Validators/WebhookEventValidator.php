<?php

namespace App\Validators;

use App\Database\DB;
use App\Entities\WebhookEventEntity;
use App\Exceptions\ConflictException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Repositories\WebhookEventRepository;

/**
 * WebhookEvent 도메인 Validator
 *
 * 웹훅 이벤트 존재 여부, 상태, 처리 가능 여부 등 Semantic 검증을 담당한다.
 */
class WebhookEventValidator extends BaseValidator
{
    private WebhookEventRepository $webhookEventRepo;

    public function __construct(DB &$db)
    {
        parent::__construct($db);
        $this->webhookEventRepo = new WebhookEventRepository($db);
    }

    // ========================================
    // 존재성 검증
    // ========================================

    /**
     * 웹훅 이벤트 존재 여부 확인 (존재하지 않으면 예외)
     *
     * @param int $webhookEventId
     * @return WebhookEventEntity
     * @throws NotFoundException
     */
    public function validateWebhookEventExists(int $webhookEventId): WebhookEventEntity
    {
        $webhookEvent = $this->webhookEventRepo->findById($webhookEventId);

        if (!$webhookEvent) {
            throw new NotFoundException(__("messages.webhook_event_not_found"));
        }

        return $webhookEvent;
    }

    // ========================================
    // 상태 검증
    // ========================================

    /**
     * 웹훅 이벤트가 수신 상태인지 확인
     *
     * @param WebhookEventEntity $webhookEvent
     * @return void
     * @throws ForbiddenException
     */
    public function validateWebhookEventReceived(WebhookEventEntity $webhookEvent): void
    {
        if (!$webhookEvent->isReceived()) {
            throw new ForbiddenException(__("messages.webhook_event_not_received"));
        }
    }

    /**
     * 웹훅 이벤트가 처리 가능한 상태인지 확인 (received만 가능)
     *
     * @param WebhookEventEntity $webhookEvent
     * @return void
     * @throws ForbiddenException
     */
    public function validateWebhookEventProcessable(WebhookEventEntity $webhookEvent): void
    {
        if (!$webhookEvent->isProcessable()) {
            throw new ForbiddenException(__("messages.webhook_event_not_processable"));
        }
    }

    /**
     * 웹훅 이벤트가 재처리 가능한 상태인지 확인 (failed만 가능)
     *
     * @param WebhookEventEntity $webhookEvent
     * @return void
     * @throws ForbiddenException
     */
    public function validateWebhookEventRetryable(WebhookEventEntity $webhookEvent): void
    {
        if (!$webhookEvent->isRetryable()) {
            throw new ForbiddenException(__("messages.webhook_event_not_retryable"));
        }
    }

    /**
     * 웹훅 이벤트가 이미 처리되었는지 확인 (중복 처리 방지)
     *
     * @param WebhookEventEntity $webhookEvent
     * @return void
     * @throws ConflictException
     */
    public function validateWebhookEventNotProcessed(WebhookEventEntity $webhookEvent): void
    {
        if ($webhookEvent->isProcessed()) {
            throw new ConflictException(__("messages.webhook_event_already_processed"));
        }
    }

    // ========================================
    // 복합 검증
    // ========================================

    /**
     * 웹훅 이벤트 처리 가능 여부 복합 검증 (존재 + 처리 가능 상태)
     *
     * @param int $webhookEventId
     * @return WebhookEventEntity
     * @throws NotFoundException|ForbiddenException
     */
    public function validateProcessableWebhookEvent(int $webhookEventId): WebhookEventEntity
    {
        $webhookEvent = $this->validateWebhookEventExists($webhookEventId);
        $this->validateWebhookEventProcessable($webhookEvent);

        return $webhookEvent;
    }

    /**
     * 웹훅 이벤트 재처리 가능 여부 복합 검증 (존재 + 재처리 가능 상태)
     *
     * @param int $webhookEventId
     * @return WebhookEventEntity
     * @throws NotFoundException|ForbiddenException
     */
    public function validateRetryableWebhookEvent(int $webhookEventId): WebhookEventEntity
    {
        $webhookEvent = $this->validateWebhookEventExists($webhookEventId);
        $this->validateWebhookEventRetryable($webhookEvent);

        return $webhookEvent;
    }
}
