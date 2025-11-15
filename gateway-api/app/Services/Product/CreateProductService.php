<?php

namespace App\Services\Product;

use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;

/**
 * 상품 생성 Service
 */
class CreateProductService extends ProductService
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
     * 상품 생성
     *
     * @param array $data
     * @return array
     */
    protected function execute(...$args): array
    {
        $data = $args[0];

        $productCode = $data["product_code"];
        $this->validator->validateProductCodeNotExists($productCode);

        $payload = [
            "product_code" => $productCode,
            "name" => $data["name"],
            "description" => $data["description"] ?? null,
            "base_price" => $data["base_price"],
            "category" => $data["category"],
            "status" => $data["status"] ?? "active",
        ];

        $this->productRepo->create($payload);

        $product = $this->validator->validateProductExists($productCode);

        return $this->formatProduct($product);
    }
}
