<?php

namespace App\Http\Requests\Statistics;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 일별 매출 통계 조회 요청 검증
 *
 * GET /api/statistics/daily-sales?start_date={date}&end_date={date}
 */
class GetDailySalesStatsRequest extends FormRequest
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
            "start_date" => ["required", "date", "date_format:Y-m-d"],
            "end_date" => ["required", "date", "date_format:Y-m-d", "after_or_equal:start_date"]
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
            "start_date.required" => __("messages.validation.statistics.start_date.required"),
            "start_date.date" => __("messages.validation.statistics.start_date.date"),
            "start_date.date_format" => __("messages.validation.statistics.start_date.date_format"),
            "end_date.required" => __("messages.validation.statistics.end_date.required"),
            "end_date.date" => __("messages.validation.statistics.end_date.date"),
            "end_date.date_format" => __("messages.validation.statistics.end_date.date_format"),
            "end_date.after_or_equal" => __("messages.validation.statistics.end_date.after_or_equal")
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
            "start_date" => $this->query("start_date"),
            "end_date" => $this->query("end_date")
        ];
    }
}
