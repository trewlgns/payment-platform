<?php

namespace App\Http\Requests\Coupon;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UseCouponRequest extends FormRequest
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
            "customer_email" => [
                "required",
                "email",
                "max:255"
            ],
            "order_id" => [
                "required",
                "integer",
                "min:1"
            ]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "customer_email.required" => __("messages.validation.coupon.customer_email_required"),
            "customer_email.email" => __("messages.validation.coupon.customer_email_email"),
            "customer_email.max" => __("messages.validation.coupon.customer_email_max"),
            "order_id.required" => __("messages.validation.coupon.order_id_required"),
            "order_id.integer" => __("messages.validation.coupon.order_id_integer"),
            "order_id.min" => __("messages.validation.coupon.order_id_min"),
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
