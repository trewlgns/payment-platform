{{-- Admin Sidebar Component --}}

<aside class="admin-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="bi bi-shop"></i>
            <span class="sidebar-title">Payment Platform</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="sidebar-menu">
            {{-- Dashboard --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/dashboard') }}" class="sidebar-menu-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>대시보드</span>
                </a>
            </li>

            {{-- 주문 관리 --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/orders') }}" class="sidebar-menu-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check"></i>
                    <span>주문 관리</span>
                </a>
            </li>

            {{-- 결제 내역 --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/payments') }}" class="sidebar-menu-link {{ request()->is('admin/payments*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>
                    <span>결제 내역</span>
                </a>
            </li>

            {{-- 환불 관리 --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/refunds') }}" class="sidebar-menu-link {{ request()->is('admin/refunds*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    <span>환불 관리</span>
                </a>
            </li>

            {{-- 통계 --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/statistics') }}" class="sidebar-menu-link {{ request()->is('admin/statistics*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i>
                    <span>통계</span>
                </a>
            </li>

            {{-- Divider --}}
            <li class="sidebar-divider"></li>

            {{-- 상품 관리 --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/products') }}" class="sidebar-menu-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    <span>상품 관리</span>
                </a>
            </li>

            {{-- 프로모션 관리 --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/promotions') }}" class="sidebar-menu-link {{ request()->is('admin/promotions*') ? 'active' : '' }}">
                    <i class="bi bi-megaphone"></i>
                    <span>프로모션</span>
                </a>
            </li>

            {{-- 쿠폰 관리 --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/coupons') }}" class="sidebar-menu-link {{ request()->is('admin/coupons*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>쿠폰 관리</span>
                </a>
            </li>

            {{-- Divider --}}
            <li class="sidebar-divider"></li>

            {{-- PG 관리 --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/pg-providers') }}" class="sidebar-menu-link {{ request()->is('admin/pg-providers*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3"></i>
                    <span>PG 관리</span>
                </a>
            </li>

            {{-- 웹훅 이벤트 --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/webhooks') }}" class="sidebar-menu-link {{ request()->is('admin/webhooks*') ? 'active' : '' }}">
                    <i class="bi bi-broadcast"></i>
                    <span>웹훅 이벤트</span>
                </a>
            </li>

            {{-- Divider --}}
            <li class="sidebar-divider"></li>

            {{-- 사용자 관리 --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/users') }}" class="sidebar-menu-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>사용자 관리</span>
                </a>
            </li>

            {{-- 설정 --}}
            <li class="sidebar-menu-item">
                <a href="{{ url('/admin/settings') }}" class="sidebar-menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>설정</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <i class="bi bi-person-circle"></i>
            <span>관리자</span>
        </div>
    </div>
</aside>
