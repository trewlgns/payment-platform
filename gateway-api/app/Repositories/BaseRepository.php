<?php

namespace App\Repositories;

use App\Database\DB;

abstract class BaseRepository
{
    /**
     * DB 인스턴스 (모든 리포지토리가 같은 DB 객체 공유)
     */
    protected DB $db;

    /**
     * 테이블명 (자식 클래스에서 정의 필수)
     */
    protected string $table;

    /**
     * Primary Key 컬럼명 (자식 클래스에서 재정의 가능)
     */
    protected string $primaryKey = "id";

    /**
     * 생성자 - DB 객체 주입 (트랜잭션 공유를 위해 참조로 받음)
     *
     * @param DB $db
     */
    public function __construct(DB &$db)
    {
        $this->db = $db;
    }

    /**
     * 테이블명 반환
     *
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Primary Key 컬럼명 반환
     *
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }
}
