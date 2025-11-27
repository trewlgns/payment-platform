<?php

namespace App\Http\Requests\Statistics;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 고객 세그먼트 통계 조회 요청 검증
 *
 * GET /api/statistics/customer-segment?stats_date={date}
 */
class GetCustomerSegmentStatsRequest extends FormRequest
{
    /**
     * 권한 검증
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Syntax 검증 규칙
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "stats_date" => ["required", "date", "date_format:Y-m-d"]
        ];
    }

    /**
     * 검증 실패 메시지 (다국어 키)
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "stats_date.required" => __("messages.validation.statistics.stats_date.required"),
            "stats_date.date" => __("messages.validation.statistics.stats_date.date"),
            "stats_date.date_format" => __("messages.validation.statistics.stats_date.date_format")
        ];
    }

    /**
     * 검증된 필터 데이터 반환
     *
     * @return array
     */
    public function getFilters(): array
    {
        return [
            "stats_date" => $this->query("stats_date")
        ];
    }
}
