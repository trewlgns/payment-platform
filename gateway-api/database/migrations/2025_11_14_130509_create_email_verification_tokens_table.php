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
        Schema::create("email_verification_tokens", function (Blueprint $table) {
            $table->string("token_hash", 255)->primary()->comment("토큰 해시 (PRIMARY KEY)");
            $table->string("email", 255)->comment("이메일 (FOREIGN KEY)");
            $table->timestamp("expires_at")->comment("만료 시각 (24시간)");
            $table->timestamp("verified_at")->nullable()->comment("인증 완료 시각");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 외래키
            $table->foreign("email")->references("email")->on("users")->onDelete("cascade");

            // 인덱스
            $table->index("email");
            $table->index("expires_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("email_verification_tokens");
    }
};
