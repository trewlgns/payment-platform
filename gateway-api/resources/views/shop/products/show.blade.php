@extends('layouts.shop')

@section('title', $product['name'] ?? '상품 상세')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">홈</a></li>
            <li class="breadcrumb-item"><a href="/products">상품</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product['name'] ?? '상품' }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image Gallery -->
        <div class="col-lg-6 mb-4">
            <div class="product-detail-gallery">
                <!-- Main Image -->
                <div class="product-detail-gallery__main mb-3">
                    <img
                        id="mainProductImage"
                        src="{{ $product['image_url'] ?? 'https://via.placeholder.com/600x600?text=Product+Image' }}"
                        alt="{{ $product['name'] ?? 'Product' }}"
                        class="img-fluid rounded"
                    >
                    @if(!empty($product['badge']))
                    <span class="badge bg-{{ $product['badge_color'] ?? 'primary' }} product-detail-gallery__badge">
                        {{ $product['badge'] }}
                    </span>
                    @endif
                </div>

                <!-- Thumbnail Images -->
                <div class="product-detail-gallery__thumbnails">
                    <div class="row g-2">
                        @php
                            $thumbnails = $product['images'] ?? [
                                $product['image_url'] ?? 'https://via.placeholder.com/150?text=1',
                                'https://via.placeholder.com/150?text=2',
                                'https://via.placeholder.com/150?text=3',
                                'https://via.placeholder.com/150?text=4'
                            ];
                        @endphp
                        @foreach($thumbnails as $index => $thumbnail)
                        <div class="col-3">
                            <img
                                src="{{ $thumbnail }}"
                                alt="Thumbnail {{ $index + 1 }}"
                                class="img-fluid rounded product-detail-gallery__thumbnail {{ $index === 0 ? 'active' : '' }}"
                                data-full-image="{{ $thumbnail }}"
                                style="cursor: pointer;"
                            >
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="col-lg-6">
            <div class="product-detail-info">
                <!-- Product Title -->
                <h1 class="product-detail-info__title h2 mb-3">
                    {{ $product['name'] ?? 'Product Name' }}
                </h1>

                <!-- Product Category -->
                <div class="product-detail-info__category mb-3">
                    <span class="badge bg-secondary">{{ $product['category'] ?? 'Electronics' }}</span>
                    @if(!empty($product['status']))
                    <span class="badge bg-{{ $product['status'] === 'active' ? 'success' : 'danger' }}">
                        {{ ucfirst($product['status'] ?? 'active') }}
                    </span>
                    @endif
                </div>

                <!-- Product Price -->
                <div class="product-detail-info__price mb-4">
                    @if(!empty($product['discount_price']))
                    <div class="d-flex align-items-center gap-3">
                        <span class="price--original h5 mb-0">${{ number_format($product['base_price'] ?? 0, 2) }}</span>
                        <span class="price--discount h3 mb-0">${{ number_format($product['discount_price'], 2) }}</span>
                        <span class="badge bg-danger">
                            -{{ round((($product['base_price'] - $product['discount_price']) / $product['base_price']) * 100) }}%
                        </span>
                    </div>
                    @else
                    <span class="h3 mb-0 text-primary fw-bold">${{ number_format($product['base_price'] ?? 99.99, 2) }}</span>
                    @endif
                </div>

                <!-- Product Description -->
                <div class="product-detail-info__description mb-4">
                    <h5 class="fw-semibold">상품 설명</h5>
                    <p class="text-muted">
                        {{ $product['description'] ?? '고품질의 제품으로 귀하의 니즈를 충족시키도록 설계되었습니다. 뛰어난 장인정신과 현대적인 디자인이 어떤 공간과도 잘 어울립니다.' }}
                    </p>
                </div>

                <!-- Product Specifications -->
                <div class="product-detail-info__specs mb-4">
                    <h5 class="fw-semibold mb-3">상품 정보</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">상품 코드:</span>
                            <span class="fw-semibold">{{ $product['product_code'] ?? 'PROD-001' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">카테고리:</span>
                            <span class="fw-semibold">{{ $product['category'] ?? '전자기기' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">재고:</span>
                            <span class="badge bg-success">재고 있음</span>
                        </li>
                        @if(!empty($product['brand']))
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">브랜드:</span>
                            <span class="fw-semibold">{{ $product['brand'] }}</span>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- Quantity & Add to Cart -->
                <div class="product-detail-info__actions">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label fw-semibold">수량</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" id="decreaseQty">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input
                                    type="number"
                                    class="form-control text-center"
                                    id="quantity"
                                    value="1"
                                    min="1"
                                    max="99"
                                >
                                <button class="btn btn-outline-secondary" type="button" id="increaseQty">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mb-3">
                        <button class="btn btn-primary btn-lg flex-grow-1" id="addToCartBtn">
                            <i class="bi bi-cart-plus me-2"></i>
                            장바구니 담기
                        </button>
                        <button class="btn btn-outline-secondary btn-lg" id="addToWishlistBtn">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>

                    <button class="btn btn-success btn-lg w-100" id="buyNowBtn">
                        <i class="bi bi-lightning-charge me-2"></i>
                        바로 구매
                    </button>
                </div>

                <!-- Additional Info -->
                <div class="product-detail-info__extras mt-4 pt-4 border-top">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-truck fs-4 text-primary me-3"></i>
                                <div>
                                    <div class="fw-semibold">무료 배송</div>
                                    <small class="text-muted">5만원 이상 주문시</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-arrow-clockwise fs-4 text-primary me-3"></i>
                                <div>
                                    <div class="fw-semibold">30일 반품</div>
                                    <small class="text-muted">환불 보장</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-shield-check fs-4 text-primary me-3"></i>
                                <div>
                                    <div class="fw-semibold">안전한 결제</div>
                                    <small class="text-muted">100% 보안 결제</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-headset fs-4 text-primary me-3"></i>
                                <div>
                                    <div class="fw-semibold">24/7 고객지원</div>
                                    <small class="text-muted">전담 지원팀</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                        상세 정보
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">
                        제품 사양
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                        리뷰 (12)
                    </button>
                </li>
            </ul>
            <div class="tab-content p-4 border border-top-0 rounded-bottom" id="productDetailTabsContent">
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <h5 class="mb-3">상품 상세</h5>
                    <p class="text-muted">
                        {{ $product['long_description'] ?? '프리미엄 소재로 제작되어 오래 사용할 수 있도록 설계된 제품입니다. 기능성과 미적 매력을 결합하여 업무용과 개인용 모두에 완벽합니다. 모든 면에서 세심한 디테일이 기대 이상의 제품을 제공합니다.' }}
                    </p>
                    <ul class="text-muted">
                        <li>프리미엄 품질의 소재</li>
                        <li>현대적이고 세련된 디자인</li>
                        <li>사용 및 관리가 쉬움</li>
                        <li>친환경적</li>
                        <li>제조사 품질보증 지원</li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="specifications" role="tabpanel">
                    <h5 class="mb-3">기술 사양</h5>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th width="30%">상품 코드</th>
                                <td>{{ $product['product_code'] ?? 'PROD-001' }}</td>
                            </tr>
                            <tr>
                                <th>카테고리</th>
                                <td>{{ $product['category'] ?? '전자기기' }}</td>
                            </tr>
                            <tr>
                                <th>무게</th>
                                <td>{{ $product['weight'] ?? '1.5 kg' }}</td>
                            </tr>
                            <tr>
                                <th>크기</th>
                                <td>{{ $product['dimensions'] ?? '30 x 20 x 10 cm' }}</td>
                            </tr>
                            <tr>
                                <th>재질</th>
                                <td>{{ $product['material'] ?? '프리미엄 플라스틱 & 메탈' }}</td>
                            </tr>
                            <tr>
                                <th>품질보증</th>
                                <td>{{ $product['warranty'] ?? '제조사 1년 보증' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <h5 class="mb-3">고객 리뷰</h5>

                    <!-- Review Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="display-4 fw-bold text-primary">4.5</div>
                            <div class="text-warning mb-2">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </div>
                            <p class="text-muted mb-0">12개의 리뷰 기준</p>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">5 <i class="bi bi-star-fill text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2">
                                    <div class="progress-bar bg-warning" style="width: 75%"></div>
                                </div>
                                <span class="text-muted">75%</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">4 <i class="bi bi-star-fill text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2">
                                    <div class="progress-bar bg-warning" style="width: 15%"></div>
                                </div>
                                <span class="text-muted">15%</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">3 <i class="bi bi-star-fill text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2">
                                    <div class="progress-bar bg-warning" style="width: 8%"></div>
                                </div>
                                <span class="text-muted">8%</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">2 <i class="bi bi-star-fill text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2">
                                    <div class="progress-bar bg-warning" style="width: 2%"></div>
                                </div>
                                <span class="text-muted">2%</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="me-2">1 <i class="bi bi-star-fill text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2">
                                    <div class="progress-bar bg-warning" style="width: 0%"></div>
                                </div>
                                <span class="text-muted">0%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Individual Reviews -->
                    <div class="review-item border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>John Doe</strong>
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                            <small class="text-muted">2일 전</small>
                        </div>
                        <p class="mb-0">훌륭한 제품입니다! 제가 찾던 제품이에요. 강력 추천합니다.</p>
                    </div>

                    <div class="review-item border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>Jane Smith</strong>
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                            </div>
                            <small class="text-muted">1주 전</small>
                        </div>
                        <p class="mb-0">매우 좋은 품질입니다. 빠른 배송과 훌륭한 고객 서비스.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">관련 상품</h3>
            <div class="row g-4">
                @for($i = 1; $i <= 4; $i++)
                <div class="col-md-3">
                    <div class="card product-card h-100 shadow-hover">
                        <img
                            src="https://via.placeholder.com/300x300?text=Product+{{ $i }}"
                            class="card-img-top"
                            alt="관련 상품 {{ $i }}"
                        >
                        <div class="card-body">
                            <h6 class="card-title">관련 상품 {{ $i }}</h6>
                            <p class="card-text text-muted small">샘플 설명 텍스트</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 mb-0 text-primary">${{ 49.99 + ($i * 10) }}</span>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    const qtyInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');

    decreaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(qtyInput.value);
        if (currentValue > 1) {
            qtyInput.value = currentValue - 1;
        }
    });

    increaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(qtyInput.value);
        if (currentValue < 99) {
            qtyInput.value = currentValue + 1;
        }
    });

    // Thumbnail image click
    const thumbnails = document.querySelectorAll('.product-detail-gallery__thumbnail');
    const mainImage = document.getElementById('mainProductImage');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));

            // Add active class to clicked thumbnail
            this.classList.add('active');

            // Update main image
            mainImage.src = this.dataset.fullImage;
        });
    });

    // Add to cart button
    document.getElementById('addToCartBtn').addEventListener('click', function() {
        const quantity = qtyInput.value;
        alert(`${quantity}개 상품이 장바구니에 추가되었습니다!`);
        // TODO: 실제 장바구니 기능 구현
    });

    // Add to wishlist button
    document.getElementById('addToWishlistBtn').addEventListener('click', function() {
        this.classList.toggle('btn-outline-secondary');
        this.classList.toggle('btn-danger');
        const icon = this.querySelector('i');
        icon.classList.toggle('bi-heart');
        icon.classList.toggle('bi-heart-fill');
    });

    // Buy now button
    document.getElementById('buyNowBtn').addEventListener('click', function() {
        alert('결제 페이지로 이동합니다...');
        // TODO: 결제 페이지 리다이렉트 구현
    });
});
</script>
@endpush
