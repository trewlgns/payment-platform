@extends('layouts.shop')

@section('title', '프로젝트 소개 - PayShop')

@section('content')
<div class="about">
    {{-- Hero Section --}}
    <section class="about-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <span class="about-hero__badge">Portfolio Project</span>
                    <h1 class="about-hero__title">Payment Platform</h1>
                    <p class="about-hero__desc">
                        Laravel 기반 멀티 PG 통합 결제 플랫폼<br>
                        Service-Repository 패턴과 SQLP 고급 쿼리 활용
                    </p>
                    <div class="about-hero__buttons">
                        <a href="{{ url('/') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-shop"></i> 쇼핑몰 체험하기
                        </a>
                        <a href="{{ url('/admin/dashboard') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-speedometer2"></i> 관리자 대시보드
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tech Stack Section --}}
    <section class="about-section">
        <div class="container">
            <div class="about-section__header">
                <h2 class="about-section__title">기술 스택</h2>
                <p class="about-section__subtitle">프로젝트에 사용된 핵심 기술들</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="tech-card">
                        <div class="tech-card__icon tech-card__icon--red">
                            <i class="bi bi-filetype-php"></i>
                        </div>
                        <h5 class="tech-card__title">Laravel 10</h5>
                        <p class="tech-card__desc">PHP 8.2 기반<br>Gateway API 서버</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="tech-card">
                        <div class="tech-card__icon tech-card__icon--blue">
                            <i class="bi bi-database"></i>
                        </div>
                        <h5 class="tech-card__title">MySQL 8.0</h5>
                        <p class="tech-card__desc">파티셔닝, 인덱스 최적화<br>SQLP 고급 쿼리</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="tech-card">
                        <div class="tech-card__icon tech-card__icon--yellow">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h5 class="tech-card__title">FastAPI</h5>
                        <p class="tech-card__desc">Python 기반<br>Mock PG 서버</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="tech-card">
                        <div class="tech-card__icon tech-card__icon--purple">
                            <i class="bi bi-bootstrap"></i>
                        </div>
                        <h5 class="tech-card__title">Bootstrap 5</h5>
                        <p class="tech-card__desc">Blade + jQuery<br>SCSS + BEM</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Architecture Section --}}
    <section class="about-section about-section--gray">
        <div class="container">
            <div class="about-section__header">
                <h2 class="about-section__title">시스템 아키텍처</h2>
                <p class="about-section__subtitle">멀티 서비스 기반 결제 플랫폼 구조</p>
            </div>
            <div class="architecture-diagram">
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-10">
                        <div class="architecture-box">
                            <div class="architecture-layer">
                                <div class="architecture-layer__title">Frontend</div>
                                <div class="architecture-layer__items">
                                    <span class="architecture-item">Laravel Blade</span>
                                    <span class="architecture-item">jQuery</span>
                                    <span class="architecture-item">Bootstrap 5</span>
                                    <span class="architecture-item">Chart.js</span>
                                </div>
                            </div>
                            <div class="architecture-arrow">
                                <i class="bi bi-arrow-down"></i>
                            </div>
                            <div class="architecture-layer architecture-layer--primary">
                                <div class="architecture-layer__title">Gateway API (Laravel)</div>
                                <div class="architecture-layer__items">
                                    <span class="architecture-item">Controller</span>
                                    <span class="architecture-item">Service</span>
                                    <span class="architecture-item">Validator</span>
                                    <span class="architecture-item">Repository</span>
                                </div>
                            </div>
                            <div class="architecture-arrow">
                                <i class="bi bi-arrow-down"></i>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="architecture-layer architecture-layer--success">
                                        <div class="architecture-layer__title">Mock PG Server</div>
                                        <div class="architecture-layer__items">
                                            <span class="architecture-item">FastAPI</span>
                                            <span class="architecture-item">Webhook</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="architecture-layer architecture-layer--warning">
                                        <div class="architecture-layer__title">Statistics Batch</div>
                                        <div class="architecture-layer__items">
                                            <span class="architecture-item">Artisan Command</span>
                                            <span class="architecture-item">Scheduler</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="architecture-layer architecture-layer--info">
                                        <div class="architecture-layer__title">Database</div>
                                        <div class="architecture-layer__items">
                                            <span class="architecture-item">MySQL 8.0</span>
                                            <span class="architecture-item">Partitioning</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="about-section">
        <div class="container">
            <div class="about-section__header">
                <h2 class="about-section__title">핵심 기능</h2>
                <p class="about-section__subtitle">프로젝트의 주요 구현 사항</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="bi bi-credit-card-2-back"></i>
                        </div>
                        <div class="feature-card__content">
                            <h5>멀티 PG 통합</h5>
                            <p>Adapter 패턴으로 다양한 PG사 연동 추상화. Toss, KakaoPay 등 쉽게 확장 가능한 구조.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="bi bi-layers"></i>
                        </div>
                        <div class="feature-card__content">
                            <h5>Service-Repository 패턴</h5>
                            <p>계층별 책임 분리. BaseService로 트랜잭션/로깅 자동화, PDO 기반 Static Query 사용.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="feature-card__content">
                            <h5>SQLP 고급 쿼리</h5>
                            <p>Window Function, CTE 활용한 통계 배치. 테이블 파티셔닝으로 대용량 데이터 최적화.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div class="feature-card__content">
                            <h5>Webhook 시스템</h5>
                            <p>PG 결제 상태 변경 실시간 수신. HMAC 서명 검증, 재시도 로직, 멱등성 키 처리.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                        <div class="feature-card__content">
                            <h5>통계 대시보드</h5>
                            <p>Chart.js 기반 시각화. 일별/월별 매출, PG별 성과, 고객 세그먼트 분석.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="feature-card__content">
                            <h5>환불 관리</h5>
                            <p>전액/부분 환불 지원. 환불 요청 → 승인 → 처리 플로우. PG 연동 자동 처리.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Demo Links --}}
    <section class="about-section about-section--dark">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="text-white mb-4">직접 체험해보세요</h2>
                    <p class="text-white-50 mb-4">
                        쇼핑몰에서 상품을 장바구니에 담고, 결제까지 진행해보세요.<br>
                        관리자 대시보드에서 통계와 주문 관리 기능을 확인할 수 있습니다.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ url('/products') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-grid"></i> 상품 목록
                        </a>
                        <a href="{{ url('/cart') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-cart3"></i> 장바구니
                        </a>
                        <a href="{{ url('/admin/dashboard') }}" class="btn btn-warning btn-lg">
                            <i class="bi bi-speedometer2"></i> 관리자
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
