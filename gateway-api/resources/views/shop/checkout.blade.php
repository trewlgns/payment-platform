@extends('layouts.shop')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/cart">Shopping Cart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <h2 class="mb-4">Checkout</h2>

    <div class="row">
        <!-- Checkout Form -->
        <div class="col-lg-8">
            <!-- Customer Information -->
            <div class="card checkout-section mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
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
                        Shipping Address
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="address" class="form-label">Street Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" required>
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="city" required>
                        </div>
                        <div class="col-md-6">
                            <label for="state" class="form-label">State/Province <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="state" required>
                        </div>
                        <div class="col-md-6">
                            <label for="zipCode" class="form-label">ZIP/Postal Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="zipCode" required>
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                            <select class="form-select" id="country" required>
                                <option value="">Select Country</option>
                                <option value="US" selected>United States</option>
                                <option value="KR">South Korea</option>
                                <option value="CA">Canada</option>
                                <option value="UK">United Kingdom</option>
                                <option value="JP">Japan</option>
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
                        Payment Method
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
                                        Credit/Debit Card
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
                                        Bank Transfer
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
                                        Virtual Account
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Card Payment Form -->
                    <div id="cardPaymentForm">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="cardNumber" class="form-label">Card Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            <div class="col-md-6">
                                <label for="cardExpiry" class="form-label">Expiry Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="col-md-6">
                                <label for="cardCvv" class="form-label">CVV <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cardCvv" placeholder="123" maxlength="4">
                            </div>
                            <div class="col-12">
                                <label for="cardName" class="form-label">Cardholder Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cardName" placeholder="JOHN DOE">
                            </div>
                        </div>
                    </div>

                    <!-- Bank Transfer Info (hidden by default) -->
                    <div id="bankPaymentInfo" class="d-none">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Bank transfer details will be provided after order confirmation.
                        </div>
                    </div>

                    <!-- Virtual Account Info (hidden by default) -->
                    <div id="virtualPaymentInfo" class="d-none">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Virtual account number will be issued after order confirmation.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="card checkout-section mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Order Notes (Optional)
                    </h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control" id="orderNotes" rows="3" placeholder="Special instructions for delivery..."></textarea>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card checkout-summary sticky-top" style="top: 20px;">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <!-- Order Items -->
                    <div id="orderItemsList" class="mb-3">
                        <!-- Items will be loaded here -->
                    </div>

                    <hr>

                    <!-- Price Breakdown -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal (<span id="summaryTotalItems">0</span> items)</span>
                        <span id="summarySubtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span id="summaryShipping">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tax (10%)</span>
                        <span id="summaryTax">$0.00</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-4">
                        <strong class="h5 mb-0">Total</strong>
                        <strong class="h4 mb-0 text-primary" id="summaryTotal">$0.00</strong>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                        <label class="form-check-label small" for="agreeTerms">
                            I agree to the <a href="#" class="text-primary">Terms & Conditions</a>
                        </label>
                    </div>

                    <!-- Place Order Button -->
                    <button class="btn btn-primary btn-lg w-100 mb-2" id="placeOrderBtn">
                        <i class="bi bi-lock-fill me-2"></i>
                        Place Order
                    </button>

                    <div class="text-center">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            Secure SSL Encrypted Payment
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
                <small class="text-muted">Qty: ${item.quantity}</small>
            </div>
            <div class="text-end">
                <div class="fw-bold">$${(item.price * item.quantity).toFixed(2)}</div>
            </div>
        </div>
    `).join('');

    // Update summary
    document.getElementById('summaryTotalItems').textContent = checkoutData.cart.items.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('summarySubtotal').textContent = `$${checkoutData.cart.subtotal.toFixed(2)}`;
    document.getElementById('summaryShipping').textContent = checkoutData.cart.shipping === 0 ? 'FREE' : `$${checkoutData.cart.shipping.toFixed(2)}`;
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
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        // Simulate payment processing
        setTimeout(() => {
            // TODO: Implement actual payment API call
            alert('Order placed successfully! (Demo mode)');

            // Clear cart
            localStorage.removeItem('cart');

            // Redirect to success page
            // window.location.href = '/order-success';

            // Reset button
            this.disabled = false;
            this.innerHTML = '<i class="bi bi-lock-fill me-2"></i>Place Order';
        }, 2000);
    });
}

// Validate checkout form
function validateCheckoutForm() {
    const agreeTerms = document.getElementById('agreeTerms');

    if (!agreeTerms.checked) {
        alert('Please agree to the Terms & Conditions');
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
            alert(`Please fill in ${field.previousElementSibling.textContent.replace('*', '').trim()}`);
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
                alert(`Please fill in ${field.previousElementSibling.textContent.replace('*', '').trim()}`);
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
