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
            "product_code.required" => __("messages.validation.product.product_code_required"),
            "product_code.unique" => __("messages.validation.product.product_code_unique"),
            "name.required" => __("messages.validation.product.name_required"),
            "base_price.required" => __("messages.validation.product.base_price_required"),
            "base_price.numeric" => __("messages.validation.product.base_price_numeric"),
            "base_price.min" => __("messages.validation.product.base_price_min"),
            "category.required" => __("messages.validation.product.category_required"),
            "status.in" => __("messages.validation.product.status_in"),
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
