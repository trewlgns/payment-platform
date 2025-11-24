<?php

namespace App\Services\Coupon;

use App\Entities\CouponEntity;
use App\Repositories\CouponRepository;
use App\Services\BaseService;
use App\Validators\CouponValidator;

/**
 * 쿠폰 사용 Service
 */
class UseCouponService extends BaseService
{
    public function __construct(
        private readonly CouponRepository $couponRepository,
        private readonly CouponValidator $couponValidator
    ) {
        parent::__construct();
    }

    /**
     * @param array $data
     * @return array
     */
    protected function execute(...$args): array
    {
        $data = $args[0];

        $couponCode = $data["coupon_code"];

        // 쿠폰 사용 가능 여부 확인
        $this->couponValidator->validateUsableCoupon($couponCode);

        // 쿠폰 사용 기록
        $redemptionId = $this->couponRepository->redeem([
            "coupon_code" => $couponCode,
            "customer_email" => $data["customer_email"],
            "order_id" => $data["order_id"]
        ]);

        // 쿠폰 발급 카운트 증가
        $this->couponRepository->incrementIssuedCount($couponCode);

        // 쿠폰 정보 조회
        $coupon = $this->couponRepository->findByCode($couponCode);

        // 최대 사용 횟수 도달 시 상태 변경
        if ($coupon->hasReachedMaxUsage()) {
            $this->couponRepository->updateStatus($couponCode, "used");
        }

        return [
            "redemption_id" => $redemptionId,
            "coupon" => $coupon
        ];
    }
}
