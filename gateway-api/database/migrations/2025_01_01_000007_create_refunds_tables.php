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
        // refund_requests 테이블
        Schema::create("refund_requests", function (Blueprint $table) {
            $table->id("refund_request_id")->comment("환불 요청 ID (PRIMARY KEY)");
            $table->unsignedBigInteger("order_id")->comment("주문 ID (FOREIGN KEY)");
            $table->unsignedBigInteger("payment_id")->comment("결제 ID (FOREIGN KEY)");
            $table->decimal("requested_amount", 10, 2)->comment("환불 요청 금액");
            $table->text("reason")->nullable()->comment("환불 사유");
            $table->enum("status", ["requested", "approved", "processing", "completed", "rejected"])->default("requested")->comment("환불 상태");
            $table->timestamp("requested_at")->useCurrent()->comment("요청일시");
            $table->timestamp("processed_at")->nullable()->comment("처리일시");
            $table->timestamps();

            // 외래키
            $table->foreign("order_id")->references("order_id")->on("orders")->onDelete("restrict");
            $table->foreign("payment_id")->references("payment_id")->on("payments")->onDelete("restrict");

            // 인덱스
            $table->index(["order_id", "requested_at"]);
            $table->index("payment_id");
            $table->index("status");
        });

        // refund_transactions 테이블
        Schema::create("refund_transactions", function (Blueprint $table) {
            $table->id("refund_transaction_id")->comment("환불 트랜잭션 ID (PRIMARY KEY)");
            $table->unsignedBigInteger("refund_request_id")->comment("환불 요청 ID (FOREIGN KEY)");
            $table->string("pg_transaction_id", 255)->unique()->comment("PG 환불 트랜잭션 ID (UNIQUE)");
            $table->decimal("refunded_amount", 10, 2)->comment("실제 환불 금액");
            $table->enum("status", ["requested", "completed", "failed"])->default("requested")->comment("트랜잭션 상태");
            $table->json("response_payload")->nullable()->comment("PG 응답 데이터");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 외래키
            $table->foreign("refund_request_id")->references("refund_request_id")->on("refund_requests")->onDelete("cascade");

            // 인덱스
            $table->index(["refund_request_id", "created_at"]);
            $table->index("status");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("refund_transactions");
        Schema::dropIfExists("refund_requests");
    }
};
