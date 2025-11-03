<?php

/**
 * 메시지 키 및 HTTP 상태 코드 마스터 설정
 *
 * 이 파일에서 모든 메시지 키와 상태 코드를 중앙 관리합니다.
 * 각 언어별 파일(lang/ko/messages.php, lang/en/messages.php)은
 * 이 파일에 정의된 키를 기준으로 번역만 제공합니다.
 */
return [
    // 공통 메시지
    "success" => 200,
    "error" => 400,
    "not_found" => 404,
    "created" => 201,
    "updated" => 200,
    "deleted" => 200,
    "validation_failed" => 422,
    "unauthorized" => 401,
    "forbidden" => 403,
    "server_error" => 500,

    // 상품 관련
    "product_not_found" => 404,
    "product_list_success" => 200,
    "product_detail_success" => 200,
    "product_created" => 201,
    "product_updated" => 200,
    "product_deleted" => 200,
    "product_status_updated" => 200,
    "product_out_of_stock" => 400,

    // 주문 관련
    "order_not_found" => 404,
    "order_list_success" => 200,
    "order_detail_success" => 200,
    "order_created" => 201,
    "order_cancelled" => 200,
    "order_cannot_cancel" => 400,
    "order_status_updated" => 200,

    // 결제 관련
    "payment_not_found" => 404,
    "payment_success" => 200,
    "payment_failed" => 400,
    "payment_cancelled" => 200,
    "payment_refunded" => 200,
    "payment_already_processed" => 409,
    "payment_amount_mismatch" => 400,

    // 사용자 관련
    "user_not_found" => 404,
    "user_created" => 201,
    "user_updated" => 200,
    "user_deleted" => 200,

    // 쿠폰 관련
    "coupon_not_found" => 404,
    "coupon_expired" => 400,
    "coupon_already_used" => 400,
    "coupon_applied" => 200,

    // 프로모션 관련
    "promotion_not_found" => 404,
    "promotion_not_active" => 400,
    "promotion_applied" => 200,

    // 환불 관련
    "refund_requested" => 200,
    "refund_approved" => 200,
    "refund_rejected" => 400,
    "refund_completed" => 200,
];
