<?php

namespace App\Http\Requests\Coupon;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class IssueCouponRequest extends FormRequest
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
            "coupon_code" => [
                "required",
                "string",
                "max:50"
            ],
            "promotion_code" => [
                "required",
                "string",
                "max:50"
            ],
            "max_usage" => [
                "sometimes",
                "integer",
                "min:1"
            ],
            "expires_at" => [
                "required",
                "date_format:Y-m-d H:i:s"
            ]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "coupon_code.required" => __("messages.validation.coupon.coupon_code_required"),
            "coupon_code.max" => __("messages.validation.coupon.coupon_code_max"),
            "promotion_code.required" => __("messages.validation.coupon.promotion_code_required"),
            "promotion_code.max" => __("messages.validation.coupon.promotion_code_max"),
            "max_usage.integer" => __("messages.validation.coupon.max_usage_integer"),
            "max_usage.min" => __("messages.validation.coupon.max_usage_min"),
            "expires_at.required" => __("messages.validation.coupon.expires_at_required"),
            "expires_at.date_format" => __("messages.validation.coupon.expires_at_date_format"),
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
