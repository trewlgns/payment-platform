@extends('layouts.shop')

@section('title', '홈 - 결제 플랫폼')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="hero rounded mb-5">
        <div class="container text-center">
            <h1 class="hero__title">
                <i class="bi bi-wallet2"></i> 결제 플랫폼
            </h1>
            <p class="hero__subtitle">
                Laravel & Bootstrap 5 기반 멀티 PG 통합 결제 플랫폼
            </p>
            <div class="mt-4">
                <a href="{{ url('/products') }}" class="btn btn-light btn-lg me-2">
                    <i class="bi bi-grid"></i> 상품 둘러보기
                </a>
                <a href="{{ url('/admin') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-speedometer2"></i> 관리자 대시보드
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mb-5">
        <div class="col-lg-4 mb-4">
            <div class="card card-hover h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-credit-card text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold">멀티 PG 지원</h5>
                    <p class="card-text text-muted">
                        Toss, KakaoPay 등 다양한 결제 게이트웨이와 연동을 지원합니다.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card card-hover h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-graph-up text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold">분석 대시보드</h5>
                    <p class="card-text text-muted">
                        실시간 분석 및 통계를 아름다운 차트와 리포트로 제공합니다.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card card-hover h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-shield-check text-info" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold">안전한 거래</h5>
                    <p class="card-text text-muted">
                        엔터프라이즈급 보안과 트랜잭션 관리, 환불 지원을 제공합니다.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-5">
        <div class="col-md-3 mb-4">
            <div class="stats-card">
                <div class="stats-card__icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stats-card__value">1,234</div>
                <div class="stats-card__label">총 주문</div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stats-card stats-card--success">
                <div class="stats-card__icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-card__value">1,189</div>
                <div class="stats-card__label">완료</div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stats-card stats-card--warning">
                <div class="stats-card__icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stats-card__value">45</div>
                <div class="stats-card__label">대기중</div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stats-card stats-card--danger">
                <div class="stats-card__icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stats-card__value">$12.5M</div>
                <div class="stats-card__label">총 수익</div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="card bg-light border-0 mb-5">
        <div class="card-body text-center py-5">
            <h2 class="mb-3">지금 바로 시작해보세요!</h2>
            <p class="text-muted mb-4">
                결제 플랫폼을 탐색하고 비즈니스 성장에 어떻게 도움이 되는지 확인하세요.
            </p>
            <a href="{{ url('/products') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-arrow-right-circle"></i> 시작하기
            </a>
        </div>
    </div>
</div>
@endsection
