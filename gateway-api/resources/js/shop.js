/**
 * Shop JavaScript
 */

console.log('Shop JS loaded');

// ============================================
// Cart Badge Management
// ============================================
function updateCartBadge() {
    const cartData = JSON.parse(localStorage.getItem('cart') || '[]');
    const cartCount = cartData.reduce((total, item) => total + (item.quantity || 0), 0);
    const $cartBadge = $('.cart-badge');

    if (cartCount > 0) {
        $cartBadge.text(cartCount).show();
    } else {
        $cartBadge.hide();
    }
}

// ============================================
// User Menu Management (Login State)
// ============================================
function updateUserMenu() {
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    const userName = localStorage.getItem('userName') || '사용자';

    const $userDropdown = $('.user-dropdown');
    const $userLabel = $userDropdown.find('.user-label');
    const $userMenu = $userDropdown.find('.user-menu');

    if (isLoggedIn) {
        // 로그인 상태
        $userLabel.text(userName);
        $userMenu.html(`
            <li><a class="dropdown-item" href="/account"><i class="bi bi-person"></i> 내 계정</a></li>
            <li><a class="dropdown-item" href="/orders"><i class="bi bi-bag"></i> 주문 내역</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item logout-btn" href="#"><i class="bi bi-box-arrow-right"></i> 로그아웃</a></li>
        `);

        // 로그아웃 버튼 이벤트
        $userMenu.find('.logout-btn').on('click', function(e) {
            e.preventDefault();
            localStorage.removeItem('isLoggedIn');
            localStorage.removeItem('userName');
            localStorage.removeItem('userEmail');
            updateUserMenu();
            alert('로그아웃되었습니다.');
            window.location.href = '/';
        });
    } else {
        // 로그아웃 상태
        $userLabel.text('로그인');
        $userMenu.html(`
            <li><a class="dropdown-item" href="/login"><i class="bi bi-box-arrow-in-right"></i> 로그인</a></li>
            <li><a class="dropdown-item" href="/register"><i class="bi bi-person-plus"></i> 회원가입</a></li>
        `);
    }
}

// ============================================
// Product Search Modal
// ============================================
let searchTimeout = null;

function initProductSearch() {
    const $searchTrigger = $('.search-trigger');
    const $searchModal = $('.search-modal');
    const $searchModalClose = $('.search-modal-close');
    const $searchInput = $('.search-modal-input');
    const $searchForm = $('.search-modal-form');
    const $autocompleteContainer = $('.search-autocomplete');

    // Open modal on search trigger click
    $searchTrigger.on('click', function() {
        $searchModal.addClass('active');
        // Focus input after modal animation
        setTimeout(() => {
            $searchInput.focus();
        }, 100);
    });

    // Close modal - close button
    $searchModalClose.on('click', function() {
        closeSearchModal();
    });

    // Close modal - backdrop click
    $searchModal.on('click', function(e) {
        if ($(e.target).hasClass('search-modal')) {
            closeSearchModal();
        }
    });

    // Close modal - ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $searchModal.hasClass('active')) {
            closeSearchModal();
        }
    });

    // Close modal helper
    function closeSearchModal() {
        $searchModal.removeClass('active');
        $searchInput.val('');
        $autocompleteContainer.hide().empty();
    }

    // Search input event with debounce
    $searchInput.on('input', function() {
        const query = $(this).val().trim();

        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // Hide autocomplete if query is too short
        if (query.length < 2) {
            $autocompleteContainer.hide().empty();
            return;
        }

        // Debounce search (300ms)
        searchTimeout = setTimeout(() => {
            searchProducts(query, $autocompleteContainer);
        }, 300);
    });

    // Search form submit
    $searchForm.on('submit', function(e) {
        e.preventDefault();
        const query = $searchInput.val().trim();
        if (query.length > 0) {
            window.location.href = `/products?search=${encodeURIComponent(query)}`;
        }
    });
}

function searchProducts(query, $dropdown) {
    // Call search API
    $.ajax({
        url: '/api/products/search',
        method: 'GET',
        data: { q: query, limit: 5 },
        success: function(response) {
            if (response.result && response.data && response.data.length > 0) {
                renderSearchResults(response.data, $dropdown, query);
            } else {
                $dropdown.html('<div class="autocomplete-item no-results">검색 결과가 없습니다</div>').show();
            }
        },
        error: function() {
            $dropdown.html('<div class="autocomplete-item error">검색 중 오류가 발생했습니다</div>').show();
        }
    });
}

function renderSearchResults(products, $dropdown, query) {
    $dropdown.empty();

    products.forEach(product => {
        const highlightedName = highlightText(product.name, query);
        const price = parseInt(product.base_price).toLocaleString('ko-KR');

        const $item = $(`
            <a href="/products/${product.product_code}" class="autocomplete-item">
                <div class="autocomplete-item-name">${highlightedName}</div>
                <div class="autocomplete-item-price">${price}원</div>
            </a>
        `);

        $dropdown.append($item);
    });

    // View all results link
    $dropdown.append(`
        <a href="/products?search=${encodeURIComponent(query)}" class="autocomplete-item view-all">
            <i class="bi bi-search"></i> 전체 검색 결과 보기
        </a>
    `);

    $dropdown.show();
}

function highlightText(text, query) {
    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<strong>$1</strong>');
}

// ============================================
// Initialize on Document Ready
// ============================================
$(document).ready(function() {
    updateCartBadge();
    updateUserMenu();
    initProductSearch();

    // Update cart badge when storage changes (cross-tab sync)
    $(window).on('storage', function(e) {
        if (e.originalEvent.key === 'cart') {
            updateCartBadge();
        }
        if (e.originalEvent.key === 'isLoggedIn' || e.originalEvent.key === 'userName') {
            updateUserMenu();
        }
    });
});
