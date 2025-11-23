<?php

namespace App\Http\Requests\PgProvider;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListPgProvidersRequest extends FormRequest
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
            "is_active" => [
                "sometimes",
                "integer",
                "in:0,1"
            ]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "is_active.integer" => __("messages.validation.pg_provider.is_active_integer"),
            "is_active.in" => __("messages.validation.pg_provider.is_active_in"),
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
     * @return array{is_active: ?int}
     */
    public function getFilters(): array
    {
        return [
            "is_active" => $this->query("is_active") !== null ? (int) $this->query("is_active") : null
        ];
    }
}
