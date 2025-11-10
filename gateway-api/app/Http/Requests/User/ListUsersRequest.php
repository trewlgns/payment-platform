<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Enums\ResponseMessage;

class ListUsersRequest extends FormRequest
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
            "status" => [
                "sometimes",
                "string",
                "in:active,inactive,banned"
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
     * 검증 에러 메시지 커스터마이징
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "status.in" => "상태는 active, inactive, banned 중 하나여야 합니다.",
            "page.integer" => "페이지는 정수여야 합니다.",
            "page.min" => "페이지는 1 이상이어야 합니다.",
            "per_page.integer" => "페이지당 개수는 정수여야 합니다.",
            "per_page.min" => "페이지당 개수는 1 이상이어야 합니다.",
            "per_page.max" => "페이지당 개수는 100 이하여야 합니다.",
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

    /**
     * 검증된 쿼리 파라미터를 배열로 반환
     *
     * @return array
     */
    public function getFilters(): array
    {
        return [
            "status" => $this->query("status"),
            "page" => (int) $this->query("page", 1),
            "per_page" => (int) $this->query("per_page", 20)
        ];
    }
}
