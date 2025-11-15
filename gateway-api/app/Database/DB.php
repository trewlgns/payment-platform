<?php

namespace App\Database;

use App\Exceptions\ServerErrorException;
use PDO;
use PDOException;
use PDOStatement;

class DB
{
    private PDO $pdo;

    /**
     * 생성자 - PDO 인스턴스 생성
     */
    public function __construct()
    {
        // Laravel DB 설정으로 PDO 인스턴스 생성
        $host = config("database.connections.mysql.host");
        $database = config("database.connections.mysql.database");
        $username = config("database.connections.mysql.username");
        $password = config("database.connections.mysql.password");
        $charset = config("database.connections.mysql.charset", "utf8mb4");

        $dsn = "mysql:host={$host};dbname={$database};charset={$charset}";

        $this->pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    /**
     * SELECT 쿼리 실행 후 전체 결과 반환
     *
     * @param string $query Static SQL 쿼리 (Named Placeholder)
     * @param array $bindings 바인딩 파라미터
     * @return array 연관 배열의 배열
     * @throws PDOException
     */
    public function select(string $query, array $bindings = []): array
    {
        $stmt = $this->executeQuery($query, $bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * SELECT 쿼리 실행 후 첫 번째 결과만 반환
     *
     * @param string $query Static SQL 쿼리 (Named Placeholder)
     * @param array $bindings 바인딩 파라미터
     * @return array|null 연관 배열 또는 null
     * @throws PDOException
     */
    public function selectOne(string $query, array $bindings = []): ?array
    {
        $stmt = $this->executeQuery($query, $bindings);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }

    /**
     * INSERT 쿼리 실행 후 마지막 삽입 ID 반환
     *
     * @param string $query Static SQL INSERT 쿼리
     * @param array $bindings 바인딩 파라미터
     * @return int|string Last Insert ID
     * @throws PDOException
     */
    public function insert(string $query, array $bindings = [])
    {
        $this->executeQuery($query, $bindings);
        return $this->pdo->lastInsertId();
    }

    /**
     * UPDATE 쿼리 실행 후 영향받은 행 수 반환
     *
     * @param string $query Static SQL UPDATE 쿼리
     * @param array $bindings 바인딩 파라미터
     * @return int 영향받은 행 수
     * @throws PDOException
     */
    public function update(string $query, array $bindings = []): int
    {
        $stmt = $this->executeQuery($query, $bindings);
        return $stmt->rowCount();
    }

    /**
     * DELETE 쿼리 실행 후 영향받은 행 수 반환
     *
     * @param string $query Static SQL DELETE 쿼리
     * @param array $bindings 바인딩 파라미터
     * @return int 영향받은 행 수
     * @throws PDOException
     */
    public function delete(string $query, array $bindings = []): int
    {
        $stmt = $this->executeQuery($query, $bindings);
        return $stmt->rowCount();
    }

    /**
     * 트랜잭션 시작
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * 트랜잭션 커밋
     *
     * @return bool
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * 트랜잭션 롤백
     *
     * @return bool
     */
    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * 트랜잭션 내부인지 확인
     *
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * 쿼리 실행 (모든 쿼리 타입의 공통 로직)
     *
     * @param string $query Static SQL 쿼리
     * @param array $bindings ["paramName" => ["value" => $value, "type" => PDO::PARAM_*]]
     * @return PDOStatement
     * @throws PDOException
     */
    private function executeQuery(string $query, array $bindings = []): PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($query);

            // 파라미터 바인딩 (타입 명시 필수)
            foreach ($bindings as $paramName => $binding) {
                if (!is_array($binding) || !isset($binding["value"]) || !isset($binding["type"])) {
                    throw new ServerErrorException(
                        __("messages.db_binding_error"), // 데이터베이스 바인딩 형식이 올바르지 않습니다
                        "Invalid binding format for parameter {$paramName}",
                        ["parameter" => $paramName, "binding" => $binding]
                    );
                }

                $stmt->bindValue($paramName, $binding["value"], $binding["type"]);
            }

            $stmt->execute();
            return $stmt;

        } catch (PDOException $e) {
            // 에러 로깅 (디버깅용)
            error_log("Query execution failed: " . $e->getMessage());
            error_log("Query: " . $query);
            error_log("Bindings: " . json_encode($bindings));
            throw $e;
        }
    }

    /**
     * PDO 인스턴스 반환 (고급 작업용)
     *
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
