<?php

namespace App\Services\Product;

use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;

/**
 * 상품 정보 수정 Service
 */
class UpdateProductService extends ProductService
{
    private ProductRepository $productRepo;
    private ProductValidator $validator;

    public function __construct()
    {
        parent::__construct();
        $this->productRepo = new ProductRepository($this->db);
        $this->validator = new ProductValidator($this->db);
    }

    /**
     * 상품 정보 수정
     *
     * @param string $productCode
     * @param array $data
     * @return array
     */
    protected function execute(...$args): array
    {
        $productCode = $args[0];
        $data = $args[1];

        $product = $this->validator->validateProductExists($productCode);

        $payload = [
            "name" => $data["name"] ?? $product->name,
            "description" => array_key_exists("description", $data) ? $data["description"] : $product->description,
            "base_price" => $data["base_price"] ?? $product->basePrice,
            "category" => $data["category"] ?? $product->category,
        ];

        $this->productRepo->update($productCode, $payload);

        $updated = $this->validator->validateProductExists($productCode);

        return $this->formatProduct($updated);
    }
}
