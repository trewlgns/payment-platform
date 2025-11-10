<?php

namespace App\Services;

use App\Database\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseService
{
    /**
     * DB 인스턴스 (각 Service가 독립적으로 소유)
     */
    protected DB $db;

    /**
     * 생성자 - DB 인스턴스 생성
     */
    public function __construct()
    {
        // Service가 DB 인스턴스를 직접 생성 (트랜잭션 독립성 보장)
        $this->db = new DB();
    }

    /**
     * Service 실행 진입점 (Controller에서 호출)
     * 공통 로직(로깅, 모니터링 등) 처리 후 execute() 위임
     *
     * @param mixed ...$args
     * @return mixed
     */
    public function handle(...$args)
    {
        // 서비스 실행 전 공통 작업
        $this->beforeExecute($args);

        try {
            // 트랜잭션 시작
            $this->db->beginTransaction();

            // 실제 비즈니스 로직 실행 (자식 클래스에서 구현)
            $result = $this->execute(...$args);

            // 트랜잭션 커밋
            $this->db->commit();

            // 서비스 실행 후 공통 작업
            $this->afterExecute($args, $result);

            return $result;

        } catch (\Exception $e) {
            // 트랜잭션 롤백
            $this->db->rollBack();

            // 에러 로깅
            $this->onError($e, $args);

            // Exception 재발생 (Global Handler가 처리)
            throw $e;
        }
    }

    /**
     * 실제 비즈니스 로직 (자식 클래스에서 반드시 구현)
     *
     * @param mixed ...$args
     * @return mixed
     */
    abstract protected function execute(...$args);

    /**
     * Service 실행 전 공통 작업 (Hook)
     *
     * @param array $args
     * @return void
     */
    protected function beforeExecute(array $args): void
    {
        // 기본 구현: 서비스 실행 로깅
        Log::info(static::class . " started", [
            "arguments" => $this->sanitizeLogData($args)
        ]);
    }

    /**
     * Service 실행 후 공통 작업 (Hook)
     *
     * @param array $args
     * @param mixed $result
     * @return void
     */
    protected function afterExecute(array $args, $result): void
    {
        // 기본 구현: 서비스 성공 로깅
        Log::info(static::class . " completed successfully");
    }

    /**
     * 에러 발생 시 공통 작업 (Hook)
     *
     * @param \Exception $e
     * @param array $args
     * @return void
     */
    protected function onError(\Exception $e, array $args): void
    {
        // 기본 구현: 에러 로깅
        Log::error(static::class . " failed", [
            "exception" => get_class($e),
            "message" => $e->getMessage(),
            "arguments" => $this->sanitizeLogData($args)
        ]);
    }

    /**
     * 로그 데이터 민감정보 제거 (Hook, 자식에서 오버라이드 가능)
     *
     * @param array $data
     * @return array
     */
    protected function sanitizeLogData(array $data): array
    {
        // 기본 구현: password, token 등 민감정보 마스킹
        $sanitized = $data;

        array_walk_recursive($sanitized, function (&$value, $key) {
            if (in_array($key, ["password", "password_confirmation", "token", "verification_token"])) {
                $value = "***REDACTED***";
            }
        });

        return $sanitized;
    }
}
