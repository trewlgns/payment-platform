{{-- Shop Header Component --}}

<header class="shop-header">
    <nav class="navbar navbar-light">
        <div class="container">
            {{-- Left Side: Logo + Nav --}}
            <div class="header-left">
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

                {{-- Navigation Menu --}}
                <ul class="navbar-nav">
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
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="/about">
                            <i class="bi bi-info-circle"></i>
                            <span>프로젝트 소개</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Right Side: Search + Cart + User --}}
            <div class="header-right">
                {{-- Search Button --}}
                <button class="search-trigger" type="button" aria-label="검색">
                    <i class="bi bi-search"></i>
                </button>

                {{-- Cart --}}
                <a class="cart-link {{ request()->is('cart') ? 'active' : '' }}" href="/cart">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-badge">0</span>
                </a>

                {{-- User Menu --}}
                <div class="dropdown user-dropdown">
                    <a class="user-link" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        <i class="bi bi-chevron-down dropdown-arrow"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end user-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="/account"><i class="bi bi-person"></i> 내 계정</a></li>
                        <li><a class="dropdown-item" href="/orders"><i class="bi bi-bag"></i> 주문 내역</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/login"><i class="bi bi-box-arrow-in-right"></i> 로그인</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    {{-- Search Modal --}}
    <div class="search-modal" id="searchModal">
        <div class="search-modal-content">
            <form class="search-modal-form" role="search">
                <div class="search-modal-input-wrapper">
                    <i class="bi bi-search search-icon"></i>
                    <input class="search-modal-input" type="search" placeholder="상품을 검색하세요..." aria-label="검색" autofocus>
                    <button class="search-modal-submit" type="submit" aria-label="검색">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                    <button class="search-modal-close" type="button" aria-label="닫기">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </form>
            <div class="search-autocomplete"></div>
        </div>
    </div>
</header>
