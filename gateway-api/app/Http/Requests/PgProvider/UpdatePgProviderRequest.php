<?php

namespace App\Http\Requests\PgProvider;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePgProviderRequest extends FormRequest
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
                "max:100"
            ],
            "priority" => [
                "required",
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
            "name.required" => __("messages.validation.pg_provider.name_required"),
            "name.max" => __("messages.validation.pg_provider.name_max"),
            "priority.required" => __("messages.validation.pg_provider.priority_required"),
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
