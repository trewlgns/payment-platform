{{-- Shop Footer Component --}}

<footer class="shop-footer bg-dark text-light py-5">
    <div class="container">
        <div class="row">
            {{-- About Section --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-shop"></i>
                    Payment Shop
                </h5>
                <p class="text-muted">
                    A modern multi-PG payment integration platform built with Laravel, demonstrating enterprise-level architecture and payment gateway patterns.
                </p>
                <div class="social-links mt-3">
                    <a href="#" class="text-light me-3" aria-label="GitHub">
                        <i class="bi bi-github fs-4"></i>
                    </a>
                    <a href="#" class="text-light me-3" aria-label="LinkedIn">
                        <i class="bi bi-linkedin fs-4"></i>
                    </a>
                    <a href="#" class="text-light me-3" aria-label="Twitter">
                        <i class="bi bi-twitter fs-4"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/" class="text-muted text-decoration-none hover-primary">Home</a></li>
                    <li class="mb-2"><a href="/products" class="text-muted text-decoration-none hover-primary">Products</a></li>
                    <li class="mb-2"><a href="/cart" class="text-muted text-decoration-none hover-primary">Cart</a></li>
                    <li class="mb-2"><a href="/orders" class="text-muted text-decoration-none hover-primary">My Orders</a></li>
                </ul>
            </div>

            {{-- Customer Support --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Customer Support</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/help" class="text-muted text-decoration-none hover-primary">Help Center</a></li>
                    <li class="mb-2"><a href="/faq" class="text-muted text-decoration-none hover-primary">FAQ</a></li>
                    <li class="mb-2"><a href="/shipping" class="text-muted text-decoration-none hover-primary">Shipping Info</a></li>
                    <li class="mb-2"><a href="/returns" class="text-muted text-decoration-none hover-primary">Returns</a></li>
                    <li class="mb-2"><a href="/contact" class="text-muted text-decoration-none hover-primary">Contact Us</a></li>
                </ul>
            </div>

            {{-- Contact Info --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Contact Info</h6>
                <ul class="list-unstyled">
                    <li class="mb-2 text-muted">
                        <i class="bi bi-envelope me-2"></i>
                        support@paymentshop.com
                    </li>
                    <li class="mb-2 text-muted">
                        <i class="bi bi-telephone me-2"></i>
                        +82 10-1234-5678
                    </li>
                    <li class="mb-2 text-muted">
                        <i class="bi bi-geo-alt me-2"></i>
                        Seoul, South Korea
                    </li>
                </ul>
                <div class="mt-3">
                    <small class="text-muted">Business Hours:</small><br>
                    <small class="text-muted">Mon - Fri: 9:00 AM - 6:00 PM</small>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <hr class="border-secondary my-4">

        {{-- Bottom Footer --}}
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <small class="text-muted">
                    &copy; {{ date('Y') }} Payment Shop. All rights reserved.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <small class="text-muted">
                    <a href="/terms" class="text-muted text-decoration-none hover-primary me-3">Terms of Service</a>
                    <a href="/privacy" class="text-muted text-decoration-none hover-primary">Privacy Policy</a>
                </small>
            </div>
        </div>
    </div>
</footer>
