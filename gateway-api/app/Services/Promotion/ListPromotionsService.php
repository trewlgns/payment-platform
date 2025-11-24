<?php

namespace App\Services\Promotion;

use App\Repositories\PromotionRepository;
use App\Services\BaseService;

/**
 * 프로모션 목록 조회 Service
 *
 * GET /api/promotions
 */
class ListPromotionsService extends BaseService
{
    private PromotionRepository $promotionRepo;

    public function __construct()
    {
        parent::__construct();
        $this->promotionRepo = new PromotionRepository($this->db);
    }

    /**
     * 프로모션 목록 조회 (필터링 + 페이지네이션)
     *
     * @param array $filters ["is_active" => int|null, "promotion_type" => string|null, "discount_type" => string|null, "page" => int, "per_page" => int]
     * @return array
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];

        $promotions = $this->promotionRepo->findAll($filters);
        $total = $this->promotionRepo->count($filters);

        return [
            "data" => array_map(fn($promotion) => [
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
            ], $promotions),
            "pagination" => [
                "total" => $total,
                "page" => $filters["page"] ?? 1,
                "per_page" => $filters["per_page"] ?? 20,
                "total_pages" => (int) ceil($total / ($filters["per_page"] ?? 20))
            ]
        ];
    }
}
