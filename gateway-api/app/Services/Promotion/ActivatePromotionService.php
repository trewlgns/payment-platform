<?php

namespace App\Services\Promotion;

use App\Repositories\PromotionRepository;
use App\Services\BaseService;
use App\Validators\PromotionValidator;

/**
 * 프로모션 활성화 Service
 *
 * PATCH /api/promotions/{promotionCode}/activate
 */
class ActivatePromotionService extends BaseService
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
     * 프로모션 활성화
     *
     * @param string $promotionCode
     * @return array
     */
    protected function execute(...$args): array
    {
        $promotionCode = $args[0];

        // 존재 여부 검증
        $promotion = $this->promotionValidator->validatePromotionExists($promotionCode);

        // 이미 활성화된 경우 검증
        $this->promotionValidator->validatePromotionInactive($promotion);

        // 프로모션 활성화
        $this->promotionRepo->updateActiveStatus($promotionCode, 1);

        // 활성화된 프로모션 조회 및 반환
        $promotion = $this->promotionRepo->findByCode($promotionCode);

        return [
            "promotion_code" => $promotion->promotionCode,
            "name" => $promotion->name,
            "is_active" => $promotion->isActive,
            "updated_at" => $promotion->updatedAt
        ];
    }
}
