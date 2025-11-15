<?php

namespace App\Services\Product;

use App\Exceptions\ServerErrorException;
use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;

/**
 * 상품 삭제 Service
 */
class DeleteProductService extends ProductService
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
     * 상품 삭제
     *
     * @param string $productCode
     * @return void
     */
    protected function execute(...$args): void
    {
        $productCode = $args[0];

        $this->validator->validateProductExists($productCode);

        $deleted = $this->productRepo->delete($productCode);

        if ($deleted === 0) {
            throw new ServerErrorException(__("messages.product_delete_failed")); // 상품 삭제에 실패했습니다
        }
    }
}
