<?php

namespace App\Services\Promotion;

use App\Repositories\PromotionRepository;
use App\Services\BaseService;
use App\Validators\PromotionValidator;

/**
 * 프로모션 삭제 Service
 *
 * DELETE /api/promotions/{promotionCode}
 */
class DeletePromotionService extends BaseService
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
     * 프로모션 삭제
     *
     * @param string $promotionCode
     * @return array
     */
    protected function execute(...$args): array
    {
        $promotionCode = $args[0];

        // 존재 여부 검증
        $promotion = $this->promotionValidator->validatePromotionExists($promotionCode);

        // 프로모션 삭제
        $this->promotionRepo->delete($promotionCode);

        return [
            "promotion_code" => $promotion->promotionCode
        ];
    }
}
