<footer class="footer mt-5">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-wallet2"></i> Payment Platform
                </h5>
                <p class="text-muted">
                    Multi PG integration payment platform with advanced analytics and seamless checkout experience.
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-light">
                        <i class="bi bi-github fs-4"></i>
                    </a>
                    <a href="#" class="text-light">
                        <i class="bi bi-linkedin fs-4"></i>
                    </a>
                    <a href="#" class="text-light">
                        <i class="bi bi-envelope fs-4"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ url('/') }}" class="text-light text-decoration-none">
                            <i class="bi bi-chevron-right"></i> Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/products') }}" class="text-light text-decoration-none">
                            <i class="bi bi-chevron-right"></i> Products
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/orders') }}" class="text-light text-decoration-none">
                            <i class="bi bi-chevron-right"></i> Orders
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/admin') }}" class="text-light text-decoration-none">
                            <i class="bi bi-chevron-right"></i> Admin Dashboard
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-md-4">
                <h5 class="fw-bold mb-3">Contact</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt"></i>
                        Seoul, South Korea
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope"></i>
                        support@paymentplatform.com
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-phone"></i>
                        +82-10-1234-5678
                    </li>
                </ul>
            </div>
        </div>

        <hr class="my-4 border-secondary">

        <!-- Copyright -->
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0 text-muted">
                    &copy; {{ date('Y') }} Payment Platform. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="{{ url('/privacy') }}" class="text-light text-decoration-none me-3">Privacy Policy</a>
                <a href="{{ url('/terms') }}" class="text-light text-decoration-none">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
