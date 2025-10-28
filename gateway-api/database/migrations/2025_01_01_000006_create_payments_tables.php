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
        // payments 테이블
        Schema::create("payments", function (Blueprint $table) {
            $table->id("payment_id")->comment("결제 ID (PRIMARY KEY)");
            $table->unsignedBigInteger("order_id")->comment("주문 ID (FOREIGN KEY)");
            $table->string("pg_provider_code", 50)->comment("PG 제공자 코드 (FOREIGN KEY)");
            $table->decimal("amount", 10, 2)->comment("결제 금액");
            $table->enum("status", ["pending", "approved", "failed", "cancelled", "refunded"])->default("pending")->comment("결제 상태");
            $table->enum("payment_method", ["card", "bank_transfer", "virtual_account", "mobile"])->comment("결제 수단");
            $table->string("idempotency_key", 255)->unique()->comment("멱등성 키 (UNIQUE)");
            $table->timestamp("paid_at")->nullable()->comment("결제 완료 시각");
            $table->timestamps();

            // 외래키
            $table->foreign("order_id")->references("order_id")->on("orders")->onDelete("restrict");
            $table->foreign("pg_provider_code")->references("pg_provider_code")->on("pg_providers")->onDelete("restrict");

            // 인덱스
            $table->index("order_id");
            $table->index(["pg_provider_code", "status"]);
            $table->index("paid_at");
        });

        // payment_transactions 테이블
        Schema::create("payment_transactions", function (Blueprint $table) {
            $table->id("transaction_id")->comment("트랜잭션 ID (PRIMARY KEY)");
            $table->unsignedBigInteger("payment_id")->comment("결제 ID (FOREIGN KEY)");
            $table->string("pg_transaction_id", 255)->unique()->comment("PG 트랜잭션 ID (UNIQUE)");
            $table->json("request_payload")->nullable()->comment("요청 데이터");
            $table->json("response_payload")->nullable()->comment("응답 데이터");
            $table->enum("status", ["requested", "approved", "failed", "cancelled"])->default("requested")->comment("트랜잭션 상태");
            $table->integer("response_time_ms")->default(0)->comment("응답 시간(ms)");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 외래키
            $table->foreign("payment_id")->references("payment_id")->on("payments")->onDelete("cascade");

            // 인덱스
            $table->index(["payment_id", "created_at"]);
            $table->index("status");
        });

        // idempotency_keys 테이블
        Schema::create("idempotency_keys", function (Blueprint $table) {
            $table->string("key_hash", 255)->primary()->comment("키 해시 (PRIMARY KEY)");
            $table->unsignedBigInteger("payment_id")->nullable()->comment("결제 ID (FOREIGN KEY)");
            $table->timestamp("expires_at")->comment("만료 시각 (24시간)");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 외래키
            $table->foreign("payment_id")->references("payment_id")->on("payments")->onDelete("set null");

            // 인덱스
            $table->index("expires_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("idempotency_keys");
        Schema::dropIfExists("payment_transactions");
        Schema::dropIfExists("payments");
    }
};
