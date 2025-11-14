<?php

namespace App\Repositories;

use App\Entities\EmailVerificationTokenEntity;
use PDO;

class EmailVerificationTokenRepository extends BaseRepository
{
    protected string $table = "email_verification_tokens";
    protected string $primaryKey = "token_hash";

    /**
     * 토큰 해시로 토큰 조회
     *
     * @param string $tokenHash
     * @return EmailVerificationTokenEntity|null
     */
    public function findByTokenHash(string $tokenHash): ?EmailVerificationTokenEntity
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `token_hash` = :token_hash
        SQL;

        $result = $this->db->selectOne($query, [
            "token_hash" => ["value" => $tokenHash, "type" => PDO::PARAM_STR]
        ]);

        return $result ? new EmailVerificationTokenEntity($result) : null;
    }

    /**
     * 이메일로 유효한 토큰 조회 (만료되지 않고, 사용되지 않은 토큰)
     *
     * @param string $email
     * @return EmailVerificationTokenEntity|null
     */
    public function findValidTokenByEmail(string $email): ?EmailVerificationTokenEntity
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `email` = :email
              AND   `expires_at` > NOW()
              AND   `verified_at` IS NULL
            ORDER BY `created_at` DESC
            LIMIT 1
        SQL;

        $result = $this->db->selectOne($query, [
            "email" => ["value" => $email, "type" => PDO::PARAM_STR]
        ]);

        return $result ? new EmailVerificationTokenEntity($result) : null;
    }

    /**
     * 토큰 생성
     *
     * @param string $tokenHash
     * @param string $email
     * @param int $expiresInHours (기본 24시간)
     * @return string 생성된 token_hash
     */
    public function create(string $tokenHash, string $email, int $expiresInHours = 24): string
    {
        $query = <<<SQL
            INSERT INTO `{$this->table}` (
                `token_hash`,
                `email`,
                `expires_at`,
                `created_at`
            ) VALUES (
                :token_hash,
                :email,
                DATE_ADD(NOW(), INTERVAL :expires_hours HOUR),
                NOW()
            )
        SQL;

        $this->db->insert($query, [
            "token_hash"    => ["value" => $tokenHash, "type" => PDO::PARAM_STR],
            "email"         => ["value" => $email, "type" => PDO::PARAM_STR],
            "expires_hours" => ["value" => $expiresInHours, "type" => PDO::PARAM_INT]
        ]);

        return $tokenHash;
    }

    /**
     * 토큰 인증 완료 처리
     *
     * @param string $tokenHash
     * @return int 영향받은 행 수
     */
    public function markAsVerified(string $tokenHash): int
    {
        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `verified_at` = NOW()
            WHERE   `token_hash` = :token_hash
        SQL;

        return $this->db->update($query, [
            "token_hash" => ["value" => $tokenHash, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 이메일의 모든 이전 토큰 무효화 (인증 완료 처리)
     *
     * @param string $email
     * @return int 영향받은 행 수
     */
    public function invalidateAllByEmail(string $email): int
    {
        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `verified_at` = NOW()
            WHERE   `email` = :email
              AND   `verified_at` IS NULL
        SQL;

        return $this->db->update($query, [
            "email" => ["value" => $email, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 만료된 토큰 삭제 (배치용)
     *
     * @return int 삭제된 행 수
     */
    public function deleteExpiredTokens(): int
    {
        $query = <<<SQL
            DELETE FROM `{$this->table}`
            WHERE   `expires_at` < NOW()
        SQL;

        return $this->db->delete($query, []);
    }
}
