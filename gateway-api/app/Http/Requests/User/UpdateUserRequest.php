<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Enums\ResponseMessage;

class UpdateUserRequest extends FormRequest
{
    /**
     * 권한 검증
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
            "name" => [
                "sometimes",
                "string",
                "max:100"
            ],
            "phone" => [
                "sometimes",
                "string",
                "max:20",
                "regex:/^[0-9\-]+$/"
            ],
            "status" => [
                "sometimes",
                "string",
                "in:active,inactive,banned"
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
            "name.string" => "이름은 문자열이어야 합니다.",
            "name.max" => "이름은 최대 :max자까지 가능합니다.",
            "phone.string" => "전화번호는 문자열이어야 합니다.",
            "phone.regex" => "전화번호 형식이 올바르지 않습니다.",
            "status.in" => "상태는 active, inactive, banned 중 하나여야 합니다.",
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
