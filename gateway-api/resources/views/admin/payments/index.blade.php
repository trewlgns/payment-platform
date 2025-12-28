@extends('layouts.admin')

@section('title', '결제 내역')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">결제 내역</h1>
            <p class="text-muted mb-0">전체 결제 거래 내역을 조회하고 관리합니다</p>
        </div>
        <div>
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="bi bi-printer"></i> 인쇄
            </button>
            <button class="btn btn-primary">
                <i class="bi bi-download"></i> 엑셀 다운로드
            </button>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ url('/admin/payments') }}" class="row g-3">
                <div class="col-md-2">
                    <label for="filterStatus" class="form-label">결제 상태</label>
                    <select class="form-select" id="filterStatus" name="status">
                        <option value="">전체</option>
                        <option value="pending">대기</option>
                        <option value="approved">승인</option>
                        <option value="failed">실패</option>
                        <option value="cancelled">취소</option>
                        <option value="refunded">환불</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterPg" class="form-label">PG사</label>
                    <select class="form-select" id="filterPg" name="pg_provider">
                        <option value="">전체</option>
                        <option value="toss">토스페이먼츠</option>
                        <option value="mock">Mock PG</option>
                        <option value="kakaopay">카카오페이</option>
                        <option value="naverpay">네이버페이</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterMethod" class="form-label">결제 수단</label>
                    <select class="form-select" id="filterMethod" name="payment_method">
                        <option value="">전체</option>
                        <option value="card">신용카드</option>
                        <option value="transfer">계좌이체</option>
                        <option value="vbank">가상계좌</option>
                        <option value="phone">휴대폰</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterStartDate" class="form-label">시작일</label>
                    <input type="date" class="form-control" id="filterStartDate" name="start_date">
                </div>
                <div class="col-md-2">
                    <label for="filterEndDate" class="form-label">종료일</label>
                    <input type="date" class="form-control" id="filterEndDate" name="end_date">
                </div>
                <div class="col-md-2">
                    <label for="filterMinAmount" class="form-label">최소 금액</label>
                    <input type="number" class="form-control" id="filterMinAmount" name="min_amount" placeholder="0">
                </div>
                <div class="col-md-2">
                    <label for="filterMaxAmount" class="form-label">최대 금액</label>
                    <input type="number" class="form-control" id="filterMaxAmount" name="max_amount" placeholder="999999999">
                </div>
                <div class="col-md-3">
                    <label for="filterSearch" class="form-label">검색</label>
                    <input type="text" class="form-control" id="filterSearch" name="search" placeholder="결제번호, 주문번호, 고객명">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> 조회
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ url('/admin/payments') }}'">
                        <i class="bi bi-arrow-clockwise"></i> 초기화
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">결제 내역 목록</h5>
                <span class="text-muted">총 <strong>1,234</strong>건</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover payments-table mb-0">
                    <thead>
                        <tr>
                            <th>결제번호</th>
                            <th>주문번호</th>
                            <th>결제일시</th>
                            <th>고객명</th>
                            <th>PG사</th>
                            <th>결제수단</th>
                            <th>결제금액</th>
                            <th>상태</th>
                            <th>승인번호</th>
                            <th>작업</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="payment-link" data-payment-id="PAY-50234" onclick="showPaymentDetail('PAY-50234')">
                                    #PAY-50234
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('/admin/orders') }}" class="text-decoration-none">
                                    #ORD-10234
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">2025-01-15</div>
                                <small class="text-muted">14:32:15</small>
                            </td>
                            <td>김철수</td>
                            <td>토스페이먼츠</td>
                            <td>
                                <i class="bi bi-credit-card text-primary"></i>
                                신용카드
                            </td>
                            <td class="fw-bold">₩125,000</td>
                            <td><span class="badge bg-approved">승인</span></td>
                            <td>
                                <small class="text-muted font-monospace">12345678</small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="showPaymentDetail('PAY-50234')">
                                    상세
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="payment-link" data-payment-id="PAY-50233" onclick="showPaymentDetail('PAY-50233')">
                                    #PAY-50233
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('/admin/orders') }}" class="text-decoration-none">
                                    #ORD-10233
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">2025-01-15</div>
                                <small class="text-muted">13:21:45</small>
                            </td>
                            <td>이영희</td>
                            <td>Mock PG</td>
                            <td>
                                <i class="bi bi-bank text-success"></i>
                                계좌이체
                            </td>
                            <td class="fw-bold">₩89,000</td>
                            <td><span class="badge bg-approved">승인</span></td>
                            <td>
                                <small class="text-muted font-monospace">87654321</small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="showPaymentDetail('PAY-50233')">
                                    상세
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="payment-link" data-payment-id="PAY-50232" onclick="showPaymentDetail('PAY-50232')">
                                    #PAY-50232
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('/admin/orders') }}" class="text-decoration-none">
                                    #ORD-10232
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">2025-01-15</div>
                                <small class="text-muted">12:15:30</small>
                            </td>
                            <td>박민수</td>
                            <td>토스페이먼츠</td>
                            <td>
                                <i class="bi bi-credit-card text-primary"></i>
                                신용카드
                            </td>
                            <td class="fw-bold">₩256,000</td>
                            <td><span class="badge bg-approved">승인</span></td>
                            <td>
                                <small class="text-muted font-monospace">45678912</small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="showPaymentDetail('PAY-50232')">
                                    상세
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="payment-link" data-payment-id="PAY-50231" onclick="showPaymentDetail('PAY-50231')">
                                    #PAY-50231
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('/admin/orders') }}" class="text-decoration-none">
                                    #ORD-10231
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">2025-01-15</div>
                                <small class="text-muted">11:45:22</small>
                            </td>
                            <td>정수진</td>
                            <td>카카오페이</td>
                            <td>
                                <i class="bi bi-phone text-warning"></i>
                                휴대폰
                            </td>
                            <td class="fw-bold">₩42,000</td>
                            <td><span class="badge bg-failed">실패</span></td>
                            <td>
                                <small class="text-muted">-</small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="showPaymentDetail('PAY-50231')">
                                    상세
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="payment-link" data-payment-id="PAY-50230" onclick="showPaymentDetail('PAY-50230')">
                                    #PAY-50230
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('/admin/orders') }}" class="text-decoration-none">
                                    #ORD-10230
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">2025-01-14</div>
                                <small class="text-muted">18:20:10</small>
                            </td>
                            <td>홍길동</td>
                            <td>토스페이먼츠</td>
                            <td>
                                <i class="bi bi-credit-card text-primary"></i>
                                신용카드
                            </td>
                            <td class="fw-bold">₩178,000</td>
                            <td><span class="badge bg-approved">승인</span></td>
                            <td>
                                <small class="text-muted font-monospace">78912345</small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="showPaymentDetail('PAY-50230')">
                                    상세
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="payment-link" data-payment-id="PAY-50229" onclick="showPaymentDetail('PAY-50229')">
                                    #PAY-50229
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('/admin/orders') }}" class="text-decoration-none">
                                    #ORD-10229
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">2025-01-14</div>
                                <small class="text-muted">16:55:33</small>
                            </td>
                            <td>최지우</td>
                            <td>네이버페이</td>
                            <td>
                                <i class="bi bi-wallet2 text-info"></i>
                                가상계좌
                            </td>
                            <td class="fw-bold">₩320,000</td>
                            <td><span class="badge bg-cancelled">취소</span></td>
                            <td>
                                <small class="text-muted">-</small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="showPaymentDetail('PAY-50229')">
                                    상세
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="payment-link" data-payment-id="PAY-50228" onclick="showPaymentDetail('PAY-50228')">
                                    #PAY-50228
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('/admin/orders') }}" class="text-decoration-none">
                                    #ORD-10228
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">2025-01-14</div>
                                <small class="text-muted">15:30:45</small>
                            </td>
                            <td>강민호</td>
                            <td>Mock PG</td>
                            <td>
                                <i class="bi bi-credit-card text-primary"></i>
                                신용카드
                            </td>
                            <td class="fw-bold">₩67,500</td>
                            <td><span class="badge bg-refunded">환불</span></td>
                            <td>
                                <small class="text-muted font-monospace">91234567</small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="showPaymentDetail('PAY-50228')">
                                    상세
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="payment-link" data-payment-id="PAY-50227" onclick="showPaymentDetail('PAY-50227')">
                                    #PAY-50227
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('/admin/orders') }}" class="text-decoration-none">
                                    #ORD-10227
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">2025-01-14</div>
                                <small class="text-muted">14:12:18</small>
                            </td>
                            <td>윤서연</td>
                            <td>토스페이먼츠</td>
                            <td>
                                <i class="bi bi-bank text-success"></i>
                                계좌이체
                            </td>
                            <td class="fw-bold">₩145,000</td>
                            <td><span class="badge bg-approved">승인</span></td>
                            <td>
                                <small class="text-muted font-monospace">34567891</small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="showPaymentDetail('PAY-50227')">
                                    상세
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">이전</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">다음</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

{{-- Payment Detail Modal --}}
<div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-labelledby="paymentDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentDetailModalLabel">결제 상세 정보</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Payment Basic Info --}}
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">결제 기본 정보</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>결제번호:</strong> <span id="detailPaymentId">#PAY-50234</span></p>
                            <p><strong>주문번호:</strong> <span id="detailOrderId">#ORD-10234</span></p>
                            <p><strong>결제일시:</strong> <span id="detailPaymentDate">2025-01-15 14:32:15</span></p>
                            <p><strong>결제상태:</strong> <span id="detailPaymentStatus" class="badge bg-approved">승인</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>PG사:</strong> <span id="detailPgProvider">토스페이먼츠</span></p>
                            <p><strong>결제수단:</strong> <span id="detailPaymentMethod">신용카드</span></p>
                            <p><strong>결제금액:</strong> <span id="detailAmount" class="text-primary fw-bold">₩125,000</span></p>
                            <p><strong>승인번호:</strong> <span id="detailApprovalNo">12345678</span></p>
                        </div>
                    </div>
                </div>

                {{-- Customer Info --}}
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">고객 정보</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>고객명:</strong> <span id="detailCustomerName">김철수</span></p>
                            <p><strong>이메일:</strong> <span id="detailCustomerEmail">customer@example.com</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>연락처:</strong> <span id="detailCustomerPhone">010-1234-5678</span></p>
                        </div>
                    </div>
                </div>

                {{-- Card Info (for card payments) --}}
                <div class="mb-4" id="cardInfoSection">
                    <h6 class="border-bottom pb-2 mb-3">카드 정보</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>카드사:</strong> <span id="detailCardIssuer">신한카드</span></p>
                            <p><strong>카드번호:</strong> <span id="detailCardNumber">1234-****-****-5678</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>할부:</strong> <span id="detailInstallment">일시불</span></p>
                            <p><strong>카드타입:</strong> <span id="detailCardType">개인신용</span></p>
                        </div>
                    </div>
                </div>

                {{-- Transaction Info --}}
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">거래 정보</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>PG 거래번호:</strong> <span id="detailPgTid" class="font-monospace small">TID1234567890ABCDEF</span></p>
                            <p><strong>요청일시:</strong> <span id="detailRequestedAt">2025-01-15 14:32:10</span></p>
                            <p><strong>승인일시:</strong> <span id="detailApprovedAt">2025-01-15 14:32:15</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>처리시간:</strong> <span id="detailProcessTime">5초</span></p>
                            <p><strong>IP 주소:</strong> <span id="detailIpAddress">192.168.1.100</span></p>
                            <p><strong>User Agent:</strong> <span id="detailUserAgent" class="small text-muted">Mozilla/5.0...</span></p>
                        </div>
                    </div>
                </div>

                {{-- Error Info (for failed payments) --}}
                <div class="mb-4 d-none" id="errorInfoSection">
                    <h6 class="border-bottom pb-2 mb-3 text-danger">오류 정보</h6>
                    <div class="alert alert-danger">
                        <p class="mb-1"><strong>오류 코드:</strong> <span id="detailErrorCode">-</span></p>
                        <p class="mb-0"><strong>오류 메시지:</strong> <span id="detailErrorMessage">-</span></p>
                    </div>
                </div>

                {{-- Refund Info (for refunded payments) --}}
                <div class="mb-4 d-none" id="refundInfoSection">
                    <h6 class="border-bottom pb-2 mb-3 text-warning">환불 정보</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>환불일시:</strong> <span id="detailRefundedAt">-</span></p>
                            <p><strong>환불금액:</strong> <span id="detailRefundAmount">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>환불사유:</strong> <span id="detailRefundReason">-</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> 인쇄
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showPaymentDetail(paymentId) {
    // TODO: AJAX call to fetch payment details
    // For now, just show the modal with sample data
    const modal = new bootstrap.Modal(document.getElementById('paymentDetailModal'));
    modal.show();

    // Sample data - replace with actual AJAX response
    if (paymentId === 'PAY-50231') {
        // Show error info for failed payment
        document.getElementById('errorInfoSection').classList.remove('d-none');
        document.getElementById('cardInfoSection').classList.add('d-none');
        document.getElementById('refundInfoSection').classList.add('d-none');
        document.getElementById('detailErrorCode').textContent = 'E001';
        document.getElementById('detailErrorMessage').textContent = '카드 한도 초과';
    } else if (paymentId === 'PAY-50228') {
        // Show refund info for refunded payment
        document.getElementById('refundInfoSection').classList.remove('d-none');
        document.getElementById('errorInfoSection').classList.add('d-none');
        document.getElementById('detailRefundedAt').textContent = '2025-01-15 10:30:00';
        document.getElementById('detailRefundAmount').textContent = '₩67,500';
        document.getElementById('detailRefundReason').textContent = '고객 요청';
    } else {
        // Hide error and refund sections for successful payments
        document.getElementById('errorInfoSection').classList.add('d-none');
        document.getElementById('refundInfoSection').classList.add('d-none');
        document.getElementById('cardInfoSection').classList.remove('d-none');
    }
}
</script>
@endpush
