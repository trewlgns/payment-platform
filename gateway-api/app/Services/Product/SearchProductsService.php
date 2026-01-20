<?php

namespace App\Services\Product;

use App\Database\DB;
use App\Repositories\ProductRepository;
use App\Services\BaseService;

class SearchProductsService extends BaseService
{
    private ProductRepository $productRepo;

    public function __construct()
    {
        parent::__construct();
        $this->productRepo = new ProductRepository($this->db);
    }

    /**
     * 상품 검색 실행
     *
     * @param array $params ["query" => string, "limit" => int]
     * @return array
     */
    protected function execute(...$args): array
    {
        $params = $args[0] ?? [];
        $query = $params["query"];
        $limit = $params["limit"];

        // 상품 검색 (유사도 기반)
        $products = $this->productRepo->searchByName($query, $limit);

        return $products;
    }
}
