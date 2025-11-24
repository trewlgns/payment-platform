<?php

namespace App\Validators;

use App\Database\DB;
use App\Entities\PromotionEntity;
use App\Exceptions\ConflictException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Repositories\PromotionRepository;

/**
 * Promotion 도메인 Validator
 *
 * 프로모션 존재 여부, 상태, 중복 여부, 기간 등 Semantic 검증을 담당한다.
 */
class PromotionValidator extends BaseValidator
{
    private PromotionRepository $promotionRepo;

    public function __construct(DB &$db)
    {
        parent::__construct($db);
        $this->promotionRepo = new PromotionRepository($db);
    }

    // ========================================
    // 존재성 검증
    // ========================================

    /**
     * 프로모션 존재 여부 확인 (존재하지 않으면 예외)
     *
     * @param string $promotionCode
     * @return PromotionEntity
     * @throws NotFoundException
     */
    public function validatePromotionExists(string $promotionCode): PromotionEntity
    {
        $promotion = $this->promotionRepo->findByCode($promotionCode);

        if (!$promotion) {
            throw new NotFoundException(__("messages.promotion_not_found"));
        }

        return $promotion;
    }

    /**
     * 프로모션 코드 중복 여부 확인
     *
     * @param string $promotionCode
     * @return void
     * @throws ConflictException
     */
    public function validatePromotionCodeNotExists(string $promotionCode): void
    {
        if ($this->promotionRepo->exists($promotionCode)) {
            throw new ConflictException(__("messages.promotion_code_exists"));
        }
    }

    // ========================================
    // 상태 검증
    // ========================================

    /**
     * 프로모션이 활성 상태인지 확인
     *
     * @param PromotionEntity $promotion
     * @return void
     * @throws ForbiddenException
     */
    public function validatePromotionActive(PromotionEntity $promotion): void
    {
        if (!$promotion->isActive()) {
            throw new ForbiddenException(__("messages.promotion_inactive"));
        }
    }

    /**
     * 프로모션이 비활성 상태인지 확인
     *
     * @param PromotionEntity $promotion
     * @return void
     * @throws ForbiddenException
     */
    public function validatePromotionInactive(PromotionEntity $promotion): void
    {
        if (!$promotion->isInactive()) {
            throw new ForbiddenException(__("messages.promotion_already_active"));
        }
    }

    /**
     * 프로모션이 진행 중인지 확인
     *
     * @param PromotionEntity $promotion
     * @return void
     * @throws ForbiddenException
     */
    public function validatePromotionOngoing(PromotionEntity $promotion): void
    {
        if (!$promotion->isOngoing()) {
            throw new ForbiddenException(__("messages.promotion_not_ongoing"));
        }
    }

    /**
     * 프로모션이 만료되지 않았는지 확인
     *
     * @param PromotionEntity $promotion
     * @return void
     * @throws ForbiddenException
     */
    public function validatePromotionNotExpired(PromotionEntity $promotion): void
    {
        if ($promotion->isExpired()) {
            throw new ForbiddenException(__("messages.promotion_expired"));
        }
    }

    // ========================================
    // 기간 검증
    // ========================================

    /**
     * 프로모션 기간이 유효한지 확인 (시작일 < 종료일)
     *
     * @param string $startAt
     * @param string $endAt
     * @return void
     * @throws ForbiddenException
     */
    public function validatePromotionPeriod(string $startAt, string $endAt): void
    {
        if ($startAt >= $endAt) {
            throw new ForbiddenException(__("messages.promotion_invalid_period"));
        }
    }

    // ========================================
    // 복합 검증
    // ========================================

    /**
     * 사용 가능한 프로모션인지 확인 (존재 + 활성 + 진행 중 + 만료 안 됨)
     *
     * @param string $promotionCode
     * @return PromotionEntity
     */
    public function validateAvailablePromotion(string $promotionCode): PromotionEntity
    {
        $promotion = $this->validatePromotionExists($promotionCode);
        $this->validatePromotionActive($promotion);
        $this->validatePromotionOngoing($promotion);
        $this->validatePromotionNotExpired($promotion);

        return $promotion;
    }
}
