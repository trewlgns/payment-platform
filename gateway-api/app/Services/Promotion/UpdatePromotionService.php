<?php

namespace App\Services\Promotion;

use App\Repositories\PromotionRepository;
use App\Services\BaseService;
use App\Validators\PromotionValidator;

/**
 * 프로모션 수정 Service
 *
 * PUT /api/promotions/{promotionCode}
 */
class UpdatePromotionService extends BaseService
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
     * 프로모션 수정
     *
     * @param string $promotionCode
     * @param array $data
     * @return array
     */
    protected function execute(...$args): array
    {
        $promotionCode = $args[0];
        $data = $args[1];

        // 존재 여부 검증
        $this->promotionValidator->validatePromotionExists($promotionCode);

        // 기간 유효성 검증
        $this->promotionValidator->validatePromotionPeriod($data["start_at"], $data["end_at"]);

        // 프로모션 수정
        $this->promotionRepo->update($promotionCode, $data);

        // 수정된 프로모션 조회 및 반환
        $promotion = $this->promotionRepo->findByCode($promotionCode);

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
