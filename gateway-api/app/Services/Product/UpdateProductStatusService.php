<?php

namespace App\Services\Product;

use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;

/**
 * 상품 상태 변경 Service
 */
class UpdateProductStatusService extends ProductService
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
     * 상품 상태 변경
     *
     * @param string $productCode
     * @param string $status
     * @return array
     */
    protected function execute(...$args): array
    {
        $productCode = $args[0];
        $status = $args[1];

        $product = $this->validator->validateProductExists($productCode);

        if ($product->status !== $status) {
            $this->productRepo->updateStatus($productCode, $status);
        }

        $updated = $this->validator->validateProductExists($productCode);

        return $this->formatProduct($updated);
    }
}
