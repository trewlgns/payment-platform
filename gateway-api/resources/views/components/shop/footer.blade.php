{{-- Shop Footer Component --}}

<footer class="shop-footer bg-dark text-light py-5">
    <div class="container">
        <div class="row">
            {{-- About Section --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-shop"></i>
                    결제 플랫폼 쇼핑몰
                </h5>
                <p class="text-muted">
                    Laravel 기반의 현대적인 멀티 PG 통합 결제 플랫폼으로, 엔터프라이즈급 아키텍처와 결제 게이트웨이 패턴을 구현한 프로젝트입니다.
                </p>
                <div class="social-links mt-3">
                    <a href="#" class="text-light me-3" aria-label="GitHub">
                        <i class="bi bi-github fs-4"></i>
                    </a>
                    <a href="#" class="text-light me-3" aria-label="LinkedIn">
                        <i class="bi bi-linkedin fs-4"></i>
                    </a>
                    <a href="#" class="text-light me-3" aria-label="Twitter">
                        <i class="bi bi-twitter fs-4"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">빠른 링크</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/" class="text-muted text-decoration-none hover-primary">홈</a></li>
                    <li class="mb-2"><a href="/products" class="text-muted text-decoration-none hover-primary">상품</a></li>
                    <li class="mb-2"><a href="/cart" class="text-muted text-decoration-none hover-primary">장바구니</a></li>
                    <li class="mb-2"><a href="/orders" class="text-muted text-decoration-none hover-primary">주문 내역</a></li>
                </ul>
            </div>

            {{-- Customer Support --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">고객 지원</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/help" class="text-muted text-decoration-none hover-primary">고객센터</a></li>
                    <li class="mb-2"><a href="/faq" class="text-muted text-decoration-none hover-primary">자주 묻는 질문</a></li>
                    <li class="mb-2"><a href="/shipping" class="text-muted text-decoration-none hover-primary">배송 정보</a></li>
                    <li class="mb-2"><a href="/returns" class="text-muted text-decoration-none hover-primary">반품/교환</a></li>
                    <li class="mb-2"><a href="/contact" class="text-muted text-decoration-none hover-primary">문의하기</a></li>
                </ul>
            </div>

            {{-- Contact Info --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">연락처</h6>
                <ul class="list-unstyled">
                    <li class="mb-2 text-muted">
                        <i class="bi bi-envelope me-2"></i>
                        support@paymentshop.com
                    </li>
                    <li class="mb-2 text-muted">
                        <i class="bi bi-telephone me-2"></i>
                        +82 10-1234-5678
                    </li>
                    <li class="mb-2 text-muted">
                        <i class="bi bi-geo-alt me-2"></i>
                        서울, 대한민국
                    </li>
                </ul>
                <div class="mt-3">
                    <small class="text-muted">운영 시간:</small><br>
                    <small class="text-muted">월 - 금: 오전 9:00 - 오후 6:00</small>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <hr class="border-secondary my-4">

        {{-- Bottom Footer --}}
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <small class="text-muted">
                    &copy; {{ date('Y') }} 결제 플랫폼 쇼핑몰. All rights reserved.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <small class="text-muted">
                    <a href="/terms" class="text-muted text-decoration-none hover-primary me-3">이용약관</a>
                    <a href="/privacy" class="text-muted text-decoration-none hover-primary">개인정보처리방침</a>
                </small>
            </div>
        </div>
    </div>
</footer>
