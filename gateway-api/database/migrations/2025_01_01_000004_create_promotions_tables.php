<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // promotions 테이블
        Schema::create("promotions", function (Blueprint $table) {
            $table->string("promotion_code", 50)->primary()->comment("프로모션 코드 (PRIMARY KEY)");
            $table->string("name", 200)->comment("프로모션 명칭");
            $table->enum("promotion_type", ["cart_discount", "product_discount", "shipping_discount", "coupon"])->comment("프로모션 유형");
            $table->enum("discount_type", ["percentage", "fixed_amount"])->comment("할인 유형");
            $table->decimal("discount_value", 10, 2)->comment("할인값 (%, 금액)");
            $table->timestamp("start_at")->comment("시작일시");
            $table->timestamp("end_at")->comment("종료일시");
            $table->tinyInteger("is_active")->default(1)->comment("활성화 여부");
            $table->timestamps();

            // 인덱스
            $table->index(["is_active", "start_at", "end_at"]);
        });

        // promotion_rules 테이블
        Schema::create("promotion_rules", function (Blueprint $table) {
            $table->id("rule_id")->comment("규칙 ID (PRIMARY KEY)");
            $table->string("promotion_code", 50)->comment("프로모션 코드 (FOREIGN KEY)");
            $table->enum("rule_type", ["min_amount", "max_discount", "target_product", "target_category"])->comment("규칙 유형");
            $table->json("rule_value")->comment("규칙 값 (JSON)");
            $table->integer("priority")->default(0)->comment("우선순위");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 외래키
            $table->foreign("promotion_code")->references("promotion_code")->on("promotions")->onDelete("cascade");

            // 인덱스
            $table->index(["promotion_code", "priority"]);
        });

        // coupons 테이블
        Schema::create("coupons", function (Blueprint $table) {
            $table->string("coupon_code", 50)->primary()->comment("쿠폰 코드 (PRIMARY KEY)");
            $table->string("promotion_code", 50)->comment("프로모션 코드 (FOREIGN KEY)");
            $table->integer("issued_count")->default(0)->comment("발급 수량");
            $table->integer("max_usage")->default(1)->comment("최대 사용 횟수");
            $table->timestamp("expires_at")->comment("만료일시");
            $table->enum("status", ["issued", "used", "expired", "cancelled"])->default("issued")->comment("쿠폰 상태");
            $table->timestamps();

            // 외래키
            $table->foreign("promotion_code")->references("promotion_code")->on("promotions")->onDelete("cascade");

            // 인덱스
            $table->index(["promotion_code", "status"]);
            $table->index("expires_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("coupons");
        Schema::dropIfExists("promotion_rules");
        Schema::dropIfExists("promotions");
    }
};
