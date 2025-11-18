<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "order_id" => ["required", "integer", "min:1"],
            "pg_provider_code" => ["required", "string", "max:50"],
            "amount" => ["required", "numeric", "min:0"],
            "payment_method" => ["required", "string", "in:card,bank_transfer,virtual_account,mobile"],
            "idempotency_key" => ["required", "string", "max:255"]
        ];
    }

    public function messages(): array
    {
        return [
            "order_id.required" => __("messages.validation.payment.order_id.required"),
            "order_id.integer" => __("messages.validation.payment.order_id.integer"),
            "order_id.min" => __("messages.validation.payment.order_id.min"),
            "pg_provider_code.required" => __("messages.validation.payment.pg_provider_code.required"),
            "pg_provider_code.string" => __("messages.validation.payment.pg_provider_code.string"),
            "pg_provider_code.max" => __("messages.validation.payment.pg_provider_code.max"),
            "amount.required" => __("messages.validation.payment.amount.required"),
            "amount.numeric" => __("messages.validation.payment.amount.numeric"),
            "amount.min" => __("messages.validation.payment.amount.min"),
            "payment_method.required" => __("messages.validation.payment.payment_method.required"),
            "payment_method.in" => __("messages.validation.payment.payment_method.in"),
            "idempotency_key.required" => __("messages.validation.payment.idempotency_key.required"),
            "idempotency_key.string" => __("messages.validation.payment.idempotency_key.string"),
            "idempotency_key.max" => __("messages.validation.payment.idempotency_key.max")
        ];
    }
}
