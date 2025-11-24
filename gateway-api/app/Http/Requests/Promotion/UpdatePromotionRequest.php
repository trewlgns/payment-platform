<?php

namespace App\Http\Requests\Promotion;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePromotionRequest extends FormRequest
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
            "name" => [
                "required",
                "string",
                "max:200"
            ],
            "promotion_type" => [
                "required",
                "string",
                "in:cart_discount,product_discount,shipping_discount,coupon"
            ],
            "discount_type" => [
                "required",
                "string",
                "in:percentage,fixed_amount"
            ],
            "discount_value" => [
                "required",
                "numeric",
                "min:0"
            ],
            "start_at" => [
                "required",
                "date_format:Y-m-d H:i:s"
            ],
            "end_at" => [
                "required",
                "date_format:Y-m-d H:i:s",
                "after:start_at"
            ]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "name.required" => __("messages.validation.promotion.name_required"),
            "name.max" => __("messages.validation.promotion.name_max"),
            "promotion_type.required" => __("messages.validation.promotion.promotion_type_required"),
            "promotion_type.in" => __("messages.validation.promotion.promotion_type_in"),
            "discount_type.required" => __("messages.validation.promotion.discount_type_required"),
            "discount_type.in" => __("messages.validation.promotion.discount_type_in"),
            "discount_value.required" => __("messages.validation.promotion.discount_value_required"),
            "discount_value.numeric" => __("messages.validation.promotion.discount_value_numeric"),
            "discount_value.min" => __("messages.validation.promotion.discount_value_min"),
            "start_at.required" => __("messages.validation.promotion.start_at_required"),
            "start_at.date_format" => __("messages.validation.promotion.start_at_date_format"),
            "end_at.required" => __("messages.validation.promotion.end_at_required"),
            "end_at.date_format" => __("messages.validation.promotion.end_at_date_format"),
            "end_at.after" => __("messages.validation.promotion.end_at_after"),
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
}
