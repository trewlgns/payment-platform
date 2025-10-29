<?php

namespace App\Repositories;

use PDO;

class ProductRepository extends BaseRepository
{
    protected string $table = "products";
    protected string $primaryKey = "product_code";

    /**
     * 상품 코드로 상품 정보 조회
     *
     * @param string $productCode
     * @return array|null
     */
    public function findByCode(string $productCode): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `product_code` = :product_code
        SQL;

        return $this->db->selectOne($query, [
            "product_code" => ["value" => $productCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 활성화된 상품 목록 조회
     *
     * @param int $limit
     * @return array
     */
    public function findActive(int $limit = 100): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `status` = 'active'
            ORDER BY    `created_at` DESC
            LIMIT       :limit
        SQL;

        return $this->db->select($query, [
            "limit" => ["value" => $limit, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 카테고리별 상품 조회
     *
     * @param string $category
     * @param int $limit
     * @return array
     */
    public function findByCategory(string $category, int $limit = 100): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `category` = :category
              AND       `status` = 'active'
            ORDER BY    `created_at` DESC
            LIMIT       :limit
        SQL;

        return $this->db->select($query, [
            "category"  => ["value" => $category, "type" => PDO::PARAM_STR],
            "limit"     => ["value" => $limit, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 상품 생성
     *
     * @param array $data
     * @return int|string Product Code
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
            "description"   => ["value" => $data["description"] ?? null, "type" => isset($data["description"]) ? PDO::PARAM_STR : PDO::PARAM_NULL],
            "base_price"    => ["value" => $data["base_price"], "type" => PDO::PARAM_STR],
            "category"      => ["value" => $data["category"], "type" => PDO::PARAM_STR],
            "status"        => ["value" => $data["status"], "type" => PDO::PARAM_STR],
            "created_at"    => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"    => ["value" => $now, "type" => PDO::PARAM_STR]
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
}
