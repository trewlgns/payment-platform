<?php

namespace App\Http\Requests\Order;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateOrderRequest extends FormRequest
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
                "string",
                "email",
                "max:255"
            ],
            "total_amount" => [
                "required",
                "numeric",
                "min:0",
                "max:9999999.99"
            ],
            "discount_amount" => [
                "sometimes",
                "numeric",
                "min:0",
                "max:9999999.99"
            ],
            "final_amount" => [
                "required",
                "numeric",
                "min:0",
                "max:9999999.99"
            ]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "customer_email.required" => __("messages.validation.order.customer_email_required"),
            "customer_email.email" => __("messages.validation.order.customer_email_email"),
            "customer_email.max" => __("messages.validation.order.customer_email_max"),
            "total_amount.required" => __("messages.validation.order.total_amount_required"),
            "total_amount.numeric" => __("messages.validation.order.total_amount_numeric"),
            "total_amount.min" => __("messages.validation.order.total_amount_min"),
            "total_amount.max" => __("messages.validation.order.total_amount_max"),
            "discount_amount.numeric" => __("messages.validation.order.discount_amount_numeric"),
            "discount_amount.min" => __("messages.validation.order.discount_amount_min"),
            "discount_amount.max" => __("messages.validation.order.discount_amount_max"),
            "final_amount.required" => __("messages.validation.order.final_amount_required"),
            "final_amount.numeric" => __("messages.validation.order.final_amount_numeric"),
            "final_amount.min" => __("messages.validation.order.final_amount_min"),
            "final_amount.max" => __("messages.validation.order.final_amount_max"),
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
