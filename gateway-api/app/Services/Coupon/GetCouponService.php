<?php

namespace App\Services\Coupon;

use App\Entities\CouponEntity;
use App\Repositories\CouponRepository;
use App\Services\BaseService;
use App\Validators\CouponValidator;

/**
 * 쿠폰 상세 조회 Service
 */
class GetCouponService extends BaseService
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

        return $this->couponRepository->findByCode($couponCode);
    }
}
