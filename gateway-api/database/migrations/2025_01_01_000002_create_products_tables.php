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
        // products 테이블
        Schema::create("products", function (Blueprint $table) {
            $table->string("product_code", 100)->primary()->comment("상품 코드 (PRIMARY KEY)");
            $table->string("name", 200)->comment("상품명");
            $table->text("description")->nullable()->comment("상품 설명");
            $table->decimal("base_price", 10, 2)->comment("기본 가격");
            $table->string("category", 50)->comment("카테고리");
            $table->enum("status", ["active", "inactive", "soldout"])->default("active")->comment("상품 상태");
            $table->timestamps();

            // 인덱스
            $table->index(["category", "status"]);
            $table->index("status");
        });

        // product_stocks 테이블
        Schema::create("product_stocks", function (Blueprint $table) {
            $table->string("product_code", 100)->primary()->comment("상품 코드 (PRIMARY KEY, FOREIGN KEY)");
            $table->integer("quantity")->default(0)->comment("재고 수량");
            $table->integer("reserved_quantity")->default(0)->comment("예약 수량");
            $table->timestamp("updated_at")->useCurrent()->useCurrentOnUpdate()->comment("수정일시");

            // 외래키
            $table->foreign("product_code")->references("product_code")->on("products")->onDelete("cascade");
        });

        // product_options 테이블
        Schema::create("product_options", function (Blueprint $table) {
            $table->string("product_code", 100)->comment("상품 코드 (COMPOSITE PK, FOREIGN KEY)");
            $table->string("option_name", 50)->comment("옵션명 (COMPOSITE PK)");
            $table->string("option_value", 50)->comment("옵션값 (COMPOSITE PK)");
            $table->decimal("additional_price", 10, 2)->default(0)->comment("추가 금액");
            $table->timestamp("created_at")->useCurrent()->comment("생성일시");

            // 복합 PK
            $table->primary(["product_code", "option_name", "option_value"]);

            // 외래키
            $table->foreign("product_code")->references("product_code")->on("products")->onDelete("cascade");

            // 인덱스
            $table->index("product_code");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("product_options");
        Schema::dropIfExists("product_stocks");
        Schema::dropIfExists("products");
    }
};
