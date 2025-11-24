<?php

namespace App\Services\Coupon;

use App\Repositories\CouponRepository;
use App\Services\BaseService;

/**
 * 쿠폰 목록 조회 Service
 */
class ListCouponsService extends BaseService
{
    public function __construct(
        private readonly CouponRepository $couponRepository
    ) {
        parent::__construct();
    }

    /**
     * @param array $filters
     * @return array
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];

        $coupons = $this->couponRepository->findAll($filters);
        $total = $this->couponRepository->count($filters);

        return [
            "coupons" => $coupons,
            "pagination" => [
                "total" => $total,
                "per_page" => $filters["per_page"] ?? 20,
                "current_page" => $filters["page"] ?? 1,
                "last_page" => (int) ceil($total / ($filters["per_page"] ?? 20))
            ]
        ];
    }
}
