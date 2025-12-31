@extends('layouts.shop')

@section('title', 'Home - Payment Platform')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="hero rounded mb-5">
        <div class="container text-center">
            <h1 class="hero__title">
                <i class="bi bi-wallet2"></i> Payment Platform
            </h1>
            <p class="hero__subtitle">
                Multi PG integration payment platform with Laravel & Bootstrap 5
            </p>
            <div class="mt-4">
                <a href="{{ url('/products') }}" class="btn btn-light btn-lg me-2">
                    <i class="bi bi-grid"></i> Browse Products
                </a>
                <a href="{{ url('/admin') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-speedometer2"></i> Admin Dashboard
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
                    <h5 class="card-title fw-bold">Multi PG Support</h5>
                    <p class="card-text text-muted">
                        Integrate with multiple payment gateways including Toss, KakaoPay, and more.
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
                    <h5 class="card-title fw-bold">Analytics Dashboard</h5>
                    <p class="card-text text-muted">
                        Real-time analytics and statistics with beautiful charts and reports.
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
                    <h5 class="card-title fw-bold">Secure Transactions</h5>
                    <p class="card-text text-muted">
                        Enterprise-grade security with transaction management and refund support.
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
                <div class="stats-card__label">Total Orders</div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stats-card stats-card--success">
                <div class="stats-card__icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-card__value">1,189</div>
                <div class="stats-card__label">Completed</div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stats-card stats-card--warning">
                <div class="stats-card__icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stats-card__value">45</div>
                <div class="stats-card__label">Pending</div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stats-card stats-card--danger">
                <div class="stats-card__icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stats-card__value">$12.5M</div>
                <div class="stats-card__label">Total Revenue</div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="card bg-light border-0 mb-5">
        <div class="card-body text-center py-5">
            <h2 class="mb-3">Ready to get started?</h2>
            <p class="text-muted mb-4">
                Explore our payment platform and see how it can help your business grow.
            </p>
            <a href="{{ url('/products') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-arrow-right-circle"></i> Get Started
            </a>
        </div>
    </div>
</div>
@endsection
