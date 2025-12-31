@extends('layouts.shop')

@section('title', 'Products')

@section('content')
<div class="container py-5">
    <!-- 페이지 헤더 -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-3">All Products</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- 필터 및 정렬 -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary active">All</button>
                <button type="button" class="btn btn-outline-secondary">Electronics</button>
                <button type="button" class="btn btn-outline-secondary">Wearables</button>
                <button type="button" class="btn btn-outline-secondary">Accessories</button>
            </div>
        </div>
        <div class="col-md-6 text-end">
            <select class="form-select d-inline-block w-auto">
                <option selected>Sort by: Latest</option>
                <option value="1">Price: Low to High</option>
                <option value="2">Price: High to Low</option>
                <option value="3">Name: A to Z</option>
            </select>
        </div>
    </div>

    <!-- 상품 목록 -->
    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-md-6 col-lg-4">
            <div class="card product-card h-100 shadow-hover">
                <!-- 상품 이미지 -->
                <div class="product-card__image-wrapper position-relative">
                    <a href="/products/{{ $product['product_code'] }}">
                        <img
                            src="{{ $product['image_url'] ?? 'https://via.placeholder.com/300x300?text=Product' }}"
                            class="card-img-top"
                            alt="{{ $product['name'] }}"
                        >
                    </a>

                    @if(!empty($product['badge']))
                    <span class="badge bg-{{ $product['badge_color'] ?? 'primary' }} position-absolute top-0 start-0 m-2">
                        {{ $product['badge'] }}
                    </span>
                    @endif

                    <!-- 빠른 보기 버튼 -->
                    <div class="product-card__overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                        <a href="/products/{{ $product['product_code'] }}" class="btn btn-light btn-sm">
                            <i class="bi bi-eye me-1"></i> View Details
                        </a>
                    </div>
                </div>

                <!-- 상품 정보 -->
                <div class="card-body">
                    <!-- 카테고리 -->
                    <div class="mb-2">
                        <span class="badge bg-secondary small">{{ $product['category'] ?? 'Category' }}</span>
                    </div>

                    <!-- 상품명 -->
                    <h5 class="card-title">
                        <a href="/products/{{ $product['product_code'] }}" class="text-decoration-none text-dark">
                            {{ $product['name'] }}
                        </a>
                    </h5>

                    <!-- 상품 설명 -->
                    <p class="card-text text-muted small mb-3">
                        {{ Str::limit($product['description'] ?? '', 80) }}
                    </p>

                    <!-- 가격 및 버튼 -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="product-card__price">
                            @if(!empty($product['discount_price']))
                            <div class="d-flex flex-column">
                                <span class="text-muted text-decoration-line-through small">
                                    ${{ number_format($product['base_price'], 2) }}
                                </span>
                                <span class="h5 mb-0 text-primary fw-bold">
                                    ${{ number_format($product['discount_price'], 2) }}
                                </span>
                            </div>
                            @else
                            <span class="h5 mb-0 text-primary fw-bold">
                                ${{ number_format($product['base_price'], 2) }}
                            </span>
                            @endif
                        </div>

                        <button class="btn btn-outline-primary btn-sm" onclick="addToCart('{{ $product['product_code'] }}')">
                            <i class="bi bi-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>
                No products found.
            </div>
        </div>
        @endforelse
    </div>

    <!-- 페이지네이션 (데모용) -->
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Product pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addToCart(productCode) {
    alert(`Added product ${productCode} to cart!`);
    // TODO: 실제 장바구니 기능 구현
}
</script>
@endpush

@push('styles')
<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e0e0e0;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.product-card__image-wrapper {
    overflow: hidden;
    aspect-ratio: 1 / 1;
}

.product-card__image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-card__image-wrapper img {
    transform: scale(1.1);
}

.product-card__overlay {
    opacity: 0;
    background-color: rgba(0, 0, 0, 0.5);
    transition: opacity 0.3s ease;
}

.product-card:hover .product-card__overlay {
    opacity: 1;
}

.shadow-hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
</style>
@endpush
