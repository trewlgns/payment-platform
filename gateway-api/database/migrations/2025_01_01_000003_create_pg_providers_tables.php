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
        // pg_providers 테이블
        Schema::create("pg_providers", function (Blueprint $table) {
            $table->string("pg_provider_code", 50)->primary()->comment("PG 코드 (PRIMARY KEY)");
            $table->string("name", 100)->comment("PG사 명칭");
            $table->tinyInteger("is_active")->default(1)->comment("활성화 여부");
            $table->integer("priority")->default(0)->comment("우선순위");
            $table->timestamps();

            // 인덱스
            $table->index(["is_active", "priority"]);
        });

        // pg_credentials 테이블
        Schema::create("pg_credentials", function (Blueprint $table) {
            $table->id("credential_id")->comment("자격증명 ID (PRIMARY KEY)");
            $table->string("pg_provider_code", 50)->comment("PG 제공자 코드 (FOREIGN KEY)");
            $table->string("merchant_id", 100)->comment("가맹점 ID");
            $table->string("api_key_encrypted", 500)->comment("암호화된 API 키");
            $table->enum("environment", ["test", "production"])->default("test")->comment("환경");
            $table->tinyInteger("is_active")->default(1)->comment("활성화 여부");
            $table->timestamps();

            // 외래키
            $table->foreign("pg_provider_code")->references("pg_provider_code")->on("pg_providers")->onDelete("cascade");

            // 인덱스
            $table->index(["pg_provider_code", "environment"]);
            $table->index("is_active");
        });

        // pg_error_codes 테이블
        Schema::create("pg_error_codes", function (Blueprint $table) {
            $table->string("pg_provider_code", 50)->comment("PG 제공자 코드 (COMPOSITE PK, FOREIGN KEY)");
            $table->string("error_code", 50)->comment("PG 오류 코드 (COMPOSITE PK)");
            $table->string("error_message", 500)->comment("오류 메시지");
            $table->enum("error_category", ["network", "auth", "validation", "business"])->comment("오류 카테고리");
            $table->enum("action_type", ["retry", "manual", "ignore"])->comment("조치 유형");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 복합 PK
            $table->primary(["pg_provider_code", "error_code"]);

            // 외래키
            $table->foreign("pg_provider_code")->references("pg_provider_code")->on("pg_providers")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("pg_error_codes");
        Schema::dropIfExists("pg_credentials");
        Schema::dropIfExists("pg_providers");
    }
};
