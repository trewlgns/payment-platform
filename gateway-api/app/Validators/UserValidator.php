<?php

namespace App\Validators;

use App\Database\DB;
use App\Repositories\UserRepository;
use App\Entities\UserEntity;
use App\Exceptions\NotFoundException;
use App\Exceptions\ConflictException;
use App\Exceptions\ForbiddenException;

/**
 * User 도메인 Validator
 *
 * 사용자 관련 Semantic 검증 담당:
 * - 존재성 검증 (사용자가 존재하는가?)
 * - 중복 검증 (이메일이 이미 존재하는가?)
 * - 상태 검증 (사용자가 활성 상태인가?)
 * - 삭제 여부 검증 (사용자가 삭제되지 않았는가?)
 */
class UserValidator extends BaseValidator
{
    private UserRepository $userRepo;

    /**
     * 생성자 - DB 및 Repository 초기화
     *
     * @param DB $db DB 인스턴스 (참조로 전달)
     */
    public function __construct(DB &$db)
    {
        parent::__construct($db);
        $this->userRepo = new UserRepository($db);
    }

    // ========================================
    // 존재성 검증
    // ========================================

    /**
     * 사용자가 존재하는지 확인 (존재하지 않으면 예외)
     *
     * @param string $email 사용자 이메일
     * @return UserEntity 사용자 Entity (검증 통과 시 Service에서 재사용)
     * @throws NotFoundException 사용자를 찾을 수 없는 경우
     */
    public function validateUserExists(string $email): UserEntity
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            throw new NotFoundException(__("messages.user_not_found"));
        }

        return $user;
    }

    // ========================================
    // 중복 검증
    // ========================================

    /**
     * 이메일 중복 확인 (중복이면 예외)
     *
     * @param string $email 사용자 이메일
     * @return void
     * @throws ConflictException 이메일이 이미 존재하는 경우
     */
    public function validateEmailNotExists(string $email): void
    {
        if ($this->userRepo->existsByEmail($email)) {
            throw new ConflictException(__("messages.user_email_exists"));
        }
    }

    // ========================================
    // 상태 검증
    // ========================================

    /**
     * 사용자 상태가 활성화인지 확인
     *
     * @param UserEntity $user 사용자 Entity
     * @return void
     * @throws ForbiddenException 비활성화된 사용자인 경우
     */
    public function validateUserActive(UserEntity $user): void
    {
        if (!$user->isActive()) {
            throw new ForbiddenException(__("messages.user_inactive"));
        }
    }

    /**
     * 사용자가 삭제되지 않았는지 확인
     *
     * @param UserEntity $user 사용자 Entity
     * @return void
     * @throws NotFoundException 삭제된 사용자인 경우
     */
    public function validateUserNotDeleted(UserEntity $user): void
    {
        if ($user->isDeleted()) {
            throw new NotFoundException(__("messages.user_deleted_state"));
        }
    }

    /**
     * 사용자 이메일이 인증되었는지 확인
     *
     * @param UserEntity $user 사용자 Entity
     * @return void
     * @throws ForbiddenException 이메일 미인증 사용자인 경우
     */
    public function validateEmailVerified(UserEntity $user): void
    {
        if (!$user->isEmailVerified()) {
            throw new ForbiddenException(__("messages.user_email_not_verified"));
        }
    }

    /**
     * 사용자 이메일이 인증되지 않았는지 확인 (중복 인증 방지)
     *
     * @param UserEntity $user 사용자 Entity
     * @return void
     * @throws ConflictException 이미 인증된 사용자인 경우
     */
    public function validateEmailNotVerified(UserEntity $user): void
    {
        if ($user->isEmailVerified()) {
            throw new ConflictException(__("messages.user_email_already_verified"));
        }
    }

    // ========================================
    // 복합 검증 (여러 조건 조합)
    // ========================================

    /**
     * 활성 사용자인지 확인 (존재 + 활성 + 미삭제)
     *
     * Service에서 자주 사용되는 조합 검증을 하나의 메서드로 제공
     *
     * @param string $email 사용자 이메일
     * @return UserEntity 사용자 Entity
     * @throws NotFoundException 사용자를 찾을 수 없거나 삭제된 경우
     * @throws ForbiddenException 비활성화된 사용자인 경우
     */
    public function validateActiveUser(string $email): UserEntity
    {
        $user = $this->validateUserExists($email);
        $this->validateUserActive($user);
        $this->validateUserNotDeleted($user);

        return $user;
    }

    /**
     * 인증된 활성 사용자인지 확인 (존재 + 활성 + 미삭제 + 이메일 인증)
     *
     * @param string $email 사용자 이메일
     * @return UserEntity 사용자 Entity
     * @throws NotFoundException 사용자를 찾을 수 없거나 삭제된 경우
     * @throws ForbiddenException 비활성화되었거나 이메일 미인증 사용자인 경우
     */
    public function validateVerifiedActiveUser(string $email): UserEntity
    {
        $user = $this->validateActiveUser($email);
        $this->validateEmailVerified($user);

        return $user;
    }
}
