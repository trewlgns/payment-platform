{{-- Shop Footer Component - Portfolio Version --}}

<footer class="shop-footer py-5">
    <div class="container">
        <div class="row">
            {{-- Project Info Section --}}
            <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
                <h5 class="footer-heading fw-bold mb-3">
                    <i class="bi bi-layers"></i>
                    Payment Platform
                </h5>
                <p class="footer-description mb-3">
                    Laravel 기반 멀티 PG 통합 결제 플랫폼 포트폴리오 프로젝트입니다.
                    <br>
                    엔터프라이즈 아키텍처 패턴을 활용한 실무 중심 프로젝트입니다.
                </p>

                {{-- Tech Stack Badges --}}
                <div class="tech-stack mb-3">
                    <span class="badge bg-primary me-2 mb-2">Laravel 10</span>
                    <span class="badge bg-primary me-2 mb-2">PHP 8.2</span>
                    <span class="badge bg-info me-2 mb-2">MySQL 8.0</span>
                    <span class="badge bg-success me-2 mb-2">Python FastAPI</span>
                    <span class="badge bg-warning text-dark me-2 mb-2">jQuery</span>
                    <span class="badge bg-secondary me-2 mb-2">SCSS</span>
                </div>

                {{-- Key Features --}}
                <div class="key-features">
                    <small class="text-light d-block mb-1">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        Service-Repository 패턴
                    </small>
                    <small class="text-light d-block mb-1">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        PG Adapter Pattern
                    </small>
                    <small class="text-light d-block">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        통계 배치 및 대시보드 시각화
                    </small>
                </div>
            </div>

            {{-- Developer Info Section --}}
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="footer-heading fw-bold mb-3">개발자 정보</h6>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <a href="https://github.com/trewlgns/payment-platform"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="footer-link text-decoration-none">
                            <i class="bi bi-github me-2"></i>
                            GitHub Repository
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="mailto:trewlgns@naver.com"
                           class="footer-link text-decoration-none">
                            <i class="bi bi-envelope me-2"></i>
                            trewlgns@naver.com
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="#"
                           class="footer-link text-decoration-none footer-disabled"
                           title="포트폴리오 사이트 준비 중">
                            <i class="bi bi-globe me-2"></i>
                            Portfolio Site
                            <small>(준비 중)</small>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Project Links Section --}}
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="footer-heading fw-bold mb-3">프로젝트 링크</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="/" class="footer-link text-decoration-none">
                            <i class="bi bi-house me-2"></i>
                            홈
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="/products" class="footer-link text-decoration-none">
                            <i class="bi bi-box-seam me-2"></i>
                            상품 목록
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="/admin/dashboard" class="footer-link text-decoration-none">
                            <i class="bi bi-speedometer2 me-2"></i>
                            관리자 대시보드
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="https://github.com/trewlgns/payment-platform#readme"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="footer-link text-decoration-none">
                            <i class="bi bi-file-text me-2"></i>
                            프로젝트 문서
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Divider --}}
        <hr class="footer-divider my-4">

        {{-- Bottom Footer --}}
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <small class="footer-copyright">
                    &copy; {{ date('Y') }} Payment Platform Portfolio Project
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <small class="footer-copyright">
                    <span class="me-3">
                        <i class="bi bi-code-slash me-1"></i>
                        Built with Laravel & FastAPI
                    </span>
                    <a href="https://github.com/trewlgns/payment-platform/blob/main/LICENSE"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="footer-link text-decoration-none">
                        MIT License
                    </a>
                </small>
            </div>
        </div>
    </div>
</footer>
