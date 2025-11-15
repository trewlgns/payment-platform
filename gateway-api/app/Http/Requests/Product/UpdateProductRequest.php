<?php

namespace App\Http\Requests\Product;

use App\Enums\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
                "sometimes",
                "string",
                "max:200"
            ],
            "description" => [
                "sometimes",
                "nullable",
                "string"
            ],
            "base_price" => [
                "sometimes",
                "numeric",
                "min:0"
            ],
            "category" => [
                "sometimes",
                "string",
                "max:50"
            ]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "base_price.numeric" => __("messages.validation.product.base_price_numeric"),
            "base_price.min" => __("messages.validation.product.base_price_min"),
            "category.max" => __("messages.validation.product.category_max"),
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
