<?php

namespace App\Validators;

use App\Database\DB;

/**
 * Validator 추상 클래스
 *
 * 모든 Validator의 Base 클래스로, DB 인스턴스를 참조로 주입받아
 * 트랜잭션을 공유하고 Semantic 검증을 수행한다.
 *
 * Validator의 역할:
 * - 존재성 검증 (리소스가 존재하는가?)
 * - 상태 검증 (리소스가 적절한 상태인가?)
 * - 관계 검증 (연관된 리소스가 유효한가?)
 * - 비즈니스 정책 검증 (비즈니스 규칙을 만족하는가?)
 *
 * Repository와의 차이점:
 * - Repository: 데이터 접근 (쿼리 실행)
 * - Validator: 데이터 검증 (Repository를 사용하여 검증 수행)
 */
abstract class BaseValidator
{
    /**
     * DB 인스턴스 (모든 Validator가 같은 DB 객체 공유)
     *
     * Repository와 동일하게 참조(&)로 주입받아 트랜잭션을 공유한다.
     */
    protected DB $db;

    /**
     * 생성자 - DB 객체 주입 (트랜잭션 공유를 위해 참조로 받음)
     *
     * @param DB $db DB 인스턴스 (참조로 전달)
     */
    public function __construct(DB &$db)
    {
        $this->db = $db;
    }
}
