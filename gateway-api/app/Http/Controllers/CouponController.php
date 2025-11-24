<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessage;
use App\Http\Requests\Coupon\IssueCouponRequest;
use App\Http\Requests\Coupon\ListCouponsRequest;
use App\Http\Requests\Coupon\UseCouponRequest;
use App\Services\Coupon\ExpireCouponService;
use App\Services\Coupon\GetCouponService;
use App\Services\Coupon\IssueCouponService;
use App\Services\Coupon\ListCouponsService;
use App\Services\Coupon\UseCouponService;
use Illuminate\Http\JsonResponse;

/**
 * 쿠폰 관리 Controller
 */
class CouponController extends BaseController
{
    /**
     * 쿠폰 목록 조회
     *
     * GET /api/coupons
     */
    public function index(ListCouponsRequest $request, ListCouponsService $service): JsonResponse
    {
        $filters = $request->getFilters();
        $result = $service->handle($filters);

        return $this->success($result, ResponseMessage::COUPON_LIST_SUCCESS);
    }

    /**
     * 쿠폰 상세 조회
     *
     * GET /api/coupons/{couponCode}
     */
    public function show(string $couponCode, GetCouponService $service): JsonResponse
    {
        $coupon = $service->handle($couponCode);

        return $this->success($coupon, ResponseMessage::COUPON_DETAIL_SUCCESS);
    }

    /**
     * 쿠폰 발급
     *
     * POST /api/coupons
     */
    public function store(IssueCouponRequest $request, IssueCouponService $service): JsonResponse
    {
        $validated = $request->validated();
        $coupon = $service->handle($validated);

        return $this->success($coupon, ResponseMessage::COUPON_ISSUED);
    }

    /**
     * 쿠폰 사용
     *
     * POST /api/coupons/{couponCode}/use
     */
    public function use(string $couponCode, UseCouponRequest $request, UseCouponService $service): JsonResponse
    {
        $validated = $request->validated();
        $validated["coupon_code"] = $couponCode;

        $result = $service->handle($validated);

        return $this->success($result, ResponseMessage::COUPON_USED);
    }

    /**
     * 쿠폰 만료
     *
     * PATCH /api/coupons/{couponCode}/expire
     */
    public function expire(string $couponCode, ExpireCouponService $service): JsonResponse
    {
        $coupon = $service->handle($couponCode);

        return $this->success($coupon, ResponseMessage::COUPON_EXPIRED);
    }
}
