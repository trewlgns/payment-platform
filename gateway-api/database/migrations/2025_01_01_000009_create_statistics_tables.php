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
        // daily_sales_stats 테이블
        Schema::create("daily_sales_stats", function (Blueprint $table) {
            $table->date("stats_date")->primary()->comment("통계 날짜 (PRIMARY KEY)");
            $table->integer("total_orders")->default(0)->comment("총 주문 수");
            $table->decimal("total_amount", 15, 2)->default(0)->comment("총 매출액");
            $table->decimal("total_discount", 15, 2)->default(0)->comment("총 할인액");
            $table->decimal("net_amount", 15, 2)->default(0)->comment("순매출액");
            $table->decimal("avg_order_value", 10, 2)->default(0)->comment("평균 주문 금액");
            $table->timestamps();
        });

        // daily_pg_stats 테이블
        Schema::create("daily_pg_stats", function (Blueprint $table) {
            $table->date("stats_date")->comment("통계 날짜 (COMPOSITE PK)");
            $table->string("pg_provider_code", 50)->comment("PG 제공자 코드 (COMPOSITE PK, FOREIGN KEY)");
            $table->integer("success_count")->default(0)->comment("성공 건수");
            $table->integer("fail_count")->default(0)->comment("실패 건수");
            $table->decimal("total_amount", 15, 2)->default(0)->comment("총 거래액");
            $table->integer("avg_response_time_ms")->default(0)->comment("평균 응답 시간");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 복합 PK
            $table->primary(["stats_date", "pg_provider_code"]);

            // 외래키
            $table->foreign("pg_provider_code")->references("pg_provider_code")->on("pg_providers")->onDelete("restrict");
        });

        // customer_segment_stats 테이블
        Schema::create("customer_segment_stats", function (Blueprint $table) {
            $table->date("stats_date")->comment("통계 날짜 (COMPOSITE PK)");
            $table->string("segment", 50)->comment("고객 세그먼트 (COMPOSITE PK: 신규, 일반, VIP)");
            $table->integer("customer_count")->default(0)->comment("고객 수");
            $table->integer("total_orders")->default(0)->comment("총 주문 수");
            $table->decimal("total_amount", 15, 2)->default(0)->comment("총 매출액");
            $table->decimal("repeat_rate", 5, 2)->default(0)->comment("재구매율");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 복합 PK
            $table->primary(["stats_date", "segment"]);
        });

        // promotion_performance_stats 테이블
        Schema::create("promotion_performance_stats", function (Blueprint $table) {
            $table->date("stats_date")->comment("통계 날짜 (COMPOSITE PK)");
            $table->string("promotion_code", 50)->comment("프로모션 코드 (COMPOSITE PK, FOREIGN KEY)");
            $table->integer("usage_count")->default(0)->comment("사용 건수");
            $table->decimal("total_discount_amount", 15, 2)->default(0)->comment("총 할인액");
            $table->decimal("revenue_contribution", 15, 2)->default(0)->comment("매출 기여도");
            $table->decimal("roi", 10, 2)->default(0)->comment("투자 대비 효과");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 복합 PK
            $table->primary(["stats_date", "promotion_code"]);

            // 외래키
            $table->foreign("promotion_code")->references("promotion_code")->on("promotions")->onDelete("restrict");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("promotion_performance_stats");
        Schema::dropIfExists("customer_segment_stats");
        Schema::dropIfExists("daily_pg_stats");
        Schema::dropIfExists("daily_sales_stats");
    }
};
