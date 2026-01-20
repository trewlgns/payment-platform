<?php

namespace App\Repositories;

use App\Entities\ProductEntity;
use PDO;

class ProductRepository extends BaseRepository
{
    protected string $table = "products";
    protected string $primaryKey = "product_code";

    /**
     * 상품 코드로 상품 정보 조회
     *
     * @param string $productCode
     * @return ProductEntity|null
     */
    public function findByCode(string $productCode): ?ProductEntity
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `product_code` = :product_code
        SQL;

        $row = $this->db->selectOne($query, [
            "product_code" => ["value" => $productCode, "type" => PDO::PARAM_STR]
        ]);

        return $row ? new ProductEntity($row) : null;
    }

    /**
     * 상태/카테고리 필터 기반 상품 목록 조회 (페이지네이션)
     *
     * @param string|null $status
     * @param string|null $category
     * @param int $limit
     * @param int $offset
     * @return ProductEntity[]
     */
    public function findByFilters(?string $status, ?string $category, int $limit, int $offset): array
    {
        $conditions = ["1 = 1"];
        $bindings = [
            "limit" => ["value" => $limit, "type" => PDO::PARAM_INT],
            "offset" => ["value" => $offset, "type" => PDO::PARAM_INT],
        ];

        if ($status !== null) {
            $conditions[] = "`status` = :status";
            $bindings["status"] = ["value" => $status, "type" => PDO::PARAM_STR];
        }

        if ($category !== null) {
            $conditions[] = "`category` = :category";
            $bindings["category"] = ["value" => $category, "type" => PDO::PARAM_STR];
        }

        $whereClause = implode(" AND ", $conditions);

        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       {$whereClause}
            ORDER BY    `created_at` DESC
            LIMIT       :limit OFFSET :offset
        SQL;

        $rows = $this->db->select($query, $bindings);

        return array_map(fn(array $row) => new ProductEntity($row), $rows);
    }

    /**
     * 필터 기반 전체 개수 조회
     *
     * @param string|null $status
     * @param string|null $category
     * @return int
     */
    public function countByFilters(?string $status, ?string $category): int
    {
        $conditions = ["1 = 1"];
        $bindings = [];

        if ($status !== null) {
            $conditions[] = "`status` = :status";
            $bindings["status"] = ["value" => $status, "type" => PDO::PARAM_STR];
        }

        if ($category !== null) {
            $conditions[] = "`category` = :category";
            $bindings["category"] = ["value" => $category, "type" => PDO::PARAM_STR];
        }

        $whereClause = implode(" AND ", $conditions);

        $query = <<<SQL
            SELECT  COUNT(*) AS cnt
            FROM    `{$this->table}`
            WHERE   {$whereClause}
        SQL;

        $result = $this->db->selectOne($query, $bindings);

        return (int) ($result["cnt"] ?? 0);
    }

    /**
     * 활성화된 상품 목록 조회 (legacy helper)
     *
     * @param int $limit
     * @return ProductEntity[]
     */
    public function findActive(int $limit = 100): array
    {
        return $this->findByFilters("active", null, $limit, 0);
    }

    /**
     * 카테고리별 활성 상품 조회 (legacy helper)
     *
     * @param string $category
     * @param int $limit
     * @return ProductEntity[]
     */
    public function findByCategory(string $category, int $limit = 100): array
    {
        return $this->findByFilters("active", $category, $limit, 0);
    }

    /**
     * 상품 생성
     *
     * @param array $data
     * @return int|string Product Code 또는 lastInsertId
     */
    public function create(array $data)
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `{$this->table}` (
                `product_code`,
                `name`,
                `description`,
                `base_price`,
                `category`,
                `status`,
                `created_at`,
                `updated_at`
            ) VALUES (
                :product_code,
                :name,
                :description,
                :base_price,
                :category,
                :status,
                :created_at,
                :updated_at
            )
        SQL;

        return $this->db->insert($query, [
            "product_code"  => ["value" => $data["product_code"], "type" => PDO::PARAM_STR],
            "name"          => ["value" => $data["name"], "type" => PDO::PARAM_STR],
            "description"   => [
                "value" => $data["description"] ?? null,
                "type" => isset($data["description"]) ? PDO::PARAM_STR : PDO::PARAM_NULL
            ],
            "base_price"    => ["value" => $data["base_price"], "type" => PDO::PARAM_STR],
            "category"      => ["value" => $data["category"], "type" => PDO::PARAM_STR],
            "status"        => ["value" => $data["status"], "type" => PDO::PARAM_STR],
            "created_at"    => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"    => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 상품 정보 업데이트
     *
     * @param string $productCode
     * @param array $data ["name"?, "description"?, "base_price"?, "category"?]
     * @return int Affected rows
     */
    public function update(string $productCode, array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `name` = :name,
                    `description` = :description,
                    `base_price` = :base_price,
                    `category` = :category,
                    `updated_at` = :updated_at
            WHERE   `product_code` = :product_code
        SQL;

        return $this->db->update($query, [
            "name"          => ["value" => $data["name"], "type" => PDO::PARAM_STR],
            "description"   => [
                "value" => $data["description"] ?? null,
                "type" => isset($data["description"]) ? PDO::PARAM_STR : PDO::PARAM_NULL
            ],
            "base_price"    => ["value" => $data["base_price"], "type" => PDO::PARAM_STR],
            "category"      => ["value" => $data["category"], "type" => PDO::PARAM_STR],
            "updated_at"    => ["value" => $now, "type" => PDO::PARAM_STR],
            "product_code"  => ["value" => $productCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 상품 상태 업데이트
     *
     * @param string $productCode
     * @param string $status
     * @return int Affected rows
     */
    public function updateStatus(string $productCode, string $status): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `status` = :status,
                    `updated_at` = :updated_at
            WHERE   `product_code` = :product_code
        SQL;

        return $this->db->update($query, [
            "status"        => ["value" => $status, "type" => PDO::PARAM_STR],
            "updated_at"    => ["value" => $now, "type" => PDO::PARAM_STR],
            "product_code"  => ["value" => $productCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 상품 삭제
     *
     * @param string $productCode
     * @return int Affected rows
     */
    public function delete(string $productCode): int
    {
        $query = <<<SQL
            DELETE FROM `{$this->table}`
            WHERE       `product_code` = :product_code
        SQL;

        return $this->db->delete($query, [
            "product_code" => ["value" => $productCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 상품 존재 여부 확인
     *
     * @param string $productCode
     * @return bool
     */
    public function exists(string $productCode): bool
    {
        $query = <<<SQL
            SELECT  1
            FROM    `{$this->table}`
            WHERE   `product_code` = :product_code
            LIMIT   1
        SQL;

        $result = $this->db->selectOne($query, [
            "product_code" => ["value" => $productCode, "type" => PDO::PARAM_STR]
        ]);

        return $result !== null;
    }

    /**
     * 상품명 기반 유사도 검색 (LIKE 패턴 매칭)
     *
     * @param string $keyword 검색어
     * @param int $limit 결과 개수 제한
     * @return array Raw array (Entity 변환 안함 - API 응답용)
     */
    public function searchByName(string $keyword, int $limit = 5): array
    {
        // LIKE 패턴 생성 (양쪽 와일드카드)
        $likePattern = "%{$keyword}%";

        $query = <<<SQL
            SELECT      `product_code`,
                        `name`,
                        `base_price`,
                        `category`,
                        `status`
            FROM        `{$this->table}`
            WHERE       `status` = :status
              AND       `name` LIKE :keyword
            ORDER BY    CASE
                            WHEN `name` = :exact_match THEN 1
                            WHEN `name` LIKE :starts_with THEN 2
                            ELSE 3
                        END,
                        `created_at` DESC
            LIMIT       :limit
        SQL;

        return $this->db->select($query, [
            "status"        => ["value" => "active", "type" => PDO::PARAM_STR],
            "keyword"       => ["value" => $likePattern, "type" => PDO::PARAM_STR],
            "exact_match"   => ["value" => $keyword, "type" => PDO::PARAM_STR],
            "starts_with"   => ["value" => "{$keyword}%", "type" => PDO::PARAM_STR],
            "limit"         => ["value" => $limit, "type" => PDO::PARAM_INT]
        ]);
    }
}
