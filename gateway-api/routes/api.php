<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PgProviderController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\StatisticsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// 헬스 체크
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String()
    ]);
});

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('users')->group(function () {
    // 사용자 목록 조회 (페이지네이션, 필터링)
    // GET /api/users?status=active&page=1&per_page=20
    Route::get('/', [UserController::class, 'index']);

    // 사용자 생성 (회원가입)
    // POST /api/users
    Route::post('/', [UserController::class, 'store']);

    // 사용자 상세 조회
    // GET /api/users/{email}
    Route::get('/{email}', [UserController::class, 'show']);

    // 사용자 정보 수정
    // PUT /api/users/{email}
    Route::put('/{email}', [UserController::class, 'update']);

    // 사용자 삭제 (Soft Delete)
    // DELETE /api/users/{email}
    Route::delete('/{email}', [UserController::class, 'destroy']);

    // 사용자 상태 변경
    // PATCH /api/users/{email}/status
    Route::patch('/{email}/status', [UserController::class, 'updateStatus']);

    // 이메일 인증 토큰 발송
    // POST /api/users/{email}/send-verification
    Route::post('/{email}/send-verification', [UserController::class, 'sendVerification']);

    // 이메일 인증 처리
    // POST /api/users/{email}/verify-email
    Route::post('/{email}/verify-email', [UserController::class, 'verifyEmail']);
});

/*
|--------------------------------------------------------------------------
| Product API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{productCode}', [ProductController::class, 'show']);
    Route::put('/{productCode}', [ProductController::class, 'update']);
    Route::patch('/{productCode}/status', [ProductController::class, 'updateStatus']);
    Route::delete('/{productCode}', [ProductController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Order API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('orders')->group(function () {
    // 주문 목록 조회 (페이지네이션, 필터링)
    // GET /api/orders?status=paid&customer_email=test@example.com&page=1&per_page=20
    Route::get('/', [OrderController::class, 'index']);

    // 주문 생성
    // POST /api/orders
    Route::post('/', [OrderController::class, 'store']);

    // 주문 상세 조회
    // GET /api/orders/{orderId}
    Route::get('/{orderId}', [OrderController::class, 'show']);

    // 주문 상태 변경
    // PATCH /api/orders/{orderId}/status
    Route::patch('/{orderId}/status', [OrderController::class, 'updateStatus']);

    // 주문 취소
    // POST /api/orders/{orderId}/cancel
    Route::post('/{orderId}/cancel', [OrderController::class, 'cancel']);

    // 주문 환불
    // POST /api/orders/{orderId}/refund
    Route::post('/{orderId}/refund', [OrderController::class, 'refund']);
});

/*
|--------------------------------------------------------------------------
| Payment API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('payments')->group(function () {
    // 결제 목록 조회 (페이지네이션, 필터링)
    // GET /api/payments?status=approved&order_id=1&page=1&per_page=20
    Route::get('/', [PaymentController::class, 'index']);

    // 결제 생성
    // POST /api/payments
    Route::post('/', [PaymentController::class, 'store']);

    // 결제 상세 조회
    // GET /api/payments/{paymentId}
    Route::get('/{paymentId}', [PaymentController::class, 'show']);

    // 결제 취소
    // POST /api/payments/{paymentId}/cancel
    Route::post('/{paymentId}/cancel', [PaymentController::class, 'cancel']);

    // 결제 환불
    // POST /api/payments/{paymentId}/refund
    Route::post('/{paymentId}/refund', [PaymentController::class, 'refund']);
});

/*
|--------------------------------------------------------------------------
| PG Provider API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('pg-providers')->group(function () {
    // PG 제공자 목록 조회 (필터링)
    // GET /api/pg-providers?is_active=1
    Route::get('/', [PgProviderController::class, 'index']);

    // PG 제공자 생성
    // POST /api/pg-providers
    Route::post('/', [PgProviderController::class, 'store']);

    // PG 제공자 상세 조회
    // GET /api/pg-providers/{pgProviderCode}
    Route::get('/{pgProviderCode}', [PgProviderController::class, 'show']);

    // PG 제공자 정보 수정
    // PUT /api/pg-providers/{pgProviderCode}
    Route::put('/{pgProviderCode}', [PgProviderController::class, 'update']);

    // PG 제공자 활성화
    // PATCH /api/pg-providers/{pgProviderCode}/activate
    Route::patch('/{pgProviderCode}/activate', [PgProviderController::class, 'activate']);

    // PG 제공자 비활성화
    // PATCH /api/pg-providers/{pgProviderCode}/deactivate
    Route::patch('/{pgProviderCode}/deactivate', [PgProviderController::class, 'deactivate']);

    // PG 제공자 삭제
    // DELETE /api/pg-providers/{pgProviderCode}
    Route::delete('/{pgProviderCode}', [PgProviderController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Promotion API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('promotions')->group(function () {
    // 프로모션 목록 조회 (페이지네이션, 필터링)
    // GET /api/promotions?is_active=1&promotion_type=cart_discount&page=1&per_page=20
    Route::get('/', [PromotionController::class, 'index']);

    // 프로모션 생성
    // POST /api/promotions
    Route::post('/', [PromotionController::class, 'store']);

    // 프로모션 상세 조회
    // GET /api/promotions/{promotionCode}
    Route::get('/{promotionCode}', [PromotionController::class, 'show']);

    // 프로모션 정보 수정
    // PUT /api/promotions/{promotionCode}
    Route::put('/{promotionCode}', [PromotionController::class, 'update']);

    // 프로모션 활성화
    // PATCH /api/promotions/{promotionCode}/activate
    Route::patch('/{promotionCode}/activate', [PromotionController::class, 'activate']);

    // 프로모션 비활성화
    // PATCH /api/promotions/{promotionCode}/deactivate
    Route::patch('/{promotionCode}/deactivate', [PromotionController::class, 'deactivate']);

    // 프로모션 삭제
    // DELETE /api/promotions/{promotionCode}
    Route::delete('/{promotionCode}', [PromotionController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Coupon API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('coupons')->group(function () {
    // 쿠폰 목록 조회 (페이지네이션, 필터링)
    // GET /api/coupons?status=issued&promotion_code=PROMO2024&page=1&per_page=20
    Route::get('/', [CouponController::class, 'index']);

    // 쿠폰 발급
    // POST /api/coupons
    Route::post('/', [CouponController::class, 'store']);

    // 쿠폰 상세 조회
    // GET /api/coupons/{couponCode}
    Route::get('/{couponCode}', [CouponController::class, 'show']);

    // 쿠폰 사용
    // POST /api/coupons/{couponCode}/use
    Route::post('/{couponCode}/use', [CouponController::class, 'use']);

    // 쿠폰 만료
    // PATCH /api/coupons/{couponCode}/expire
    Route::patch('/{couponCode}/expire', [CouponController::class, 'expire']);
});

/*
|--------------------------------------------------------------------------
| Refund API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('refunds')->group(function () {
    // 환불 요청 목록 조회 (페이지네이션, 필터링)
    // GET /api/refunds?status=requested&order_id=1&payment_id=1&page=1&per_page=20
    Route::get('/', [RefundController::class, 'index']);

    // 환불 요청 생성
    // POST /api/refunds
    Route::post('/', [RefundController::class, 'store']);

    // 환불 요청 상세 조회
    // GET /api/refunds/{refundRequestId}
    Route::get('/{refundRequestId}', [RefundController::class, 'show']);

    // 환불 요청 승인
    // PATCH /api/refunds/{refundRequestId}/approve
    Route::patch('/{refundRequestId}/approve', [RefundController::class, 'approve']);

    // 환불 요청 거부
    // PATCH /api/refunds/{refundRequestId}/reject
    Route::patch('/{refundRequestId}/reject', [RefundController::class, 'reject']);
});

/*
|--------------------------------------------------------------------------
| Webhook API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('webhooks')->group(function () {
    // Toss Payments 웹훅 수신
    // POST /api/webhooks/toss
    Route::post('/toss', [WebhookController::class, 'handleToss']);

    // Kakao Pay 웹훅 수신
    // POST /api/webhooks/kakao
    Route::post('/kakao', [WebhookController::class, 'handleKakao']);

    // Mock PG 웹훅 수신 (테스트용)
    // POST /api/webhooks/mock
    Route::post('/mock', [WebhookController::class, 'handleMock']);
});

/*
|--------------------------------------------------------------------------
| Statistics API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('statistics')->group(function () {
    // 일별 매출 통계 조회
    // GET /api/statistics/daily-sales?start_date=2025-01-01&end_date=2025-01-31
    Route::get('/daily-sales', [StatisticsController::class, 'getDailySales']);

    // 일별 PG 통계 조회
    // GET /api/statistics/daily-pg?start_date=2025-01-01&end_date=2025-01-31&pg_provider_code=TOSS
    Route::get('/daily-pg', [StatisticsController::class, 'getDailyPg']);

    // 고객 세그먼트 통계 조회
    // GET /api/statistics/customer-segment?stats_date=2025-01-01
    Route::get('/customer-segment', [StatisticsController::class, 'getCustomerSegment']);

    // 프로모션 성과 통계 조회
    // GET /api/statistics/promotion-performance?start_date=2025-01-01&end_date=2025-01-31&promotion_code=PROMO2024
    Route::get('/promotion-performance', [StatisticsController::class, 'getPromotionPerformance']);
});
