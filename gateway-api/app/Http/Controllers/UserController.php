<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessage;
use App\Http\Requests\User\ListUsersRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdateUserStatusRequest;
use App\Http\Requests\User\VerifyEmailRequest;
use App\Services\User\ListUsersService;
use App\Services\User\GetUserService;
use App\Services\User\CreateUserService;
use App\Services\User\UpdateUserService;
use App\Services\User\DeleteUserService;
use App\Services\User\UpdateUserStatusService;
use App\Services\User\VerifyUserEmailService;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    /**
     * 사용자 목록 조회
     *
     * GET /api/users?status=active&page=1&per_page=20
     *
     * @param ListUsersRequest $request
     * @param ListUsersService $service
     * @return JsonResponse
     */
    public function index(ListUsersRequest $request, ListUsersService $service): JsonResponse
    {
        // Request 검증은 ListUsersRequest에서 자동 처리됨
        // 검증된 필터 추출
        $filters = $request->getFilters();

        // Service 호출 (handle 메서드 사용)
        $result = $service->handle($filters);

        // 성공 응답 반환
        return $this->success($result, ResponseMessage::SUCCESS);
    }

    /**
     * 사용자 상세 조회
     *
     * GET /api/users/{email}
     *
     * @param string $email
     * @param GetUserService $service
     * @return JsonResponse
     */
    public function show(string $email, GetUserService $service): JsonResponse
    {
        // Service 호출 (handle 메서드 사용)
        $user = $service->handle($email);

        // 성공 응답 반환
        return $this->success($user, ResponseMessage::SUCCESS);
    }

    /**
     * 사용자 생성 (회원가입)
     *
     * POST /api/users
     * Body: { "email", "password", "name", "phone" }
     *
     * @param CreateUserRequest $request
     * @param CreateUserService $service
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request, CreateUserService $service): JsonResponse
    {
        // Request 검증은 CreateUserRequest에서 자동 처리됨
        $validated = $request->validated();

        // Service 호출 (handle 메서드 사용)
        $user = $service->handle($validated);

        // 성공 응답 반환 (201 Created)
        return $this->success($user, ResponseMessage::USER_CREATED);
    }

    /**
     * 사용자 정보 수정
     *
     * PUT /api/users/{email}
     * Body: { "name"?, "phone"?, "status"? }
     *
     * @param string $email
     * @param UpdateUserRequest $request
     * @param UpdateUserService $service
     * @return JsonResponse
     */
    public function update(string $email, UpdateUserRequest $request, UpdateUserService $service): JsonResponse
    {
        // Request 검증은 UpdateUserRequest에서 자동 처리됨
        $validated = $request->validated();

        // Service 호출 (handle 메서드 사용)
        $user = $service->handle($email, $validated);

        // 성공 응답 반환
        return $this->success($user, ResponseMessage::USER_UPDATED);
    }

    /**
     * 사용자 삭제 (Soft Delete)
     *
     * DELETE /api/users/{email}
     *
     * @param string $email
     * @param DeleteUserService $service
     * @return JsonResponse
     */
    public function destroy(string $email, DeleteUserService $service): JsonResponse
    {
        // Service 호출 (handle 메서드 사용)
        $service->handle($email);

        // 성공 응답 반환
        return $this->success(null, ResponseMessage::USER_DELETED);
    }

    /**
     * 사용자 상태 변경
     *
     * PATCH /api/users/{email}/status
     * Body: { "status": "active" | "inactive" | "banned" }
     *
     * @param string $email
     * @param UpdateUserStatusRequest $request
     * @param UpdateUserStatusService $service
     * @return JsonResponse
     */
    public function updateStatus(string $email, UpdateUserStatusRequest $request, UpdateUserStatusService $service): JsonResponse
    {
        // Request 검증은 UpdateUserStatusRequest에서 자동 처리됨
        $validated = $request->validated();

        // Service 호출 (handle 메서드 사용)
        $user = $service->handle($email, $validated["status"]);

        // 성공 응답 반환
        return $this->success($user, ResponseMessage::USER_UPDATED);
    }

    /**
     * 이메일 인증 처리
     *
     * POST /api/users/{email}/verify-email
     * Body: { "verification_token": "abc123..." }
     *
     * @param string $email
     * @param VerifyEmailRequest $request
     * @param VerifyUserEmailService $service
     * @return JsonResponse
     */
    public function verifyEmail(string $email, VerifyEmailRequest $request, VerifyUserEmailService $service): JsonResponse
    {
        // Request 검증은 VerifyEmailRequest에서 자동 처리됨
        $validated = $request->validated();

        // Service 호출 (handle 메서드 사용)
        $user = $service->handle($email, $validated["verification_token"]);

        // 성공 응답 반환
        return $this->success($user, ResponseMessage::SUCCESS);
    }
}
