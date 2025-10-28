<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // orders 테이블
        Schema::create("orders", function (Blueprint $table) {
            $table->id("order_id")->comment("주문 ID (PRIMARY KEY)");
            $table->string("customer_email", 255)->comment("고객 이메일 (FOREIGN KEY)");
            $table->decimal("total_amount", 10, 2)->comment("총 주문 금액");
            $table->decimal("discount_amount", 10, 2)->default(0)->comment("할인 금액");
            $table->decimal("final_amount", 10, 2)->comment("최종 결제 금액");
            $table->enum("status", ["pending", "confirmed", "paid", "preparing", "shipped", "delivered", "cancelled", "refunded"])->default("pending")->comment("주문 상태");
            $table->timestamp("ordered_at")->useCurrent()->comment("주문일시");
            $table->timestamps();

            // 외래키
            $table->foreign("customer_email")->references("email")->on("users")->onDelete("cascade");

            // 인덱스
            $table->index(["customer_email", "ordered_at"]);
            $table->index(["status", "ordered_at"]);
        });

        // order_items 테이블
        Schema::create("order_items", function (Blueprint $table) {
            $table->id("order_item_id")->comment("주문 항목 ID (PRIMARY KEY)");
            $table->unsignedBigInteger("order_id")->comment("주문 ID (FOREIGN KEY)");
            $table->string("product_code", 100)->comment("상품 코드 (FOREIGN KEY)");
            $table->string("option_name", 50)->nullable()->comment("옵션명");
            $table->string("option_value", 50)->nullable()->comment("옵션값");
            $table->integer("quantity")->comment("주문 수량");
            $table->decimal("unit_price", 10, 2)->comment("단가");
            $table->decimal("subtotal", 10, 2)->comment("소계");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 외래키
            $table->foreign("order_id")->references("order_id")->on("orders")->onDelete("cascade");
            $table->foreign("product_code")->references("product_code")->on("products")->onDelete("restrict");

            // 인덱스
            $table->index("order_id");
            $table->index("product_code");
        });

        // order_status_history 테이블
        Schema::create("order_status_history", function (Blueprint $table) {
            $table->id("history_id")->comment("이력 ID (PRIMARY KEY)");
            $table->unsignedBigInteger("order_id")->comment("주문 ID (FOREIGN KEY)");
            $table->enum("from_status", ["pending", "confirmed", "paid", "preparing", "shipped", "delivered", "cancelled", "refunded"])->comment("이전 상태");
            $table->enum("to_status", ["pending", "confirmed", "paid", "preparing", "shipped", "delivered", "cancelled", "refunded"])->comment("변경 상태");
            $table->string("changed_by", 255)->comment("변경자 이메일 (FOREIGN KEY)");
            $table->text("reason")->nullable()->comment("변경 사유");
            $table->timestamp("changed_at")->useCurrent()->comment("변경일시");

            // 외래키
            $table->foreign("order_id")->references("order_id")->on("orders")->onDelete("cascade");
            $table->foreign("changed_by")->references("email")->on("users")->onDelete("restrict");

            // 인덱스
            $table->index(["order_id", "changed_at"]);
        });

        // coupon_redemptions 테이블 (주문과 쿠폰 연결)
        Schema::create("coupon_redemptions", function (Blueprint $table) {
            $table->id("redemption_id")->comment("사용 ID (PRIMARY KEY)");
            $table->string("coupon_code", 50)->comment("쿠폰 코드 (FOREIGN KEY)");
            $table->string("customer_email", 255)->comment("고객 이메일 (FOREIGN KEY)");
            $table->unsignedBigInteger("order_id")->comment("주문 ID (FOREIGN KEY)");
            $table->timestamp("redeemed_at")->useCurrent()->comment("사용일시");
            $table->timestamp("cancelled_at")->nullable()->comment("취소일시");
            $table->enum("status", ["redeemed", "cancelled"])->default("redeemed")->comment("상태");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 외래키
            $table->foreign("coupon_code")->references("coupon_code")->on("coupons")->onDelete("restrict");
            $table->foreign("customer_email")->references("email")->on("users")->onDelete("cascade");
            $table->foreign("order_id")->references("order_id")->on("orders")->onDelete("cascade");

            // 유니크 제약
            $table->unique(["coupon_code", "order_id"]);

            // 인덱스
            $table->index(["coupon_code", "customer_email"]);
            $table->index("order_id");
            $table->index("redeemed_at");
        });

        // order_promotions 테이블 (주문에 적용된 프로모션)
        Schema::create("order_promotions", function (Blueprint $table) {
            $table->id("order_promotion_id")->comment("주문 프로모션 ID (PRIMARY KEY)");
            $table->unsignedBigInteger("order_id")->comment("주문 ID (FOREIGN KEY)");
            $table->string("promotion_code", 50)->comment("프로모션 코드 (FOREIGN KEY)");
            $table->string("coupon_code", 50)->nullable()->comment("쿠폰 코드 (FOREIGN KEY)");
            $table->decimal("discount_amount", 10, 2)->comment("적용된 할인 금액");
            $table->timestamp("applied_at")->useCurrent()->comment("적용일시");

            // 외래키
            $table->foreign("order_id")->references("order_id")->on("orders")->onDelete("cascade");
            $table->foreign("promotion_code")->references("promotion_code")->on("promotions")->onDelete("restrict");
            $table->foreign("coupon_code")->references("coupon_code")->on("coupons")->onDelete("restrict");

            // 인덱스
            $table->index("order_id");
            $table->index(["promotion_code", "applied_at"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("order_promotions");
        Schema::dropIfExists("coupon_redemptions");
        Schema::dropIfExists("order_status_history");
        Schema::dropIfExists("order_items");
        Schema::dropIfExists("orders");
    }
};
