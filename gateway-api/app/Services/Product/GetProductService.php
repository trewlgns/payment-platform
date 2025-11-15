<?php

namespace App\Services\Product;

use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;

/**
 * 상품 상세 조회 Service
 */
class GetProductService extends ProductService
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
     * 상품 상세 조회
     *
     * @param string $productCode
     * @return array
     */
    protected function execute(...$args): array
    {
        $productCode = $args[0];

        $product = $this->validator->validateProductExists($productCode);

        return $this->formatProduct($product);
    }
}
