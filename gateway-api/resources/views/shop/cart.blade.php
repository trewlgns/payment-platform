@extends('layouts.shop')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
        </ol>
    </nav>

    <h2 class="mb-4">Shopping Cart</h2>

    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="card cart-items-card mb-4">
                <div class="card-body">
                    <!-- Cart Item Template -->
                    <div id="cartItemsContainer">
                        <!-- Items will be dynamically inserted here -->
                    </div>

                    <!-- Empty Cart Message -->
                    <div id="emptyCartMessage" class="text-center py-5 d-none">
                        <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                        <h4 class="text-muted">Your cart is empty</h4>
                        <p class="text-muted">Add some products to get started!</p>
                        <a href="/products" class="btn btn-primary">
                            <i class="bi bi-shop me-2"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card cart-summary-card sticky-top" style="top: 20px;">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal (<span id="totalItems">0</span> items)</span>
                        <span id="subtotalAmount">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span id="shippingAmount">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tax (10%)</span>
                        <span id="taxAmount">$0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                        <strong class="text-primary h5 mb-0" id="totalAmount">$0.00</strong>
                    </div>

                    <!-- Coupon Code -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control"
                                id="couponCode"
                                placeholder="Coupon code"
                            >
                            <button class="btn btn-outline-secondary" type="button" id="applyCouponBtn">
                                Apply
                            </button>
                        </div>
                        <small class="text-success d-none" id="couponSuccess">
                            <i class="bi bi-check-circle me-1"></i>
                            Coupon applied successfully!
                        </small>
                        <small class="text-danger d-none" id="couponError">
                            <i class="bi bi-x-circle me-1"></i>
                            Invalid coupon code
                        </small>
                    </div>

                    <button class="btn btn-primary w-100 mb-2" id="checkoutBtn">
                        <i class="bi bi-credit-card me-2"></i>
                        Proceed to Checkout
                    </button>
                    <a href="/products" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-2"></i>
                        Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-shield-check fs-4 text-success me-3"></i>
                        <div>
                            <div class="fw-semibold">Secure Checkout</div>
                            <small class="text-muted">SSL encrypted</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-arrow-clockwise fs-4 text-success me-3"></i>
                        <div>
                            <div class="fw-semibold">30-Day Returns</div>
                            <small class="text-muted">Money back guarantee</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-truck fs-4 text-success me-3"></i>
                        <div>
                            <div class="fw-semibold">Free Shipping</div>
                            <small class="text-muted">On orders over $50</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Cart state management
let cart = {
    items: [],
    subtotal: 0,
    shipping: 0,
    tax: 0,
    total: 0,
    couponDiscount: 0
};

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
    renderCart();
});

// Load cart from localStorage
function loadCart() {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cart.items = JSON.parse(savedCart);
    } else {
        // Mock data for demo
        cart.items = [
            {
                id: 1,
                product_code: 'PROD-001',
                name: 'Premium Wireless Headphones',
                price: 149.99,
                quantity: 1,
                image: 'https://via.placeholder.com/100?text=Product+1'
            },
            {
                id: 2,
                product_code: 'PROD-002',
                name: 'Smart Watch Pro',
                price: 299.99,
                quantity: 2,
                image: 'https://via.placeholder.com/100?text=Product+2'
            },
            {
                id: 3,
                product_code: 'PROD-003',
                name: 'Bluetooth Speaker',
                price: 79.99,
                quantity: 1,
                image: 'https://via.placeholder.com/100?text=Product+3'
            }
        ];
    }
    calculateTotals();
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart.items));
}

// Calculate totals
function calculateTotals() {
    cart.subtotal = cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    cart.shipping = cart.subtotal >= 50 ? 0 : 9.99;
    cart.tax = (cart.subtotal + cart.shipping) * 0.1;
    cart.total = cart.subtotal + cart.shipping + cart.tax - cart.couponDiscount;
}

// Render cart items
function renderCart() {
    const container = document.getElementById('cartItemsContainer');
    const emptyMessage = document.getElementById('emptyCartMessage');

    if (cart.items.length === 0) {
        container.innerHTML = '';
        emptyMessage.classList.remove('d-none');
        updateSummary();
        return;
    }

    emptyMessage.classList.add('d-none');

    container.innerHTML = cart.items.map((item, index) => `
        <div class="cart-item mb-3 pb-3 border-bottom" data-index="${index}">
            <div class="row align-items-center">
                <div class="col-md-2 col-3">
                    <img src="${item.image}" alt="${item.name}" class="img-fluid rounded">
                </div>
                <div class="col-md-4 col-9">
                    <h6 class="mb-1">${item.name}</h6>
                    <p class="text-muted small mb-0">SKU: ${item.product_code}</p>
                    <p class="text-primary fw-bold mb-0">$${item.price.toFixed(2)}</p>
                </div>
                <div class="col-md-3 col-6 mt-3 mt-md-0">
                    <div class="input-group input-group-sm cart-quantity-control">
                        <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity(${index})">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input
                            type="number"
                            class="form-control text-center"
                            value="${item.quantity}"
                            min="1"
                            max="99"
                            onchange="updateQuantity(${index}, this.value)"
                        >
                        <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity(${index})">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2 col-4 mt-3 mt-md-0 text-md-end">
                    <div class="fw-bold">$${(item.price * item.quantity).toFixed(2)}</div>
                </div>
                <div class="col-md-1 col-2 mt-3 mt-md-0 text-end">
                    <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})" title="Remove">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');

    updateSummary();
}

// Update summary display
function updateSummary() {
    document.getElementById('totalItems').textContent = cart.items.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('subtotalAmount').textContent = `$${cart.subtotal.toFixed(2)}`;
    document.getElementById('shippingAmount').textContent = cart.shipping === 0 ? 'FREE' : `$${cart.shipping.toFixed(2)}`;
    document.getElementById('taxAmount').textContent = `$${cart.tax.toFixed(2)}`;
    document.getElementById('totalAmount').textContent = `$${cart.total.toFixed(2)}`;
}

// Increase quantity
function increaseQuantity(index) {
    if (cart.items[index].quantity < 99) {
        cart.items[index].quantity++;
        calculateTotals();
        saveCart();
        renderCart();
    }
}

// Decrease quantity
function decreaseQuantity(index) {
    if (cart.items[index].quantity > 1) {
        cart.items[index].quantity--;
        calculateTotals();
        saveCart();
        renderCart();
    }
}

// Update quantity
function updateQuantity(index, value) {
    const qty = parseInt(value);
    if (qty >= 1 && qty <= 99) {
        cart.items[index].quantity = qty;
        calculateTotals();
        saveCart();
        renderCart();
    }
}

// Remove item
function removeItem(index) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        cart.items.splice(index, 1);
        calculateTotals();
        saveCart();
        renderCart();

        // Show toast notification
        showToast('Item removed from cart', 'success');
    }
}

// Apply coupon
document.getElementById('applyCouponBtn').addEventListener('click', function() {
    const couponCode = document.getElementById('couponCode').value.trim();
    const successMsg = document.getElementById('couponSuccess');
    const errorMsg = document.getElementById('couponError');

    // Hide previous messages
    successMsg.classList.add('d-none');
    errorMsg.classList.add('d-none');

    if (!couponCode) {
        return;
    }

    // Mock coupon validation
    const validCoupons = {
        'SAVE10': 10,
        'SAVE20': 20,
        'FREESHIP': 0
    };

    if (validCoupons.hasOwnProperty(couponCode.toUpperCase())) {
        const discount = validCoupons[couponCode.toUpperCase()];

        if (couponCode.toUpperCase() === 'FREESHIP') {
            cart.shipping = 0;
        } else {
            cart.couponDiscount = cart.subtotal * (discount / 100);
        }

        calculateTotals();
        updateSummary();
        successMsg.classList.remove('d-none');
        document.getElementById('couponCode').value = '';
    } else {
        errorMsg.classList.remove('d-none');
    }
});

// Checkout button
document.getElementById('checkoutBtn').addEventListener('click', function() {
    if (cart.items.length === 0) {
        alert('Your cart is empty!');
        return;
    }

    // TODO: Implement checkout redirect
    window.location.href = '/checkout';
});

// Toast notification helper
function showToast(message, type = 'info') {
    // Simple alert for now - can be replaced with a proper toast library
    console.log(`[${type.toUpperCase()}] ${message}`);
}
</script>
@endpush
