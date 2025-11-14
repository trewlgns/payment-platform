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
        Schema::create("users", function (Blueprint $table) {
            $table->string("email", 255)->primary()->comment("이메일 (PRIMARY KEY)");
            $table->string("password_hash", 255)->comment("암호화된 비밀번호");
            $table->string("name", 100)->comment("회원명");
            $table->string("phone", 20)->comment("연락처");
            $table->enum("status", ["active", "inactive", "banned"])->default("active")->comment("회원 상태");
            $table->timestamp("email_verified_at")->nullable()->comment("이메일 인증 시각");
            $table->timestamp("deleted_at")->nullable()->comment("삭제 시각 (Soft Delete)");
            $table->timestamps();

            // 인덱스
            $table->index("status");
            $table->index("created_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("users");
    }
};
