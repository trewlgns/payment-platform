<?php

namespace App\Enums;

/**
 * 응답 메시지 Enum (PHP 8.1+)
 *
 * 메시지 키, HTTP 상태 코드, 다국어를 한 곳에서 관리
 * IDE 자동완성 + 타입 안전성 보장
 */
enum ResponseMessage: string
{
    // 공통 메시지
    case SUCCESS = "success";
    case ERROR = "error";
    case NOT_FOUND = "not_found";
    case CREATED = "created";
    case UPDATED = "updated";
    case DELETED = "deleted";
    case VALIDATION_FAILED = "validation_failed";
    case UNAUTHORIZED = "unauthorized";
    case FORBIDDEN = "forbidden";
    case SERVER_ERROR = "server_error";

    // 상품 관련
    case PRODUCT_NOT_FOUND = "product_not_found";
    case PRODUCT_LIST_SUCCESS = "product_list_success";
    case PRODUCT_DETAIL_SUCCESS = "product_detail_success";
    case PRODUCT_CREATED = "product_created";
    case PRODUCT_UPDATED = "product_updated";
    case PRODUCT_DELETED = "product_deleted";
    case PRODUCT_STATUS_UPDATED = "product_status_updated";
    case PRODUCT_OUT_OF_STOCK = "product_out_of_stock";

    // 주문 관련
    case ORDER_NOT_FOUND = "order_not_found";
    case ORDER_LIST_SUCCESS = "order_list_success";
    case ORDER_DETAIL_SUCCESS = "order_detail_success";
    case ORDER_CREATED = "order_created";
    case ORDER_CANCELLED = "order_cancelled";
    case ORDER_REFUNDED = "order_refunded";
    case ORDER_STATUS_UPDATED = "order_status_updated";

    // 결제 관련
    case PAYMENT_NOT_FOUND = "payment_not_found";
    case PAYMENT_DETAIL_SUCCESS = "payment_detail_success";
    case PAYMENT_SUCCESS = "payment_success";
    case PAYMENT_FAILED = "payment_failed";
    case PAYMENT_CANCELLED = "payment_cancelled";
    case PAYMENT_REFUNDED = "payment_refunded";
    case PAYMENT_ALREADY_PROCESSED = "payment_already_processed";
    case PAYMENT_AMOUNT_MISMATCH = "payment_amount_mismatch";

    // 사용자 관련
    case USER_NOT_FOUND = "user_not_found";
    case USER_CREATED = "user_created";
    case USER_UPDATED = "user_updated";
    case USER_DELETED = "user_deleted";

    // 쿠폰 관련
    case COUPON_NOT_FOUND = "coupon_not_found";
    case COUPON_EXPIRED = "coupon_expired";
    case COUPON_ALREADY_USED = "coupon_already_used";
    case COUPON_APPLIED = "coupon_applied";

    // 프로모션 관련
    case PROMOTION_NOT_FOUND = "promotion_not_found";
    case PROMOTION_LIST_SUCCESS = "promotion_list_success";
    case PROMOTION_DETAIL_SUCCESS = "promotion_detail_success";
    case PROMOTION_CREATED = "promotion_created";
    case PROMOTION_UPDATED = "promotion_updated";
    case PROMOTION_DELETED = "promotion_deleted";
    case PROMOTION_ACTIVATED = "promotion_activated";
    case PROMOTION_DEACTIVATED = "promotion_deactivated";
    case PROMOTION_NOT_ACTIVE = "promotion_not_active";
    case PROMOTION_APPLIED = "promotion_applied";

    // 환불 관련
    case REFUND_REQUESTED = "refund_requested";
    case REFUND_APPROVED = "refund_approved";
    case REFUND_REJECTED = "refund_rejected";
    case REFUND_COMPLETED = "refund_completed";

    // PG 제공자 관련
    case PG_PROVIDER_NOT_FOUND = "pg_provider_not_found";
    case PG_PROVIDER_LIST_SUCCESS = "pg_provider_list_success";
    case PG_PROVIDER_DETAIL_SUCCESS = "pg_provider_detail_success";
    case PG_PROVIDER_CREATED = "pg_provider_created";
    case PG_PROVIDER_UPDATED = "pg_provider_updated";
    case PG_PROVIDER_DELETED = "pg_provider_deleted";
    case PG_PROVIDER_ACTIVATED = "pg_provider_activated";
    case PG_PROVIDER_DEACTIVATED = "pg_provider_deactivated";
    case PG_PROVIDER_ALREADY_EXISTS = "pg_provider_already_exists";
    case PG_PROVIDER_ALREADY_ACTIVE = "pg_provider_already_active";
    case PG_PROVIDER_ALREADY_INACTIVE = "pg_provider_already_inactive";

    /**
     * HTTP 상태 코드 반환
     */
    public function statusCode(): int
    {
        return match($this) {
            // 2xx - 성공
            self::SUCCESS => 200,
            self::CREATED => 201,
            self::UPDATED => 200,
            self::DELETED => 200,
            self::PRODUCT_LIST_SUCCESS => 200,
            self::PRODUCT_DETAIL_SUCCESS => 200,
            self::PRODUCT_CREATED => 201,
            self::PRODUCT_UPDATED => 200,
            self::PRODUCT_DELETED => 200,
            self::PRODUCT_STATUS_UPDATED => 200,
            self::ORDER_LIST_SUCCESS => 200,
            self::ORDER_DETAIL_SUCCESS => 200,
            self::ORDER_CREATED => 201,
            self::ORDER_CANCELLED => 200,
            self::ORDER_REFUNDED => 200,
            self::ORDER_STATUS_UPDATED => 200,
            self::PAYMENT_DETAIL_SUCCESS => 200,
            self::PAYMENT_SUCCESS => 200,
            self::PAYMENT_CANCELLED => 200,
            self::PAYMENT_REFUNDED => 200,
            self::USER_CREATED => 201,
            self::USER_UPDATED => 200,
            self::USER_DELETED => 200,
            self::COUPON_APPLIED => 200,
            self::PROMOTION_APPLIED => 200,
            self::REFUND_REQUESTED => 200,
            self::REFUND_APPROVED => 200,
            self::REFUND_COMPLETED => 200,
            self::PG_PROVIDER_LIST_SUCCESS => 200,
            self::PG_PROVIDER_DETAIL_SUCCESS => 200,
            self::PG_PROVIDER_CREATED => 201,
            self::PG_PROVIDER_UPDATED => 200,
            self::PG_PROVIDER_DELETED => 200,
            self::PG_PROVIDER_ACTIVATED => 200,
            self::PG_PROVIDER_DEACTIVATED => 200,

            // 4xx - 클라이언트 에러
            self::ERROR => 400,
            self::NOT_FOUND => 404,
            self::VALIDATION_FAILED => 422,
            self::UNAUTHORIZED => 401,
            self::FORBIDDEN => 403,
            self::PRODUCT_NOT_FOUND => 404,
            self::PRODUCT_OUT_OF_STOCK => 400,
            self::ORDER_NOT_FOUND => 404,
            self::PAYMENT_NOT_FOUND => 404,
            self::PAYMENT_FAILED => 400,
            self::PAYMENT_ALREADY_PROCESSED => 409,
            self::PAYMENT_AMOUNT_MISMATCH => 400,
            self::USER_NOT_FOUND => 404,
            self::COUPON_NOT_FOUND => 404,
            self::COUPON_EXPIRED => 400,
            self::COUPON_ALREADY_USED => 400,
            self::PROMOTION_NOT_FOUND => 404,
            self::PROMOTION_NOT_ACTIVE => 400,
            self::REFUND_REJECTED => 400,
            self::PG_PROVIDER_NOT_FOUND => 404,
            self::PG_PROVIDER_ALREADY_EXISTS => 409,
            self::PG_PROVIDER_ALREADY_ACTIVE => 400,
            self::PG_PROVIDER_ALREADY_INACTIVE => 400,

            // 5xx - 서버 에러
            self::SERVER_ERROR => 500,
        };
    }

    /**
     * 다국어 메시지 키 반환
     */
    public function messageKey(): string
    {
        return "messages.{$this->value}";
    }

    /**
     * 번역된 메시지 반환
     */
    public function message(): string
    {
        return __($this->messageKey());
    }
}
