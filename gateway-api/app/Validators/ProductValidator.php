<?php

namespace App\Validators;

use App\Database\DB;
use App\Entities\ProductEntity;
use App\Exceptions\ConflictException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Repositories\ProductRepository;

/**
 * Product 도메인 Validator
 *
 * 상품 존재 여부, 상태, 중복 여부 등 Semantic 검증을 담당한다.
 */
class ProductValidator extends BaseValidator
{
    private ProductRepository $productRepo;

    public function __construct(DB &$db)
    {
        parent::__construct($db);
        $this->productRepo = new ProductRepository($db);
    }

    // ========================================
    // 존재성 검증
    // ========================================

    /**
     * 상품 존재 여부 확인 (존재하지 않으면 예외)
     *
     * @param string $productCode
     * @return ProductEntity
     * @throws NotFoundException
     */
    public function validateProductExists(string $productCode): ProductEntity
    {
        $product = $this->productRepo->findByCode($productCode);

        if (!$product) {
            throw new NotFoundException("상품을 찾을 수 없습니다");
        }

        return $product;
    }

    /**
     * 상품 코드 중복 여부 확인
     *
     * @param string $productCode
     * @return void
     * @throws ConflictException
     */
    public function validateProductCodeNotExists(string $productCode): void
    {
        if ($this->productRepo->exists($productCode)) {
            throw new ConflictException("이미 존재하는 상품 코드입니다");
        }
    }

    // ========================================
    // 상태 검증
    // ========================================

    /**
     * 상품이 활성 상태인지 확인
     *
     * @param ProductEntity $product
     * @return void
     * @throws ForbiddenException
     */
    public function validateProductActive(ProductEntity $product): void
    {
        if (!$product->isActive()) {
            throw new ForbiddenException("비활성화된 상품입니다");
        }
    }

    /**
     * 상품이 품절 상태가 아닌지 확인
     *
     * @param ProductEntity $product
     * @return void
     * @throws ForbiddenException
     */
    public function validateProductNotSoldOut(ProductEntity $product): void
    {
        if ($product->isSoldOut()) {
            throw new ForbiddenException("품절된 상품입니다");
        }
    }

    // ========================================
    // 복합 검증
    // ========================================

    /**
     * 활성 상품인지 확인 (존재 + 활성 + 품절 아님)
     *
     * @param string $productCode
     * @return ProductEntity
     */
    public function validateActiveProduct(string $productCode): ProductEntity
    {
        $product = $this->validateProductExists($productCode);
        $this->validateProductActive($product);
        $this->validateProductNotSoldOut($product);

        return $product;
    }
}
