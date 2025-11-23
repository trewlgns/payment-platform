<?php

namespace App\Http\Requests\PgProvider;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreatePgProviderRequest extends FormRequest
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
            "pg_provider_code" => [
                "required",
                "string",
                "max:50",
                "unique:pg_providers,pg_provider_code"
            ],
            "name" => [
                "required",
                "string",
                "max:100"
            ],
            "is_active" => [
                "sometimes",
                "integer",
                "in:0,1"
            ],
            "priority" => [
                "sometimes",
                "integer",
                "min:0"
            ]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "pg_provider_code.required" => __("messages.validation.pg_provider.pg_provider_code_required"),
            "pg_provider_code.unique" => __("messages.validation.pg_provider.pg_provider_code_unique"),
            "pg_provider_code.max" => __("messages.validation.pg_provider.pg_provider_code_max"),
            "name.required" => __("messages.validation.pg_provider.name_required"),
            "name.max" => __("messages.validation.pg_provider.name_max"),
            "is_active.integer" => __("messages.validation.pg_provider.is_active_integer"),
            "is_active.in" => __("messages.validation.pg_provider.is_active_in"),
            "priority.integer" => __("messages.validation.pg_provider.priority_integer"),
            "priority.min" => __("messages.validation.pg_provider.priority_min"),
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
