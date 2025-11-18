<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class ListPaymentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "status" => ["sometimes", "string", "in:pending,approved,failed,cancelled,refunded"],
            "order_id" => ["sometimes", "integer", "min:1"],
            "pg_provider_code" => ["sometimes", "string", "max:50"],
            "page" => ["sometimes", "integer", "min:1"],
            "per_page" => ["sometimes", "integer", "min:1", "max:100"]
        ];
    }

    public function messages(): array
    {
        return [
            "status.in" => __("messages.validation.payment.status.in"),
            "order_id.integer" => __("messages.validation.payment.order_id.integer"),
            "order_id.min" => __("messages.validation.payment.order_id.min"),
            "pg_provider_code.string" => __("messages.validation.payment.pg_provider_code.string"),
            "pg_provider_code.max" => __("messages.validation.payment.pg_provider_code.max"),
            "page.integer" => __("messages.validation.pagination.page.integer"),
            "page.min" => __("messages.validation.pagination.page.min"),
            "per_page.integer" => __("messages.validation.pagination.per_page.integer"),
            "per_page.min" => __("messages.validation.pagination.per_page.min"),
            "per_page.max" => __("messages.validation.pagination.per_page.max")
        ];
    }

    public function getFilters(): array
    {
        return [
            "status" => $this->query("status"),
            "order_id" => $this->query("order_id") ? (int) $this->query("order_id") : null,
            "pg_provider_code" => $this->query("pg_provider_code"),
            "page" => (int) $this->query("page", 1),
            "per_page" => (int) $this->query("per_page", 20)
        ];
    }
}
