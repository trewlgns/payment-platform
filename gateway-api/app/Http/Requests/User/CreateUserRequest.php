<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Enums\ResponseMessage;

class CreateUserRequest extends FormRequest
{
    /**
     * 권한 검증 (인증 미들웨어로 처리 예정)
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 검증 규칙 정의
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users,email"
            ],
            "password" => [
                "required",
                "string",
                "min:8",
                "max:255"
            ],
            "name" => [
                "required",
                "string",
                "max:100"
            ],
            "phone" => [
                "required",
                "string",
                "max:20",
                "regex:/^[0-9\-]+$/" // 숫자와 하이픈만 허용
            ]
        ];
    }

    /**
     * 검증 에러 메시지 커스터마이징
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "email.required" => __("messages.validation.user.email_required"),
            "email.email" => __("messages.validation.user.email_email"),
            "email.unique" => __("messages.validation.user.email_unique"),
            "password.required" => __("messages.validation.user.password_required"),
            "password.min" => __("messages.validation.user.password_min"),
            "name.required" => __("messages.validation.user.name_required"),
            "phone.required" => __("messages.validation.user.phone_required"),
            "phone.regex" => __("messages.validation.user.phone_regex"),
        ];
    }

    /**
     * 검증 실패 시 예외 처리
     *
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
