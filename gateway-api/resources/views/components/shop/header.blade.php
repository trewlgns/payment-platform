{{-- Shop Header Component --}}

<header class="shop-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            {{-- Logo/Brand --}}
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-shop"></i>
                Payment Shop
            </a>

            {{-- Mobile Toggle Button --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Navigation Menu --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                {{-- Left Menu --}}
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            <i class="bi bi-house-door"></i>
                            Home
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('products*') ? 'active' : '' }}" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-grid"></i>
                            Products
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                            <li><a class="dropdown-item {{ !request()->has('category') && request()->is('products') ? 'active' : '' }}" href="/products">All Products</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item {{ request()->get('category') === 'electronics' ? 'active' : '' }}" href="/products?category=electronics">Electronics</a></li>
                            <li><a class="dropdown-item {{ request()->get('category') === 'fashion' ? 'active' : '' }}" href="/products?category=fashion">Fashion</a></li>
                            <li><a class="dropdown-item {{ request()->get('category') === 'home' ? 'active' : '' }}" href="/products?category=home">Home & Living</a></li>
                        </ul>
                    </li>
                </ul>

                {{-- Right Menu --}}
                <ul class="navbar-nav ms-auto">
                    {{-- Search --}}
                    <li class="nav-item">
                        <form class="d-flex me-2" role="search">
                            <input class="form-control form-control-sm" type="search" placeholder="Search products..." aria-label="Search">
                            <button class="btn btn-outline-primary btn-sm ms-1" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </li>

                    {{-- Cart --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative {{ request()->is('cart') ? 'active' : '' }}" href="/cart">
                            <i class="bi bi-cart3 fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                                0
                                <span class="visually-hidden">items in cart</span>
                            </span>
                        </a>
                    </li>

                    {{-- User Menu --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/account"><i class="bi bi-person"></i> My Account</a></li>
                            <li><a class="dropdown-item" href="/orders"><i class="bi bi-bag"></i> My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/login"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
