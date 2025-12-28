@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .dashboard-header {
        margin-bottom: 2rem;
    }

    .stats-card {
        transition: transform 0.2s ease-in-out;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<div class="container-fluid dashboard-content">
    {{-- Dashboard Header --}}
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Dashboard</h1>
                <p class="text-muted mb-0">결제 플랫폼 관리자 대시보드</p>
            </div>
            <div>
                <span class="text-muted">{{ now()->format('Y년 m월 d일') }}</span>
            </div>
        </div>
    </div>

    {{-- Summary Statistics Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">총 주문</p>
                            <h3 class="mb-0">1,234</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 12.5% from last month
                            </small>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-cart-check fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">총 결제</p>
                            <h3 class="mb-0">987</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 8.2% from last month
                            </small>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-credit-card fs-2 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">총 매출</p>
                            <h3 class="mb-0">₩5.2M</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 15.3% from last month
                            </small>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-currency-dollar fs-2 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">환불 건수</p>
                            <h3 class="mb-0">42</h3>
                            <small class="text-danger">
                                <i class="bi bi-arrow-down"></i> 3.1% from last month
                            </small>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-arrow-counterclockwise fs-2 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders & Payments --}}
    <div class="row g-4">
        {{-- Recent Orders --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">최근 주문</h5>
                        <a href="{{ url('/admin/orders') }}" class="btn btn-sm btn-outline-primary">전체보기</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>주문번호</th>
                                    <th>고객</th>
                                    <th>금액</th>
                                    <th>상태</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="text-primary">#ORD-10234</span></td>
                                    <td>김철수</td>
                                    <td>₩125,000</td>
                                    <td><span class="badge bg-success">완료</span></td>
                                </tr>
                                <tr>
                                    <td><span class="text-primary">#ORD-10233</span></td>
                                    <td>이영희</td>
                                    <td>₩89,000</td>
                                    <td><span class="badge bg-warning">처리중</span></td>
                                </tr>
                                <tr>
                                    <td><span class="text-primary">#ORD-10232</span></td>
                                    <td>박민수</td>
                                    <td>₩256,000</td>
                                    <td><span class="badge bg-success">완료</span></td>
                                </tr>
                                <tr>
                                    <td><span class="text-primary">#ORD-10231</span></td>
                                    <td>정수진</td>
                                    <td>₩42,000</td>
                                    <td><span class="badge bg-info">배송중</span></td>
                                </tr>
                                <tr>
                                    <td><span class="text-primary">#ORD-10230</span></td>
                                    <td>홍길동</td>
                                    <td>₩178,000</td>
                                    <td><span class="badge bg-success">완료</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Payments --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">최근 결제</h5>
                        <a href="{{ url('/admin/payments') }}" class="btn btn-sm btn-outline-primary">전체보기</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>결제번호</th>
                                    <th>PG</th>
                                    <th>금액</th>
                                    <th>상태</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="text-primary">#PAY-50123</span></td>
                                    <td>토스페이먼츠</td>
                                    <td>₩125,000</td>
                                    <td><span class="badge bg-success">승인</span></td>
                                </tr>
                                <tr>
                                    <td><span class="text-primary">#PAY-50122</span></td>
                                    <td>Mock PG</td>
                                    <td>₩89,000</td>
                                    <td><span class="badge bg-success">승인</span></td>
                                </tr>
                                <tr>
                                    <td><span class="text-primary">#PAY-50121</span></td>
                                    <td>토스페이먼츠</td>
                                    <td>₩256,000</td>
                                    <td><span class="badge bg-success">승인</span></td>
                                </tr>
                                <tr>
                                    <td><span class="text-primary">#PAY-50120</span></td>
                                    <td>Mock PG</td>
                                    <td>₩42,000</td>
                                    <td><span class="badge bg-danger">실패</span></td>
                                </tr>
                                <tr>
                                    <td><span class="text-primary">#PAY-50119</span></td>
                                    <td>토스페이먼츠</td>
                                    <td>₩178,000</td>
                                    <td><span class="badge bg-success">승인</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="row g-4 mt-2">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">PG별 거래 현황</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">토스페이먼츠</span>
                            <span class="fw-bold">65%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 65%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Mock PG</span>
                            <span class="fw-bold">35%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 35%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">주문 상태</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">완료</span>
                            <span class="fw-bold">820건</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 66%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">처리중</span>
                            <span class="fw-bold">314건</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 25%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">취소</span>
                            <span class="fw-bold">100건</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 9%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">빠른 링크</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ url('/admin/orders') }}" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="bi bi-cart-check text-primary me-2"></i> 주문 관리
                        </a>
                        <a href="{{ url('/admin/payments') }}" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="bi bi-credit-card text-success me-2"></i> 결제 내역
                        </a>
                        <a href="{{ url('/admin/statistics') }}" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="bi bi-graph-up text-info me-2"></i> 통계 조회
                        </a>
                        <a href="{{ url('/admin/products') }}" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="bi bi-box-seam text-warning me-2"></i> 상품 관리
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
