<?php

namespace App\Http\Requests\Promotion;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListPromotionsRequest extends FormRequest
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
            "is_active" => [
                "sometimes",
                "integer",
                "in:0,1"
            ],
            "promotion_type" => [
                "sometimes",
                "string",
                "in:cart_discount,product_discount,shipping_discount,coupon"
            ],
            "discount_type" => [
                "sometimes",
                "string",
                "in:percentage,fixed_amount"
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
            "is_active.integer" => __("messages.validation.promotion.is_active_integer"),
            "is_active.in" => __("messages.validation.promotion.is_active_in"),
            "promotion_type.in" => __("messages.validation.promotion.promotion_type_in"),
            "discount_type.in" => __("messages.validation.promotion.discount_type_in"),
            "page.integer" => __("messages.validation.pagination.page_integer"),
            "page.min" => __("messages.validation.pagination.page_min"),
            "per_page.integer" => __("messages.validation.pagination.per_page_integer"),
            "per_page.min" => __("messages.validation.pagination.per_page_min"),
            "per_page.max" => __("messages.validation.pagination.per_page_max"),
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
     * @return array
     */
    public function getFilters(): array
    {
        return [
            "is_active" => $this->query("is_active") !== null ? (int) $this->query("is_active") : null,
            "promotion_type" => $this->query("promotion_type"),
            "discount_type" => $this->query("discount_type"),
            "page" => (int) $this->query("page", 1),
            "per_page" => (int) $this->query("per_page", 20)
        ];
    }
}
