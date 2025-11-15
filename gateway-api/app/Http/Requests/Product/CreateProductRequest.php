<?php

namespace App\Http\Requests\Product;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateProductRequest extends FormRequest
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
            "product_code" => [
                "required",
                "string",
                "max:100",
                "unique:products,product_code"
            ],
            "name" => [
                "required",
                "string",
                "max:200"
            ],
            "description" => [
                "sometimes",
                "nullable",
                "string"
            ],
            "base_price" => [
                "required",
                "numeric",
                "min:0"
            ],
            "category" => [
                "required",
                "string",
                "max:50"
            ],
            "status" => [
                "sometimes",
                "string",
                "in:active,inactive,soldout"
            ]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "product_code.required" => "상품 코드는 필수 항목입니다.",
            "product_code.unique" => "이미 사용 중인 상품 코드입니다.",
            "name.required" => "상품명은 필수 항목입니다.",
            "base_price.required" => "기본 가격은 필수 항목입니다.",
            "base_price.numeric" => "기본 가격은 숫자여야 합니다.",
            "base_price.min" => "기본 가격은 0 이상이어야 합니다.",
            "category.required" => "카테고리는 필수 항목입니다.",
            "status.in" => "상태는 active, inactive, soldout 중 하나여야 합니다.",
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
