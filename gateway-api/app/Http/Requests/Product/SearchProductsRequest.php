<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class SearchProductsRequest extends FormRequest
{
    /**
     * 권한 검증
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 검증 규칙
     */
    public function rules(): array
    {
        return [
            "q" => ["required", "string", "min:2", "max:100"],
            "limit" => ["sometimes", "integer", "min:1", "max:20"]
        ];
    }

    /**
     * 검증 실패 시 메시지
     */
    public function messages(): array
    {
        return [
            "q.required" => "검색어를 입력해주세요",
            "q.min" => "검색어는 최소 2자 이상이어야 합니다",
            "q.max" => "검색어는 최대 100자까지 입력 가능합니다",
            "limit.integer" => "limit은 정수여야 합니다",
            "limit.min" => "limit은 최소 1 이상이어야 합니다",
            "limit.max" => "limit은 최대 20까지 가능합니다"
        ];
    }

    /**
     * 검색 파라미터 추출
     */
    public function getSearchParams(): array
    {
        return [
            "query" => $this->input("q"),
            "limit" => (int) $this->input("limit", 5)
        ];
    }
}
