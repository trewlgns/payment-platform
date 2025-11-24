<?php

namespace App\Services\Promotion;

use App\Repositories\PromotionRepository;
use App\Services\BaseService;
use App\Validators\PromotionValidator;

/**
 * 프로모션 상세 조회 Service
 *
 * GET /api/promotions/{promotionCode}
 */
class GetPromotionService extends BaseService
{
    private PromotionRepository $promotionRepo;
    private PromotionValidator $promotionValidator;

    public function __construct()
    {
        parent::__construct();
        $this->promotionRepo = new PromotionRepository($this->db);
        $this->promotionValidator = new PromotionValidator($this->db);
    }

    /**
     * 프로모션 상세 조회
     *
     * @param string $promotionCode
     * @return array
     */
    protected function execute(...$args): array
    {
        $promotionCode = $args[0];

        // 존재 여부 검증
        $promotion = $this->promotionValidator->validatePromotionExists($promotionCode);

        return [
            "promotion_code" => $promotion->promotionCode,
            "name" => $promotion->name,
            "promotion_type" => $promotion->promotionType,
            "discount_type" => $promotion->discountType,
            "discount_value" => $promotion->discountValue,
            "start_at" => $promotion->startAt,
            "end_at" => $promotion->endAt,
            "is_active" => $promotion->isActive,
            "created_at" => $promotion->createdAt,
            "updated_at" => $promotion->updatedAt
        ];
    }
}
