<?php

namespace App\Services\Product;

use App\Repositories\ProductRepository;

/**
 * 상품 목록 조회 Service
 *
 * GET /api/products
 */
class ListProductsService extends ProductService
{
    private ProductRepository $productRepo;

    public function __construct()
    {
        parent::__construct();
        $this->productRepo = new ProductRepository($this->db);
    }

    /**
     * 상품 목록 조회 (필터 + 페이지네이션)
     *
     * @param array $filters ["status"?, "category"?, "page"?, "per_page"?]
     * @return array
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];

        $status = $filters["status"] ?? null;
        $category = $filters["category"] ?? null;
        $page = max(1, (int) ($filters["page"] ?? 1));
        $perPage = max(1, (int) ($filters["per_page"] ?? 20));
        $offset = ($page - 1) * $perPage;

        $products = $this->productRepo->findByFilters($status, $category, $perPage, $offset);
        $totalCount = $this->productRepo->countByFilters($status, $category);

        return [
            "data" => array_map(fn($product) => $this->formatProduct($product), $products),
            "pagination" => [
                "page" => $page,
                "per_page" => $perPage,
                "total" => $totalCount,
                "total_pages" => (int) ceil($totalCount / $perPage)
            ]
        ];
    }
}
