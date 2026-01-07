{{-- Shop Header Component --}}

<header class="shop-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            {{-- Logo/Brand with Gradient --}}
            <a class="navbar-brand" href="/">
                <span class="brand-icon">
                    <i class="bi bi-shop"></i>
                </span>
                <span class="brand-text">
                    <span class="brand-title">Payment</span>
                    <span class="brand-subtitle">Platform</span>
                </span>
            </a>

            {{-- Mobile Toggle Button --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="메뉴 토글">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Navigation Menu --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                {{-- Left Menu --}}
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            <i class="bi bi-house-door"></i>
                            <span>홈</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('products*') ? 'active' : '' }}" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-grid"></i>
                            <span>상품</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                            <li><a class="dropdown-item {{ !request()->has('category') && request()->is('products') ? 'active' : '' }}" href="/products">전체 상품</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item {{ request()->get('category') === 'electronics' ? 'active' : '' }}" href="/products?category=electronics">전자기기</a></li>
                            <li><a class="dropdown-item {{ request()->get('category') === 'fashion' ? 'active' : '' }}" href="/products?category=fashion">패션</a></li>
                            <li><a class="dropdown-item {{ request()->get('category') === 'home' ? 'active' : '' }}" href="/products?category=home">홈&리빙</a></li>
                        </ul>
                    </li>
                </ul>

                {{-- Right Menu --}}
                <ul class="navbar-nav ms-auto align-items-center">
                    {{-- Search --}}
                    <li class="nav-item search-wrapper">
                        <form class="search-form" role="search">
                            <div class="input-group">
                                <input class="form-control search-input" type="search" placeholder="상품 검색..." aria-label="검색">
                                <button class="btn btn-search" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </li>

                    {{-- Cart --}}
                    <li class="nav-item cart-wrapper">
                        <a class="nav-link cart-link {{ request()->is('cart') ? 'active' : '' }}" href="/cart">
                            <i class="bi bi-cart3"></i>
                            <span class="cart-badge">0</span>
                            <span class="cart-label">장바구니</span>
                        </a>
                    </li>

                    {{-- User Menu --}}
                    <li class="nav-item dropdown user-dropdown">
                        <a class="nav-link user-link" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            <span class="user-label">마이페이지</span>
                            <i class="bi bi-chevron-down dropdown-arrow"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end user-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/account"><i class="bi bi-person"></i> 내 계정</a></li>
                            <li><a class="dropdown-item" href="/orders"><i class="bi bi-bag"></i> 주문 내역</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/login"><i class="bi bi-box-arrow-in-right"></i> 로그인</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
