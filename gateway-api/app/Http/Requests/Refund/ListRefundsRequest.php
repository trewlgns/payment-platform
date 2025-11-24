<?php

namespace App\Http\Requests\Refund;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListRefundsRequest extends FormRequest
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
                "in:requested,approved,processing,completed,rejected"
            ],
            "order_id" => [
                "sometimes",
                "integer",
                "min:1"
            ],
            "payment_id" => [
                "sometimes",
                "integer",
                "min:1"
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
            "status.in" => __("messages.validation.refund.status_in"),
            "order_id.integer" => __("messages.validation.refund.order_id_integer"),
            "order_id.min" => __("messages.validation.refund.order_id_min"),
            "payment_id.integer" => __("messages.validation.refund.payment_id_integer"),
            "payment_id.min" => __("messages.validation.refund.payment_id_min"),
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
     * @return array{status: ?string, order_id: ?int, payment_id: ?int, page: int, per_page: int}
     */
    public function getFilters(): array
    {
        return [
            "status" => $this->query("status"),
            "order_id" => $this->query("order_id") ? (int) $this->query("order_id") : null,
            "payment_id" => $this->query("payment_id") ? (int) $this->query("payment_id") : null,
            "page" => (int) $this->query("page", 1),
            "per_page" => (int) $this->query("per_page", 20)
        ];
    }
}
