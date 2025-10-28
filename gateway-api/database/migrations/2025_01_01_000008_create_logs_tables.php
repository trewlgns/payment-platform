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
        // webhook_events 테이블
        Schema::create("webhook_events", function (Blueprint $table) {
            $table->id("webhook_event_id")->comment("웹훅 이벤트 ID (PRIMARY KEY)");
            $table->string("pg_provider_code", 50)->comment("PG 제공자 코드 (FOREIGN KEY)");
            $table->string("event_type", 100)->comment("이벤트 유형 (결제 완료, 환불 완료 등)");
            $table->json("payload")->comment("Webhook 페이로드");
            $table->enum("status", ["received", "processing", "processed", "failed"])->default("received")->comment("처리 상태");
            $table->timestamp("received_at")->useCurrent()->comment("수신일시");
            $table->timestamp("processed_at")->nullable()->comment("처리일시");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 외래키
            $table->foreign("pg_provider_code")->references("pg_provider_code")->on("pg_providers")->onDelete("restrict");

            // 인덱스
            $table->index(["pg_provider_code", "received_at"]);
            $table->index("status");
            $table->index("event_type");
        });

        // api_call_logs 테이블
        Schema::create("api_call_logs", function (Blueprint $table) {
            $table->id("api_call_log_id")->comment("API 호출 로그 ID (PRIMARY KEY)");
            $table->string("pg_provider_code", 50)->comment("PG 제공자 코드 (FOREIGN KEY)");
            $table->string("endpoint", 500)->comment("API 엔드포인트");
            $table->json("request_body")->nullable()->comment("요청 바디");
            $table->json("response_body")->nullable()->comment("응답 바디");
            $table->integer("status_code")->comment("HTTP 상태 코드");
            $table->integer("duration_ms")->comment("소요 시간(ms)");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 외래키
            $table->foreign("pg_provider_code")->references("pg_provider_code")->on("pg_providers")->onDelete("restrict");

            // 인덱스
            $table->index(["pg_provider_code", "created_at"]);
            $table->index("status_code");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("api_call_logs");
        Schema::dropIfExists("webhook_events");
    }
};
