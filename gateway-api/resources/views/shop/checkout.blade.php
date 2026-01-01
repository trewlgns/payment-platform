@extends('layouts.shop')

@section('title', '결제')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">홈</a></li>
            <li class="breadcrumb-item"><a href="/cart">장바구니</a></li>
            <li class="breadcrumb-item active" aria-current="page">결제</li>
        </ol>
    </nav>

    <h2 class="mb-4">결제</h2>

    <div class="row">
        <!-- Checkout Form -->
        <div class="col-lg-8">
            <!-- Customer Information -->
            <div class="card checkout-section mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        고객 정보
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">이름 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">성 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">이메일 <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">전화번호 <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card checkout-section mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        배송지 정보
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="address" class="form-label">주소 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" required>
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">시/군/구 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="city" required>
                        </div>
                        <div class="col-md-6">
                            <label for="state" class="form-label">시/도 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="state" required>
                        </div>
                        <div class="col-md-6">
                            <label for="zipCode" class="form-label">우편번호 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="zipCode" required>
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">국가 <span class="text-danger">*</span></label>
                            <select class="form-select" id="country" required>
                                <option value="">국가 선택</option>
                                <option value="US" selected>미국</option>
                                <option value="KR">대한민국</option>
                                <option value="CA">캐나다</option>
                                <option value="UK">영국</option>
                                <option value="JP">일본</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="card checkout-section mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-credit-card-2-front me-2"></i>
                        결제 수단
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Payment Method Selection -->
                    <div class="payment-methods mb-4">
                        <div class="form-check payment-method-option mb-3">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="paymentCard" value="card" checked>
                            <label class="form-check-label w-100" for="paymentCard">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-credit-card me-2"></i>
                                        신용/체크 카드
                                    </div>
                                    <div class="payment-logos">
                                        <i class="bi bi-credit-card-fill text-primary"></i>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="form-check payment-method-option mb-3">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="paymentBank" value="bank">
                            <label class="form-check-label w-100" for="paymentBank">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-bank me-2"></i>
                                        계좌이체
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="form-check payment-method-option">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="paymentVirtual" value="virtual">
                            <label class="form-check-label w-100" for="paymentVirtual">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-wallet2 me-2"></i>
                                        가상계좌
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Card Payment Form -->
                    <div id="cardPaymentForm">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="cardNumber" class="form-label">카드 번호 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            <div class="col-md-6">
                                <label for="cardExpiry" class="form-label">유효기간 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="col-md-6">
                                <label for="cardCvv" class="form-label">CVV <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cardCvv" placeholder="123" maxlength="4">
                            </div>
                            <div class="col-12">
                                <label for="cardName" class="form-label">카드 소유자명 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cardName" placeholder="홍길동">
                            </div>
                        </div>
                    </div>

                    <!-- Bank Transfer Info (hidden by default) -->
                    <div id="bankPaymentInfo" class="d-none">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            주문 확인 후 계좌이체 정보가 제공됩니다.
                        </div>
                    </div>

                    <!-- Virtual Account Info (hidden by default) -->
                    <div id="virtualPaymentInfo" class="d-none">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            주문 확인 후 가상계좌 번호가 발급됩니다.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="card checkout-section mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-left-text me-2"></i>
                        주문 메모 (선택사항)
                    </h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control" id="orderNotes" rows="3" placeholder="배송 시 요청사항을 입력해주세요..."></textarea>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card checkout-summary sticky-top" style="top: 20px;">
                <div class="card-header bg-white">
                    <h5 class="mb-0">주문 요약</h5>
                </div>
                <div class="card-body">
                    <!-- Order Items -->
                    <div id="orderItemsList" class="mb-3">
                        <!-- Items will be loaded here -->
                    </div>

                    <hr>

                    <!-- Price Breakdown -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">소계 (<span id="summaryTotalItems">0</span>개)</span>
                        <span id="summarySubtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">배송비</span>
                        <span id="summaryShipping">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">세금 (10%)</span>
                        <span id="summaryTax">$0.00</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-4">
                        <strong class="h5 mb-0">합계</strong>
                        <strong class="h4 mb-0 text-primary" id="summaryTotal">$0.00</strong>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                        <label class="form-check-label small" for="agreeTerms">
                            <a href="#" class="text-primary">이용약관</a>에 동의합니다
                        </label>
                    </div>

                    <!-- Place Order Button -->
                    <button class="btn btn-primary btn-lg w-100 mb-2" id="placeOrderBtn">
                        <i class="bi bi-lock-fill me-2"></i>
                        주문하기
                    </button>

                    <div class="text-center">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            SSL 암호화 보안 결제
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Load cart data
let checkoutData = {
    cart: {
        items: [],
        subtotal: 0,
        shipping: 0,
        tax: 0,
        total: 0
    },
    customer: {},
    shipping: {},
    payment: {}
};

document.addEventListener('DOMContentLoaded', function() {
    loadCartData();
    renderOrderSummary();
    setupPaymentMethodToggle();
    setupCardNumberFormatting();
    setupPlaceOrderButton();
});

// Load cart from localStorage
function loadCartData() {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        checkoutData.cart.items = JSON.parse(savedCart);
    } else {
        // Mock data for demo
        checkoutData.cart.items = [
            {
                id: 1,
                product_code: 'PROD-001',
                name: 'Premium Wireless Headphones',
                price: 149.99,
                quantity: 1,
                image: 'https://via.placeholder.com/80?text=Product+1'
            },
            {
                id: 2,
                product_code: 'PROD-002',
                name: 'Smart Watch Pro',
                price: 299.99,
                quantity: 2,
                image: 'https://via.placeholder.com/80?text=Product+2'
            }
        ];
    }

    calculateTotals();
}

// Calculate totals
function calculateTotals() {
    checkoutData.cart.subtotal = checkoutData.cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    checkoutData.cart.shipping = checkoutData.cart.subtotal >= 50 ? 0 : 9.99;
    checkoutData.cart.tax = (checkoutData.cart.subtotal + checkoutData.cart.shipping) * 0.1;
    checkoutData.cart.total = checkoutData.cart.subtotal + checkoutData.cart.shipping + checkoutData.cart.tax;
}

// Render order summary
function renderOrderSummary() {
    const container = document.getElementById('orderItemsList');

    container.innerHTML = checkoutData.cart.items.map(item => `
        <div class="d-flex align-items-center mb-3">
            <img src="${item.image}" alt="${item.name}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
            <div class="flex-grow-1">
                <h6 class="mb-0 small">${item.name}</h6>
                <small class="text-muted">수량: ${item.quantity}</small>
            </div>
            <div class="text-end">
                <div class="fw-bold">$${(item.price * item.quantity).toFixed(2)}</div>
            </div>
        </div>
    `).join('');

    // Update summary
    document.getElementById('summaryTotalItems').textContent = checkoutData.cart.items.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('summarySubtotal').textContent = `$${checkoutData.cart.subtotal.toFixed(2)}`;
    document.getElementById('summaryShipping').textContent = checkoutData.cart.shipping === 0 ? '무료' : `$${checkoutData.cart.shipping.toFixed(2)}`;
    document.getElementById('summaryTax').textContent = `$${checkoutData.cart.tax.toFixed(2)}`;
    document.getElementById('summaryTotal').textContent = `$${checkoutData.cart.total.toFixed(2)}`;
}

// Setup payment method toggle
function setupPaymentMethodToggle() {
    const cardForm = document.getElementById('cardPaymentForm');
    const bankInfo = document.getElementById('bankPaymentInfo');
    const virtualInfo = document.getElementById('virtualPaymentInfo');

    document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Hide all
            cardForm.classList.add('d-none');
            bankInfo.classList.add('d-none');
            virtualInfo.classList.add('d-none');

            // Show selected
            if (this.value === 'card') {
                cardForm.classList.remove('d-none');
            } else if (this.value === 'bank') {
                bankInfo.classList.remove('d-none');
            } else if (this.value === 'virtual') {
                virtualInfo.classList.remove('d-none');
            }
        });
    });
}

// Card number formatting
function setupCardNumberFormatting() {
    const cardNumberInput = document.getElementById('cardNumber');
    const cardExpiryInput = document.getElementById('cardExpiry');

    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });

    cardExpiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    });
}

// Place order button
function setupPlaceOrderButton() {
    document.getElementById('placeOrderBtn').addEventListener('click', function() {
        // Validate form
        if (!validateCheckoutForm()) {
            return;
        }

        // Collect data
        collectCheckoutData();

        // Show loading
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>처리중...';

        // Simulate payment processing
        setTimeout(() => {
            // TODO: 실제 결제 API 호출 구현
            alert('주문이 성공적으로 완료되었습니다! (데모 모드)');

            // Clear cart
            localStorage.removeItem('cart');

            // Redirect to success page
            // window.location.href = '/order-success';

            // Reset button
            this.disabled = false;
            this.innerHTML = '<i class="bi bi-lock-fill me-2"></i>주문하기';
        }, 2000);
    });
}

// Validate checkout form
function validateCheckoutForm() {
    const agreeTerms = document.getElementById('agreeTerms');

    if (!agreeTerms.checked) {
        alert('이용약관에 동의해주세요');
        return false;
    }

    // Validate required fields
    const requiredFields = [
        'firstName', 'lastName', 'email', 'phone',
        'address', 'city', 'state', 'zipCode', 'country'
    ];

    for (const fieldId of requiredFields) {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
            alert(`${field.previousElementSibling.textContent.replace('*', '').trim()}을(를) 입력해주세요`);
            field.focus();
            return false;
        }
    }

    // Validate card details if card payment is selected
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
    if (paymentMethod === 'card') {
        const cardFields = ['cardNumber', 'cardExpiry', 'cardCvv', 'cardName'];
        for (const fieldId of cardFields) {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                alert(`${field.previousElementSibling.textContent.replace('*', '').trim()}을(를) 입력해주세요`);
                field.focus();
                return false;
            }
        }
    }

    return true;
}

// Collect checkout data
function collectCheckoutData() {
    checkoutData.customer = {
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value
    };

    checkoutData.shipping = {
        address: document.getElementById('address').value,
        city: document.getElementById('city').value,
        state: document.getElementById('state').value,
        zipCode: document.getElementById('zipCode').value,
        country: document.getElementById('country').value
    };

    checkoutData.payment = {
        method: document.querySelector('input[name="paymentMethod"]:checked').value,
        cardNumber: document.getElementById('cardNumber')?.value || '',
        cardExpiry: document.getElementById('cardExpiry')?.value || '',
        cardCvv: document.getElementById('cardCvv')?.value || '',
        cardName: document.getElementById('cardName')?.value || ''
    };

    checkoutData.orderNotes = document.getElementById('orderNotes').value;

    console.log('Checkout Data:', checkoutData);
}
</script>
@endpush
