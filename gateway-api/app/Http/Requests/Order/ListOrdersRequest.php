<?php

namespace App\Http\Requests\Order;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListOrdersRequest extends FormRequest
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
                "in:pending,confirmed,paid,preparing,shipped,delivered,cancelled,refunded"
            ],
            "customer_email" => [
                "sometimes",
                "string",
                "email",
                "max:255"
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
            "status.in" => __("messages.validation.order.status_in"),
            "customer_email.email" => __("messages.validation.order.customer_email_email"),
            "customer_email.max" => __("messages.validation.order.customer_email_max"),
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
     * @return array{status: ?string, customer_email: ?string, page: int, per_page: int}
     */
    public function getFilters(): array
    {
        return [
            "status" => $this->query("status"),
            "customer_email" => $this->query("customer_email"),
            "page" => (int) $this->query("page", 1),
            "per_page" => (int) $this->query("per_page", 20)
        ];
    }
}
