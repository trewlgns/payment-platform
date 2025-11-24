<?php

namespace App\Validators;

use App\Database\DB;
use App\Entities\CouponEntity;
use App\Exceptions\ConflictException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Repositories\CouponRepository;

/**
 * Coupon 도메인 Validator
 *
 * 쿠폰 존재 여부, 상태, 만료, 사용 가능 여부 등 Semantic 검증을 담당한다.
 */
class CouponValidator extends BaseValidator
{
    private CouponRepository $couponRepo;

    public function __construct(DB &$db)
    {
        parent::__construct($db);
        $this->couponRepo = new CouponRepository($db);
    }

    // ========================================
    // 존재성 검증
    // ========================================

    /**
     * 쿠폰 존재 여부 확인 (존재하지 않으면 예외)
     *
     * @param string $couponCode
     * @return CouponEntity
     * @throws NotFoundException
     */
    public function validateCouponExists(string $couponCode): CouponEntity
    {
        $coupon = $this->couponRepo->findByCode($couponCode);

        if (!$coupon) {
            throw new NotFoundException(__("messages.coupon_not_found"));
        }

        return $coupon;
    }

    /**
     * 쿠폰 코드 중복 여부 확인
     *
     * @param string $couponCode
     * @return void
     * @throws ConflictException
     */
    public function validateCouponCodeNotExists(string $couponCode): void
    {
        if ($this->couponRepo->exists($couponCode)) {
            throw new ConflictException(__("messages.coupon_code_exists"));
        }
    }

    // ========================================
    // 상태 검증
    // ========================================

    /**
     * 쿠폰이 발급 상태인지 확인
     *
     * @param CouponEntity $coupon
     * @return void
     * @throws ForbiddenException
     */
    public function validateCouponIssued(CouponEntity $coupon): void
    {
        if (!$coupon->isIssued()) {
            throw new ForbiddenException(__("messages.coupon_not_issued"));
        }
    }

    /**
     * 쿠폰이 사용되지 않았는지 확인
     *
     * @param CouponEntity $coupon
     * @return void
     * @throws ForbiddenException
     */
    public function validateCouponNotUsed(CouponEntity $coupon): void
    {
        if ($coupon->isUsed()) {
            throw new ForbiddenException(__("messages.coupon_already_used"));
        }
    }

    /**
     * 쿠폰이 만료되지 않았는지 확인 (시각 기준)
     *
     * @param CouponEntity $coupon
     * @return void
     * @throws ForbiddenException
     */
    public function validateCouponNotExpired(CouponEntity $coupon): void
    {
        if ($coupon->isExpiredByTime()) {
            throw new ForbiddenException(__("messages.coupon_expired"));
        }
    }

    /**
     * 쿠폰이 취소되지 않았는지 확인
     *
     * @param CouponEntity $coupon
     * @return void
     * @throws ForbiddenException
     */
    public function validateCouponNotCancelled(CouponEntity $coupon): void
    {
        if ($coupon->isCancelled()) {
            throw new ForbiddenException(__("messages.coupon_cancelled"));
        }
    }

    // ========================================
    // 사용 가능 여부 검증
    // ========================================

    /**
     * 쿠폰이 사용 가능한지 확인 (발급 상태 + 만료 안 됨)
     *
     * @param CouponEntity $coupon
     * @return void
     * @throws ForbiddenException
     */
    public function validateCouponUsable(CouponEntity $coupon): void
    {
        if (!$coupon->isUsable()) {
            throw new ForbiddenException(__("messages.coupon_not_usable"));
        }
    }

    /**
     * 쿠폰이 추가 사용 가능한지 확인 (max_usage)
     *
     * @param CouponEntity $coupon
     * @return void
     * @throws ForbiddenException
     */
    public function validateCouponCanBeUsedAgain(CouponEntity $coupon): void
    {
        if ($coupon->hasReachedMaxUsage()) {
            throw new ForbiddenException(__("messages.coupon_max_usage_reached"));
        }
    }

    // ========================================
    // 복합 검증
    // ========================================

    /**
     * 사용 가능한 쿠폰인지 확인 (존재 + 발급 + 만료 안 됨 + 취소 안 됨 + 사용 가능 횟수)
     *
     * @param string $couponCode
     * @return CouponEntity
     */
    public function validateUsableCoupon(string $couponCode): CouponEntity
    {
        $coupon = $this->validateCouponExists($couponCode);
        $this->validateCouponIssued($coupon);
        $this->validateCouponNotUsed($coupon);
        $this->validateCouponNotExpired($coupon);
        $this->validateCouponNotCancelled($coupon);

        return $coupon;
    }
}
