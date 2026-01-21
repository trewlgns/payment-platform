@extends('layouts.shop')

@section('title', 'PayShop - 멀티 PG 결제 플랫폼')

@section('content')
<div class="home">
    {{-- Hero Banner Carousel --}}
    <section class="home-hero">
        <div id="heroBannerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="2"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="home-hero__slide home-hero__slide--primary">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <span class="home-hero__badge">신규 회원 특가</span>
                                    <h1 class="home-hero__title">봄맞이<br>전자기기 특별전</h1>
                                    <p class="home-hero__desc">인기 전자기기 최대 40% 할인</p>
                                    <a href="{{ url('/products') }}" class="btn btn-light btn-lg">
                                        지금 쇼핑하기 <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                                <div class="col-lg-6 text-center d-none d-lg-block">
                                    <img src="https://via.placeholder.com/400x300?text=Electronics" alt="전자기기" class="home-hero__image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="home-hero__slide home-hero__slide--success">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <span class="home-hero__badge">한정 수량</span>
                                    <h1 class="home-hero__title">베스트셀러<br>스마트워치</h1>
                                    <p class="home-hero__desc">매일이 더 스마트해지는 경험</p>
                                    <a href="{{ url('/products/PROD002') }}" class="btn btn-light btn-lg">
                                        자세히 보기 <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                                <div class="col-lg-6 text-center d-none d-lg-block">
                                    <img src="https://via.placeholder.com/400x300?text=Smart+Watch" alt="스마트워치" class="home-hero__image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="home-hero__slide home-hero__slide--dark">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <span class="home-hero__badge">무료 배송</span>
                                    <h1 class="home-hero__title">프리미엄<br>액세서리 컬렉션</h1>
                                    <p class="home-hero__desc">5만원 이상 구매 시 무료 배송</p>
                                    <a href="{{ url('/products') }}" class="btn btn-warning btn-lg">
                                        컬렉션 보기 <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                                <div class="col-lg-6 text-center d-none d-lg-block">
                                    <img src="https://via.placeholder.com/400x300?text=Accessories" alt="액세서리" class="home-hero__image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    {{-- Quick Category Navigation --}}
    <section class="home-categories">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <div class="col-4 col-md-2">
                    <a href="{{ url('/products?category=electronics') }}" class="home-categories__item">
                        <div class="home-categories__icon">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <span>전자기기</span>
                    </a>
                </div>
                <div class="col-4 col-md-2">
                    <a href="{{ url('/products?category=wearables') }}" class="home-categories__item">
                        <div class="home-categories__icon">
                            <i class="bi bi-smartwatch"></i>
                        </div>
                        <span>웨어러블</span>
                    </a>
                </div>
                <div class="col-4 col-md-2">
                    <a href="{{ url('/products?category=accessories') }}" class="home-categories__item">
                        <div class="home-categories__icon">
                            <i class="bi bi-bag"></i>
                        </div>
                        <span>액세서리</span>
                    </a>
                </div>
                <div class="col-4 col-md-2">
                    <a href="{{ url('/products?category=audio') }}" class="home-categories__item">
                        <div class="home-categories__icon">
                            <i class="bi bi-headphones"></i>
                        </div>
                        <span>오디오</span>
                    </a>
                </div>
                <div class="col-4 col-md-2">
                    <a href="{{ url('/products?category=gaming') }}" class="home-categories__item">
                        <div class="home-categories__icon">
                            <i class="bi bi-controller"></i>
                        </div>
                        <span>게이밍</span>
                    </a>
                </div>
                <div class="col-4 col-md-2">
                    <a href="{{ url('/products') }}" class="home-categories__item">
                        <div class="home-categories__icon">
                            <i class="bi bi-grid"></i>
                        </div>
                        <span>전체보기</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Products --}}
    <section class="home-section">
        <div class="container">
            <div class="home-section__header">
                <h2 class="home-section__title">
                    <i class="bi bi-fire text-danger"></i> 인기 상품
                </h2>
                <a href="{{ url('/products?sort=popular') }}" class="home-section__more">
                    전체보기 <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card card h-100">
                        <div class="product-card__image-wrapper">
                            <a href="{{ url('/products/' . $product['product_code']) }}">
                                <img src="{{ $product['image_url'] }}" class="product-card__image" alt="{{ $product['name'] }}">
                            </a>
                            @if($product['badge'])
                            <span class="product-card__badge badge bg-{{ $product['badge_color'] }}">
                                {{ $product['badge'] }}
                            </span>
                            @endif
                            <div class="product-card__actions">
                                <button class="btn btn-light btn-sm" title="찜하기">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <button class="btn btn-primary btn-sm add-to-cart-btn"
                                        data-product-code="{{ $product['product_code'] }}"
                                        title="장바구니 담기">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="product-card__category">{{ $product['category'] }}</p>
                            <h5 class="product-card__title">
                                <a href="{{ url('/products/' . $product['product_code']) }}">
                                    {{ $product['name'] }}
                                </a>
                            </h5>
                            <div class="product-card__price">
                                @if($product['discount_price'])
                                <span class="product-card__price--original">₩{{ number_format($product['base_price']) }}</span>
                                <span class="product-card__price--sale">₩{{ number_format($product['discount_price']) }}</span>
                                @else
                                <span class="product-card__price--current">₩{{ number_format($product['base_price']) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Promotion Banners --}}
    <section class="home-promotion">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <a href="{{ url('/products?category=electronics') }}" class="home-promotion__card home-promotion__card--blue">
                        <div class="home-promotion__content">
                            <span class="home-promotion__label">특가 진행중</span>
                            <h3 class="home-promotion__title">전자기기<br>최대 40% 할인</h3>
                            <span class="home-promotion__cta">쇼핑하기 →</span>
                        </div>
                        <img src="https://via.placeholder.com/200x200?text=Electronics" alt="" class="home-promotion__image">
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('/products?category=accessories') }}" class="home-promotion__card home-promotion__card--orange">
                        <div class="home-promotion__content">
                            <span class="home-promotion__label">신상품 입고</span>
                            <h3 class="home-promotion__title">프리미엄<br>액세서리</h3>
                            <span class="home-promotion__cta">쇼핑하기 →</span>
                        </div>
                        <img src="https://via.placeholder.com/200x200?text=Accessories" alt="" class="home-promotion__image">
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- New Arrivals --}}
    <section class="home-section">
        <div class="container">
            <div class="home-section__header">
                <h2 class="home-section__title">
                    <i class="bi bi-stars text-warning"></i> 신상품
                </h2>
                <a href="{{ url('/products?sort=newest') }}" class="home-section__more">
                    전체보기 <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="row g-4">
                @foreach($newProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card card h-100">
                        <div class="product-card__image-wrapper">
                            <a href="{{ url('/products/' . $product['product_code']) }}">
                                <img src="{{ $product['image_url'] }}" class="product-card__image" alt="{{ $product['name'] }}">
                            </a>
                            @if($product['badge'])
                            <span class="product-card__badge badge bg-{{ $product['badge_color'] }}">
                                {{ $product['badge'] }}
                            </span>
                            @endif
                            <div class="product-card__actions">
                                <button class="btn btn-light btn-sm" title="찜하기">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <button class="btn btn-primary btn-sm add-to-cart-btn"
                                        data-product-code="{{ $product['product_code'] }}"
                                        title="장바구니 담기">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="product-card__category">{{ $product['category'] }}</p>
                            <h5 class="product-card__title">
                                <a href="{{ url('/products/' . $product['product_code']) }}">
                                    {{ $product['name'] }}
                                </a>
                            </h5>
                            <div class="product-card__price">
                                @if($product['discount_price'])
                                <span class="product-card__price--original">₩{{ number_format($product['base_price']) }}</span>
                                <span class="product-card__price--sale">₩{{ number_format($product['discount_price']) }}</span>
                                @else
                                <span class="product-card__price--current">₩{{ number_format($product['base_price']) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Benefits Banner --}}
    <section class="home-benefits">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-lg-3">
                    <div class="home-benefits__item">
                        <i class="bi bi-truck"></i>
                        <div>
                            <strong>무료 배송</strong>
                            <span>5만원 이상 구매 시</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="home-benefits__item">
                        <i class="bi bi-shield-check"></i>
                        <div>
                            <strong>안전 결제</strong>
                            <span>다양한 PG 지원</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="home-benefits__item">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <div>
                            <strong>간편 환불</strong>
                            <span>7일 이내 무료 반품</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="home-benefits__item">
                        <i class="bi bi-headset"></i>
                        <div>
                            <strong>고객 지원</strong>
                            <span>24시간 상담 가능</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add to cart functionality
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        const productCode = $(this).data('product-code');
        const btn = $(this);

        // Get current cart from localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Check if product already exists
        const existingItem = cart.find(item => item.product_code === productCode);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                product_code: productCode,
                quantity: 1
            });
        }

        // Save to localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Update cart badge
        updateCartBadge();

        // Show feedback
        btn.addClass('btn-success').removeClass('btn-primary');
        btn.find('i').removeClass('bi-cart-plus').addClass('bi-check');

        setTimeout(function() {
            btn.removeClass('btn-success').addClass('btn-primary');
            btn.find('i').removeClass('bi-check').addClass('bi-cart-plus');
        }, 1000);
    });

    function updateCartBadge() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const badge = $('.cart-badge');

        if (totalItems > 0) {
            badge.text(totalItems).show();
        } else {
            badge.hide();
        }
    }

    // Initialize cart badge on page load
    updateCartBadge();
});
</script>
@endpush
