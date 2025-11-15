<?php

namespace App\Services\Product;

use App\Entities\ProductEntity;
use App\Services\BaseService;

/**
 * Product 도메인 Service 공통 부모 클래스
 */
abstract class ProductService extends BaseService
{
    /**
     * ProductEntity를 API 응답 배열로 변환
     *
     * @param ProductEntity $product
     * @return array
     */
    protected function formatProduct(ProductEntity $product): array
    {
        return [
            "product_code" => $product->productCode,
            "name" => $product->name,
            "description" => $product->description,
            "base_price" => $product->basePrice,
            "category" => $product->category,
            "status" => $product->status,
            "created_at" => $product->createdAt,
            "updated_at" => $product->updatedAt,
        ];
    }
}
