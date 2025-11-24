<?php

namespace App\Services\Coupon;

use App\Entities\CouponEntity;
use App\Repositories\CouponRepository;
use App\Repositories\PromotionRepository;
use App\Services\BaseService;
use App\Validators\CouponValidator;
use App\Validators\PromotionValidator;

/**
 * 쿠폰 발급 Service
 */
class IssueCouponService extends BaseService
{
    public function __construct(
        private readonly CouponRepository $couponRepository,
        private readonly CouponValidator $couponValidator,
        private readonly PromotionRepository $promotionRepository,
        private readonly PromotionValidator $promotionValidator
    ) {
        parent::__construct();
    }

    /**
     * @param array $data
     * @return CouponEntity
     */
    protected function execute(...$args): CouponEntity
    {
        $data = $args[0];

        // 프로모션 존재 여부 확인
        $this->promotionValidator->validatePromotionExists($data["promotion_code"]);

        // 프로모션 활성화 상태 확인
        $promotion = $this->promotionRepository->findByCode($data["promotion_code"]);
        $this->promotionValidator->validatePromotionActive($promotion->getPromotionCode());

        // 쿠폰 코드 중복 확인
        $this->couponValidator->validateCouponCodeNotExists($data["coupon_code"]);

        // 쿠폰 발급
        $couponCode = $this->couponRepository->create([
            "coupon_code" => $data["coupon_code"],
            "promotion_code" => $data["promotion_code"],
            "issued_count" => 0,
            "max_usage" => $data["max_usage"] ?? 1,
            "expires_at" => $data["expires_at"],
            "status" => "issued"
        ]);

        return $this->couponRepository->findByCode($couponCode);
    }
}
