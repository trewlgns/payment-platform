<?php

namespace App\Services\Coupon;

use App\Entities\CouponEntity;
use App\Repositories\CouponRepository;
use App\Services\BaseService;
use App\Validators\CouponValidator;

/**
 * 쿠폰 만료 Service
 */
class ExpireCouponService extends BaseService
{
    public function __construct(
        private readonly CouponRepository $couponRepository,
        private readonly CouponValidator $couponValidator
    ) {
        parent::__construct();
    }

    /**
     * @param string $couponCode
     * @return CouponEntity
     */
    protected function execute(...$args): CouponEntity
    {
        $couponCode = $args[0];

        $this->couponValidator->validateCouponExists($couponCode);
        $this->couponValidator->validateCouponNotUsed($couponCode);

        $this->couponRepository->updateStatus($couponCode, "expired");

        return $this->couponRepository->findByCode($couponCode);
    }
}
