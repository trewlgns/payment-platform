@extends('layouts.admin')

@section('title', '주문 관리')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">주문 관리</h1>
            <p class="text-muted mb-0">전체 주문 내역 조회 및 관리</p>
        </div>
        <div>
            <button class="btn btn-outline-secondary" onclick="window.location.reload()">
                <i class="bi bi-arrow-clockwise me-1"></i> 새로고침
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">주문 상태</label>
                    <select class="form-select" name="status">
                        <option value="">전체</option>
                        <option value="pending">대기중</option>
                        <option value="paid">결제완료</option>
                        <option value="processing">처리중</option>
                        <option value="shipped">배송중</option>
                        <option value="delivered">배송완료</option>
                        <option value="cancelled">취소</option>
                        <option value="refunded">환불완료</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">주문일</label>
                    <input type="date" class="form-control" name="date_from">
                </div>
                <div class="col-md-3">
                    <label class="form-label">~</label>
                    <input type="date" class="form-control" name="date_to">
                </div>
                <div class="col-md-3">
                    <label class="form-label">검색</label>
                    <input type="text" class="form-control" placeholder="주문번호, 고객명" name="search">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> 조회
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> 초기화
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">주문 목록</h5>
                <span class="text-muted">총 <strong>1,234</strong>건</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover orders-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">
                                <input type="checkbox" class="form-check-input">
                            </th>
                            <th>주문번호</th>
                            <th>주문일시</th>
                            <th>고객명</th>
                            <th>상품</th>
                            <th>수량</th>
                            <th>주문금액</th>
                            <th>할인금액</th>
                            <th>최종금액</th>
                            <th>상태</th>
                            <th style="width: 120px;">관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Sample Data Row 1 --}}
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input">
                            </td>
                            <td>
                                <a href="#" class="text-primary fw-bold order-link" data-order-id="10234">
                                    #ORD-10234
                                </a>
                            </td>
                            <td>
                                <div>2025-12-28</div>
                                <small class="text-muted">14:32:15</small>
                            </td>
                            <td>
                                <div>김철수</div>
                                <small class="text-muted">chulsoo@example.com</small>
                            </td>
                            <td>Premium Wireless Headphones 외 1건</td>
                            <td>2</td>
                            <td>₩150,000</td>
                            <td>₩25,000</td>
                            <td class="fw-bold">₩125,000</td>
                            <td>
                                <span class="badge bg-success">배송완료</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-detail" data-order-id="10234">
                                    <i class="bi bi-eye"></i> 상세
                                </button>
                            </td>
                        </tr>

                        {{-- Sample Data Row 2 --}}
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input">
                            </td>
                            <td>
                                <a href="#" class="text-primary fw-bold order-link" data-order-id="10233">
                                    #ORD-10233
                                </a>
                            </td>
                            <td>
                                <div>2025-12-28</div>
                                <small class="text-muted">13:15:42</small>
                            </td>
                            <td>
                                <div>이영희</div>
                                <small class="text-muted">younghee@example.com</small>
                            </td>
                            <td>Smart Watch Pro</td>
                            <td>1</td>
                            <td>₩89,000</td>
                            <td>₩0</td>
                            <td class="fw-bold">₩89,000</td>
                            <td>
                                <span class="badge bg-warning">처리중</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-detail" data-order-id="10233">
                                    <i class="bi bi-eye"></i> 상세
                                </button>
                            </td>
                        </tr>

                        {{-- Sample Data Row 3 --}}
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input">
                            </td>
                            <td>
                                <a href="#" class="text-primary fw-bold order-link" data-order-id="10232">
                                    #ORD-10232
                                </a>
                            </td>
                            <td>
                                <div>2025-12-28</div>
                                <small class="text-muted">11:22:33</small>
                            </td>
                            <td>
                                <div>박민수</div>
                                <small class="text-muted">minsu@example.com</small>
                            </td>
                            <td>Laptop Stand Aluminum 외 2건</td>
                            <td>3</td>
                            <td>₩280,000</td>
                            <td>₩24,000</td>
                            <td class="fw-bold">₩256,000</td>
                            <td>
                                <span class="badge bg-info">배송중</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-detail" data-order-id="10232">
                                    <i class="bi bi-eye"></i> 상세
                                </button>
                            </td>
                        </tr>

                        {{-- Sample Data Row 4 --}}
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input">
                            </td>
                            <td>
                                <a href="#" class="text-primary fw-bold order-link" data-order-id="10231">
                                    #ORD-10231
                                </a>
                            </td>
                            <td>
                                <div>2025-12-27</div>
                                <small class="text-muted">18:45:12</small>
                            </td>
                            <td>
                                <div>정수진</div>
                                <small class="text-muted">sujin@example.com</small>
                            </td>
                            <td>Mechanical Keyboard RGB</td>
                            <td>1</td>
                            <td>₩42,000</td>
                            <td>₩0</td>
                            <td class="fw-bold">₩42,000</td>
                            <td>
                                <span class="badge bg-success">배송완료</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-detail" data-order-id="10231">
                                    <i class="bi bi-eye"></i> 상세
                                </button>
                            </td>
                        </tr>

                        {{-- Sample Data Row 5 --}}
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input">
                            </td>
                            <td>
                                <a href="#" class="text-primary fw-bold order-link" data-order-id="10230">
                                    #ORD-10230
                                </a>
                            </td>
                            <td>
                                <div>2025-12-27</div>
                                <small class="text-muted">16:30:25</small>
                            </td>
                            <td>
                                <div>홍길동</div>
                                <small class="text-muted">gildong@example.com</small>
                            </td>
                            <td>USB-C Hub 7-in-1</td>
                            <td>1</td>
                            <td>₩178,000</td>
                            <td>₩0</td>
                            <td class="fw-bold">₩178,000</td>
                            <td>
                                <span class="badge bg-danger">취소</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-detail" data-order-id="10230">
                                    <i class="bi bi-eye"></i> 상세
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

{{-- Order Detail Modal --}}
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">주문 상세 정보</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Order Info --}}
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">주문 정보</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>주문번호:</strong> <span id="modal-order-code">#ORD-10234</span></p>
                            <p><strong>주문일시:</strong> <span id="modal-order-date">2025-12-28 14:32:15</span></p>
                            <p><strong>주문상태:</strong> <span id="modal-order-status" class="badge bg-success">배송완료</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>고객명:</strong> <span id="modal-customer-name">김철수</span></p>
                            <p><strong>이메일:</strong> <span id="modal-customer-email">chulsoo@example.com</span></p>
                            <p><strong>연락처:</strong> <span id="modal-customer-phone">010-1234-5678</span></p>
                        </div>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">주문 상품</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>상품명</th>
                                <th>단가</th>
                                <th>수량</th>
                                <th>금액</th>
                            </tr>
                        </thead>
                        <tbody id="modal-order-items">
                            <tr>
                                <td>Premium Wireless Headphones</td>
                                <td>₩75,000</td>
                                <td>2</td>
                                <td>₩150,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Payment Info --}}
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">결제 정보</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>주문금액:</strong> <span id="modal-subtotal">₩150,000</span></p>
                            <p><strong>할인금액:</strong> <span id="modal-discount" class="text-danger">-₩25,000</span></p>
                            <p><strong>배송비:</strong> <span id="modal-shipping">₩0</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="fs-5"><strong>최종금액:</strong> <span id="modal-total" class="text-primary fw-bold">₩125,000</span></p>
                            <p><strong>결제방법:</strong> <span id="modal-payment-method">신용카드</span></p>
                            <p><strong>결제상태:</strong> <span id="modal-payment-status" class="badge bg-success">결제완료</span></p>
                        </div>
                    </div>
                </div>

                {{-- Shipping Info --}}
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">배송 정보</h6>
                    <p><strong>수령인:</strong> <span id="modal-recipient">김철수</span></p>
                    <p><strong>연락처:</strong> <span id="modal-recipient-phone">010-1234-5678</span></p>
                    <p><strong>배송지:</strong> <span id="modal-address">서울시 강남구 테헤란로 123, 456호</span></p>
                    <p><strong>배송메모:</strong> <span id="modal-memo">부재시 문앞에 놓아주세요</span></p>
                </div>

                {{-- Status Change --}}
                <div>
                    <h6 class="border-bottom pb-2 mb-3">상태 변경</h6>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <select class="form-select" id="modal-status-select">
                                <option value="pending">대기중</option>
                                <option value="paid">결제완료</option>
                                <option value="processing">처리중</option>
                                <option value="shipped">배송중</option>
                                <option value="delivered">배송완료</option>
                                <option value="cancelled">취소</option>
                                <option value="refunded">환불완료</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" id="btn-change-status">
                                <i class="bi bi-check-circle me-1"></i> 상태 변경
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-printer me-1"></i> 인쇄
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Detail Button Click
    document.querySelectorAll('.view-detail').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            // In real implementation, fetch order data via AJAX
            showOrderDetail(orderId);
        });
    });

    // Order Link Click
    document.querySelectorAll('.order-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const orderId = this.dataset.orderId;
            showOrderDetail(orderId);
        });
    });

    // Show Order Detail Modal
    function showOrderDetail(orderId) {
        // In real implementation, fetch data via AJAX
        // For now, just show the modal
        const modal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
        modal.show();
    }

    // Status Change Button
    document.getElementById('btn-change-status')?.addEventListener('click', function() {
        const newStatus = document.getElementById('modal-status-select').value;
        // In real implementation, send AJAX request to update status
        alert('상태가 변경되었습니다: ' + newStatus);
    });
});
</script>
@endpush
