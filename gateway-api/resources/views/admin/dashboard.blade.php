@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Dashboard</h1>
            <p class="text-muted">관리자 대시보드 - 사이드바 테스트 페이지</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">총 주문</h5>
                    <p class="card-text display-6">1,234</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">총 결제</h5>
                    <p class="card-text display-6">987</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">총 매출</h5>
                    <p class="card-text display-6">5.2M</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">환불 건수</h5>
                    <p class="card-text display-6">42</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">사이드바 메뉴 구조</h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Dashboard</li>
                        <li>주문 관리</li>
                        <li>결제 내역</li>
                        <li>환불 관리</li>
                        <li>통계</li>
                        <li>상품 관리</li>
                        <li>프로모션</li>
                        <li>쿠폰 관리</li>
                        <li>PG 관리</li>
                        <li>웹훅 이벤트</li>
                        <li>사용자 관리</li>
                        <li>설정</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
