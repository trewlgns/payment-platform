<?php

namespace App\Entities;

/**
 * Product Entity - 상품 도메인 데이터 객체
 *
 * Repository에서 조회한 배열 데이터를 받아 순수 데이터 + 헬퍼 메서드만 제공한다.
 * Service/Validator 레이어가 상태를 직관적으로 판별할 수 있도록 boolean 헬퍼를 포함한다.
 */
class ProductEntity
{
    /**
     * 상품 코드 (PRIMARY KEY)
     */
    public string $productCode;

    /**
     * 상품명
     */
    public string $name;

    /**
     * 상품 설명
     */
    public ?string $description;

    /**
     * 기본 가격
     */
    public string $basePrice;

    /**
     * 카테고리
     */
    public string $category;

    /**
     * 상품 상태 (active|inactive|soldout)
     */
    public string $status;

    /**
     * 생성일시
     */
    public string $createdAt;

    /**
     * 수정일시
     */
    public string $updatedAt;

    /**
     * 생성자 - 배열 데이터로부터 Entity 생성
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->productCode = $data["product_code"];
        $this->name = $data["name"];
        $this->description = $data["description"] ?? null;
        $this->basePrice = $data["base_price"];
        $this->category = $data["category"];
        $this->status = $data["status"];
        $this->createdAt = $data["created_at"];
        $this->updatedAt = $data["updated_at"];
    }

    // ========================================
    // 상태 헬퍼
    // ========================================

    /**
     * 활성 상품 여부
     */
    public function isActive(): bool
    {
        return $this->status === "active";
    }

    /**
     * 비활성 상품 여부
     */
    public function isInactive(): bool
    {
        return $this->status === "inactive";
    }

    /**
     * 품절 상품 여부
     */
    public function isSoldOut(): bool
    {
        return $this->status === "soldout";
    }
}
