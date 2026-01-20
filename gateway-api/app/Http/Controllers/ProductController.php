<?php

namespace App\Http\Controllers;

use App\Enums\ResponseMessage;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\ListProductsRequest;
use App\Http\Requests\Product\SearchProductsRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Product\UpdateProductStatusRequest;
use App\Services\Product\CreateProductService;
use App\Services\Product\DeleteProductService;
use App\Services\Product\GetProductService;
use App\Services\Product\ListProductsService;
use App\Services\Product\SearchProductsService;
use App\Services\Product\UpdateProductService;
use App\Services\Product\UpdateProductStatusService;
use Illuminate\Http\JsonResponse;

class ProductController extends BaseController
{
    /**
     * 상품 목록 조회
     *
     * GET /api/products
     */
    public function index(ListProductsRequest $request, ListProductsService $service): JsonResponse
    {
        $filters = $request->getFilters();
        $result = $service->handle($filters);

        return $this->success($result, ResponseMessage::PRODUCT_LIST_SUCCESS);
    }

    /**
     * 상품 상세 조회
     *
     * GET /api/products/{productCode}
     */
    public function show(string $productCode, GetProductService $service): JsonResponse
    {
        $product = $service->handle($productCode);

        return $this->success($product, ResponseMessage::PRODUCT_DETAIL_SUCCESS);
    }

    /**
     * 상품 생성
     *
     * POST /api/products
     */
    public function store(CreateProductRequest $request, CreateProductService $service): JsonResponse
    {
        $validated = $request->validated();
        $product = $service->handle($validated);

        return $this->success($product, ResponseMessage::PRODUCT_CREATED);
    }

    /**
     * 상품 정보 수정
     *
     * PUT /api/products/{productCode}
     */
    public function update(string $productCode, UpdateProductRequest $request, UpdateProductService $service): JsonResponse
    {
        $validated = $request->validated();
        $product = $service->handle($productCode, $validated);

        return $this->success($product, ResponseMessage::PRODUCT_UPDATED);
    }

    /**
     * 상품 상태 변경
     *
     * PATCH /api/products/{productCode}/status
     */
    public function updateStatus(
        string $productCode,
        UpdateProductStatusRequest $request,
        UpdateProductStatusService $service
    ): JsonResponse {
        $validated = $request->validated();
        $product = $service->handle($productCode, $validated["status"]);

        return $this->success($product, ResponseMessage::PRODUCT_STATUS_UPDATED);
    }

    /**
     * 상품 삭제
     *
     * DELETE /api/products/{productCode}
     */
    public function destroy(string $productCode, DeleteProductService $service): JsonResponse
    {
        $service->handle($productCode);

        return $this->success(null, ResponseMessage::PRODUCT_DELETED);
    }

    /**
     * 상품 검색 (자동완성)
     *
     * GET /api/products/search?q={keyword}&limit={limit}
     */
    public function search(SearchProductsRequest $request, SearchProductsService $service): JsonResponse
    {
        $params = $request->getSearchParams();
        $products = $service->handle($params);

        return $this->success($products, ResponseMessage::SUCCESS);
    }
}
