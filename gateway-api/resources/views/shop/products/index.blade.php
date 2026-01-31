@extends('layouts.shop')

@section('title', '상품 목록')

@section('content')
<div class="products-page">
    <!-- Hero Banner -->
    <section class="products-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door"></i> 홈</a></li>
                            <li class="breadcrumb-item active" aria-current="page">전체 상품</li>
                        </ol>
                    </nav>
                    <h1 class="products-hero__title">전체 상품</h1>
                    <p class="products-hero__subtitle">최신 트렌드의 다양한 상품을 만나보세요</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-lg-3">
                <aside class="products-filter">
                    <h2 class="products-filter__title">
                        <i class="bi bi-funnel"></i> 필터
                    </h2>

                    <!-- Category Filter -->
                    <div class="products-filter__section">
                        <h3 class="products-filter__section-title">카테고리</h3>
                        <ul class="products-filter__category-list">
                            <li class="products-filter__category-item">
                                <a href="#" class="active">
                                    전체 <span class="count">{{ count($products) }}</span>
                                </a>
                            </li>
                            <li class="products-filter__category-item">
                                <a href="#">
                                    전자기기 <span class="count">12</span>
                                </a>
                            </li>
                            <li class="products-filter__category-item">
                                <a href="#">
                                    웨어러블 <span class="count">8</span>
                                </a>
                            </li>
                            <li class="products-filter__category-item">
                                <a href="#">
                                    액세서리 <span class="count">15</span>
                                </a>
                            </li>
                            <li class="products-filter__category-item">
                                <a href="#">
                                    오디오 <span class="count">6</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Price Range Filter (Dual Slider) -->
                    <div class="products-filter__section">
                        <h3 class="products-filter__section-title">가격대</h3>
                        <div class="products-filter__price-range">
                            <div class="price-slider">
                                <div class="price-slider__track"></div>
                                <div class="price-slider__range" id="priceSliderRange"></div>
                                <input type="range" class="price-slider__input" id="priceMin" min="0" max="500000" step="10000" value="0">
                                <input type="range" class="price-slider__input" id="priceMax" min="0" max="500000" step="10000" value="500000">
                            </div>
                            <div class="price-display">
                                <span class="price-display__value" id="minPriceDisplay">₩0</span>
                                <span class="price-display__separator">~</span>
                                <span class="price-display__value" id="maxPriceDisplay">₩500,000</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="products-filter__section">
                        <h3 class="products-filter__section-title">평점</h3>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="rating5">
                            <label class="form-check-label" for="rating5">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <small class="text-muted ms-1">5점</small>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="rating4">
                            <label class="form-check-label" for="rating4">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star text-warning"></i>
                                <small class="text-muted ms-1">4점 이상</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rating3">
                            <label class="form-check-label" for="rating3">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star text-warning"></i>
                                <i class="bi bi-star text-warning"></i>
                                <small class="text-muted ms-1">3점 이상</small>
                            </label>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-secondary products-filter__reset-btn">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> 필터 초기화
                    </button>
                </aside>
            </div>

            <!-- Products Content -->
            <div class="col-lg-9">
                <!-- Toolbar -->
                <div class="products-toolbar">
                    <div class="products-toolbar__result-count">
                        총 <strong>{{ count($products) }}개</strong>의 상품
                    </div>
                    <div class="products-toolbar__controls">
                        <div class="products-toolbar__sort">
                            <select class="form-select" id="sortSelect">
                                <option value="latest" selected>최신순</option>
                                <option value="price_low">가격 낮은순</option>
                                <option value="price_high">가격 높은순</option>
                                <option value="popular">인기순</option>
                                <option value="rating">평점순</option>
                            </select>
                        </div>
                        <div class="products-toolbar__view-toggle btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary active" data-view="grid" title="그리드 보기">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-view="list" title="리스트 보기">
                                <i class="bi bi-list-ul"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                @if(count($products) > 0)
                <div class="products-grid" id="productsGrid">
                    @foreach($products as $index => $product)
                    <article class="product-card">
                        <!-- Product Image -->
                        <div class="product-card__image">
                            <a href="/products/{{ $product['product_code'] }}">
                                <img
                                    src="{{ $product['image_url'] ?? 'https://via.placeholder.com/400x400/f8f9fa/6c757d?text=' . urlencode($product['name']) }}"
                                    alt="{{ $product['name'] }}"
                                    loading="lazy"
                                >
                            </a>

                            <!-- Badges -->
                            <div class="product-card__badges">
                                @if($index < 2)
                                <span class="product-card__badge product-card__badge--new">NEW</span>
                                @endif
                                @if(!empty($product['discount_price']))
                                @php
                                    $discountPercent = round((1 - $product['discount_price'] / $product['base_price']) * 100);
                                @endphp
                                <span class="product-card__badge product-card__badge--sale">{{ $discountPercent }}% OFF</span>
                                @endif
                                @if($product['status'] === 'soldout')
                                <span class="product-card__badge product-card__badge--soldout">품절</span>
                                @endif
                            </div>

                            <!-- Wishlist Button -->
                            <button class="product-card__wishlist" data-product-code="{{ $product['product_code'] }}" title="위시리스트에 추가">
                                <i class="bi bi-heart"></i>
                            </button>

                            <!-- Quick Actions (hover) -->
                            <div class="product-card__quick-actions">
                                <a href="/products/{{ $product['product_code'] }}" class="btn btn-light">
                                    <i class="bi bi-eye"></i> 상세보기
                                </a>
                                <button class="btn btn-primary add-to-cart-btn" data-product-code="{{ $product['product_code'] }}">
                                    <i class="bi bi-cart-plus"></i> 담기
                                </button>
                            </div>
                        </div>

                        <!-- Product Body -->
                        <div class="product-card__body">
                            <span class="product-card__category">{{ $product['category'] ?? '일반' }}</span>

                            <h3 class="product-card__title">
                                <a href="/products/{{ $product['product_code'] }}">{{ $product['name'] }}</a>
                            </h3>

                            <!-- Rating -->
                            <div class="product-card__rating">
                                <div class="stars">
                                    @php $rating = $product['rating'] ?? rand(35, 50) / 10; @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($rating))
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($i - 0.5 <= $rating)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="count">({{ $product['review_count'] ?? rand(10, 200) }})</span>
                            </div>

                            <!-- Price -->
                            <div class="product-card__price">
                                @if(!empty($product['discount_price']))
                                    <span class="product-card__price-current">
                                        ₩{{ number_format($product['discount_price']) }}
                                    </span>
                                    <span class="product-card__price-original">
                                        ₩{{ number_format($product['base_price']) }}
                                    </span>
                                    <span class="product-card__price-discount">
                                        {{ $discountPercent }}%
                                    </span>
                                @else
                                    <span class="product-card__price-current">
                                        ₩{{ number_format($product['base_price']) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Product Footer (Mobile) -->
                        <div class="product-card__footer">
                            <a href="/products/{{ $product['product_code'] }}" class="btn btn-outline-primary">
                                <i class="bi bi-eye me-1"></i> 상세보기
                            </a>
                            <button class="btn btn-primary add-to-cart-btn" data-product-code="{{ $product['product_code'] }}">
                                <i class="bi bi-cart-plus me-1"></i> 장바구니
                            </button>
                        </div>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <nav class="products-pagination" aria-label="상품 페이지네이션">
                    <ul class="pagination">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                @else
                <!-- Empty State -->
                <div class="products-empty">
                    <div class="products-empty__icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h3 class="products-empty__title">상품이 없습니다</h3>
                    <p class="products-empty__text">조건에 맞는 상품을 찾을 수 없습니다.<br>다른 필터를 선택해 보세요.</p>
                    <a href="/products" class="btn btn-primary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> 전체 상품 보기
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Toggle (Grid / List)
    const viewButtons = document.querySelectorAll('.products-toolbar__view-toggle .btn');
    const productsGrid = document.getElementById('productsGrid');

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const view = this.dataset.view;
            if (view === 'list') {
                productsGrid.classList.add('products-grid--list');
            } else {
                productsGrid.classList.remove('products-grid--list');
            }
        });
    });

    // Wishlist Toggle
    document.querySelectorAll('.product-card__wishlist').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            this.classList.toggle('active');

            if (this.classList.contains('active')) {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
                showToast('위시리스트에 추가되었습니다', 'success');
            } else {
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
                showToast('위시리스트에서 제거되었습니다', 'info');
            }
        });
    });

    // Add to Cart
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productCode = this.dataset.productCode;

            // Add animation
            this.innerHTML = '<i class="bi bi-check-lg"></i> 추가됨';
            this.disabled = true;

            showToast('장바구니에 추가되었습니다', 'success');

            // Reset button after 2 seconds
            setTimeout(() => {
                this.innerHTML = '<i class="bi bi-cart-plus"></i> 담기';
                this.disabled = false;
            }, 2000);
        });
    });

    // Dual Price Range Slider
    const priceMin = document.getElementById('priceMin');
    const priceMax = document.getElementById('priceMax');
    const priceSliderRange = document.getElementById('priceSliderRange');
    const minPriceDisplay = document.getElementById('minPriceDisplay');
    const maxPriceDisplay = document.getElementById('maxPriceDisplay');

    function updatePriceSlider() {
        const min = parseInt(priceMin.value);
        const max = parseInt(priceMax.value);
        const totalRange = 500000;

        // Prevent overlap
        if (min > max - 10000) {
            if (this === priceMin) {
                priceMin.value = max - 10000;
            } else {
                priceMax.value = min + 10000;
            }
        }

        const minVal = parseInt(priceMin.value);
        const maxVal = parseInt(priceMax.value);

        // Update range bar position
        const leftPercent = (minVal / totalRange) * 100;
        const rightPercent = 100 - (maxVal / totalRange) * 100;
        priceSliderRange.style.left = leftPercent + '%';
        priceSliderRange.style.right = rightPercent + '%';

        // Update display values
        minPriceDisplay.textContent = '₩' + minVal.toLocaleString('ko-KR');
        maxPriceDisplay.textContent = '₩' + maxVal.toLocaleString('ko-KR');
    }

    if (priceMin && priceMax) {
        priceMin.addEventListener('input', updatePriceSlider);
        priceMax.addEventListener('input', updatePriceSlider);
        updatePriceSlider(); // Initialize
    }

    // Category Filter Active State
    document.querySelectorAll('.products-filter__category-item a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.products-filter__category-item a').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Toast Notification Helper
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-notification--${type}`;
        toast.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
            ${message}
        `;
        toast.style.cssText = `
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: ${type === 'success' ? '#28a745' : '#17a2b8'};
            color: white;
            padding: 14px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: slideIn 0.3s ease;
            display: flex;
            align-items: center;
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush
