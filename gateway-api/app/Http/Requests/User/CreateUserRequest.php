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
            "email.required" => "이메일은 필수 항목입니다.",
            "email.email" => "유효한 이메일 형식이 아닙니다.",
            "email.unique" => "이미 사용 중인 이메일입니다.",
            "password.required" => "비밀번호는 필수 항목입니다.",
            "password.min" => "비밀번호는 최소 :min자 이상이어야 합니다.",
            "name.required" => "이름은 필수 항목입니다.",
            "phone.required" => "전화번호는 필수 항목입니다.",
            "phone.regex" => "전화번호 형식이 올바르지 않습니다.",
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
