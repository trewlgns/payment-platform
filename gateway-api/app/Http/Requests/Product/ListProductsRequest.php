<?php

namespace App\Http\Requests\Product;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "status" => [
                "sometimes",
                "string",
                "in:active,inactive,soldout"
            ],
            "category" => [
                "sometimes",
                "string",
                "max:50"
            ],
            "page" => [
                "sometimes",
                "integer",
                "min:1"
            ],
            "per_page" => [
                "sometimes",
                "integer",
                "min:1",
                "max:100"
            ]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "status.in" => "상태는 active, inactive, soldout 중 하나여야 합니다.",
            "category.max" => "카테고리는 최대 50자까지 가능합니다.",
            "page.integer" => "페이지는 정수여야 합니다.",
            "page.min" => "페이지는 1 이상이어야 합니다.",
            "per_page.integer" => "페이지당 개수는 정수여야 합니다.",
            "per_page.min" => "페이지당 개수는 1 이상이어야 합니다.",
            "per_page.max" => "페이지당 개수는 100 이하여야 합니다.",
        ];
    }

    /**
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            "result" => false,
            "is_error" => false,
            "code" => ResponseMessage::VALIDATION_FAILED->value,
            "message" => ResponseMessage::VALIDATION_FAILED->message(),
            "details" => [
                "errors" => $validator->errors()
            ]
        ], ResponseMessage::VALIDATION_FAILED->statusCode());

        throw new HttpResponseException($response);
    }

    /**
     * 검증된 필터 파라미터 반환
     *
     * @return array{status: ?string, category: ?string, page: int, per_page: int}
     */
    public function getFilters(): array
    {
        return [
            "status" => $this->query("status"),
            "category" => $this->query("category"),
            "page" => (int) $this->query("page", 1),
            "per_page" => (int) $this->query("per_page", 20)
        ];
    }
}
