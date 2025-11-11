<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Repositories\UserRepository;

/**
 * 사용자 목록 조회 Service
 *
 * GET /api/users?status=active&page=1&per_page=20
 */
class ListUsersService extends BaseService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository($this->db);
    }

    /**
     * 사용자 목록 조회 (필터링 + 페이지네이션)
     *
     * @param array $filters ["status" => string|null, "page" => int, "per_page" => int]
     * @return array ["data" => array, "pagination" => array]
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];

        // 페이지네이션 계산
        $page = $filters["page"] ?? 1;
        $perPage = $filters["per_page"] ?? 20;
        $offset = ($page - 1) * $perPage;

        // 사용자 목록 조회 (Entity[] 반환)
        $users = $filters["status"]
            ? $this->userRepo->findByStatus($filters["status"], $perPage, $offset)
            : $this->userRepo->findAll($perPage, $offset);

        // 전체 카운트 조회
        $totalCount = $filters["status"]
            ? $this->userRepo->countByStatus($filters["status"])
            : $this->userRepo->count();

        // Entity를 응답 배열로 변환 (민감정보 제외)
        $data = array_map(function ($user) {
            return [
                "email" => $user->email,
                "name" => $user->name,
                "phone" => $user->phone,
                "status" => $user->status,
                "email_verified_at" => $user->emailVerifiedAt,
                "created_at" => $user->createdAt,
                "updated_at" => $user->updatedAt
            ];
        }, $users);

        return [
            "data" => $data,
            "pagination" => [
                "page" => $page,
                "per_page" => $perPage,
                "total" => $totalCount,
                "total_pages" => (int) ceil($totalCount / $perPage)
            ]
        ];
    }
}
