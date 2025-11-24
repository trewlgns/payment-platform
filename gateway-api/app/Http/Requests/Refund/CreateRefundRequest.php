<?php

namespace App\Http\Requests\Refund;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRefundRequest extends FormRequest
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
            "order_id" => [
                "required",
                "integer",
                "min:1"
            ],
            "payment_id" => [
                "required",
                "integer",
                "min:1"
            ],
            "requested_amount" => [
                "required",
                "numeric",
                "min:0",
                "max:9999999.99"
            ],
            "reason" => [
                "sometimes",
                "string",
                "max:1000"
            ]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "order_id.required" => __("messages.validation.refund.order_id_required"),
            "order_id.integer" => __("messages.validation.refund.order_id_integer"),
            "order_id.min" => __("messages.validation.refund.order_id_min"),
            "payment_id.required" => __("messages.validation.refund.payment_id_required"),
            "payment_id.integer" => __("messages.validation.refund.payment_id_integer"),
            "payment_id.min" => __("messages.validation.refund.payment_id_min"),
            "requested_amount.required" => __("messages.validation.refund.requested_amount_required"),
            "requested_amount.numeric" => __("messages.validation.refund.requested_amount_numeric"),
            "requested_amount.min" => __("messages.validation.refund.requested_amount_min"),
            "requested_amount.max" => __("messages.validation.refund.requested_amount_max"),
            "reason.string" => __("messages.validation.refund.reason_string"),
            "reason.max" => __("messages.validation.refund.reason_max"),
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
