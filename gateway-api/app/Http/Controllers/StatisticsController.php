<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessage;
use App\Http\Requests\Statistics\GetCustomerSegmentStatsRequest;
use App\Http\Requests\Statistics\GetDailyPgStatsRequest;
use App\Http\Requests\Statistics\GetDailySalesStatsRequest;
use App\Http\Requests\Statistics\GetPromotionPerformanceStatsRequest;
use App\Services\Statistics\GetCustomerSegmentStatsService;
use App\Services\Statistics\GetDailyPgStatsService;
use App\Services\Statistics\GetDailySalesStatsService;
use App\Services\Statistics\GetPromotionPerformanceStatsService;
use Illuminate\Http\JsonResponse;

/**
 * 통계 Controller
 *
 * 일별/월별 매출 통계, PG별 통계, 고객 세그먼트 통계, 프로모션 성과 통계 조회
 */
class StatisticsController extends BaseController
{
    /**
     * 일별 매출 통계 조회
     *
     * GET /api/statistics/daily-sales?start_date={date}&end_date={date}
     *
     * @param GetDailySalesStatsRequest $request
     * @param GetDailySalesStatsService $service
     * @return JsonResponse
     */
    public function getDailySales(
        GetDailySalesStatsRequest $request,
        GetDailySalesStatsService $service
    ): JsonResponse
    {
        $filters = $request->getFilters();

        $result = $service->handle($filters);

        return $this->success($result, ResponseMessage::SUCCESS);
    }

    /**
     * 일별 PG 통계 조회
     *
     * GET /api/statistics/daily-pg?start_date={date}&end_date={date}&pg_provider_code={code}
     *
     * @param GetDailyPgStatsRequest $request
     * @param GetDailyPgStatsService $service
     * @return JsonResponse
     */
    public function getDailyPg(
        GetDailyPgStatsRequest $request,
        GetDailyPgStatsService $service
    ): JsonResponse
    {
        $filters = $request->getFilters();

        $result = $service->handle($filters);

        return $this->success($result, ResponseMessage::SUCCESS);
    }

    /**
     * 고객 세그먼트 통계 조회
     *
     * GET /api/statistics/customer-segment?stats_date={date}
     *
     * @param GetCustomerSegmentStatsRequest $request
     * @param GetCustomerSegmentStatsService $service
     * @return JsonResponse
     */
    public function getCustomerSegment(
        GetCustomerSegmentStatsRequest $request,
        GetCustomerSegmentStatsService $service
    ): JsonResponse
    {
        $filters = $request->getFilters();

        $result = $service->handle($filters);

        return $this->success($result, ResponseMessage::SUCCESS);
    }

    /**
     * 프로모션 성과 통계 조회
     *
     * GET /api/statistics/promotion-performance?start_date={date}&end_date={date}&promotion_code={code}
     *
     * @param GetPromotionPerformanceStatsRequest $request
     * @param GetPromotionPerformanceStatsService $service
     * @return JsonResponse
     */
    public function getPromotionPerformance(
        GetPromotionPerformanceStatsRequest $request,
        GetPromotionPerformanceStatsService $service
    ): JsonResponse
    {
        $filters = $request->getFilters();

        $result = $service->handle($filters);

        return $this->success($result, ResponseMessage::SUCCESS);
    }
}
