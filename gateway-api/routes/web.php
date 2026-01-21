<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // 인기 상품 (Featured Products)
    $featuredProducts = [
        [
            'product_code' => 'PROD001',
            'name' => '프리미엄 무선 헤드폰',
            'description' => '노이즈 캔슬링 기능의 고품질 무선 헤드폰',
            'base_price' => 199000,
            'discount_price' => 149000,
            'category' => '오디오',
            'status' => 'active',
            'badge' => '베스트',
            'badge_color' => 'danger',
            'image_url' => 'https://via.placeholder.com/300x300/6366f1/ffffff?text=Headphones'
        ],
        [
            'product_code' => 'PROD002',
            'name' => '스마트워치 프로',
            'description' => '피트니스 추적과 알림 기능',
            'base_price' => 299000,
            'discount_price' => 249000,
            'category' => '웨어러블',
            'status' => 'active',
            'badge' => '인기',
            'badge_color' => 'warning',
            'image_url' => 'https://via.placeholder.com/300x300/10b981/ffffff?text=Watch'
        ],
        [
            'product_code' => 'PROD003',
            'name' => '노트북 백팩',
            'description' => '내구성 좋은 스타일리시 백팩',
            'base_price' => 79000,
            'discount_price' => null,
            'category' => '액세서리',
            'status' => 'active',
            'badge' => null,
            'badge_color' => null,
            'image_url' => 'https://via.placeholder.com/300x300/f59e0b/ffffff?text=Backpack'
        ],
        [
            'product_code' => 'PROD004',
            'name' => '블루투스 스피커',
            'description' => '360도 사운드의 휴대용 스피커',
            'base_price' => 89000,
            'discount_price' => 69000,
            'category' => '오디오',
            'status' => 'active',
            'badge' => '할인',
            'badge_color' => 'success',
            'image_url' => 'https://via.placeholder.com/300x300/ec4899/ffffff?text=Speaker'
        ],
    ];

    // 신상품 (New Arrivals)
    $newProducts = [
        [
            'product_code' => 'PROD005',
            'name' => '게이밍 마우스',
            'description' => 'RGB 조명과 고정밀 센서',
            'base_price' => 59000,
            'discount_price' => null,
            'category' => '게이밍',
            'status' => 'active',
            'badge' => 'NEW',
            'badge_color' => 'primary',
            'image_url' => 'https://via.placeholder.com/300x300/8b5cf6/ffffff?text=Mouse'
        ],
        [
            'product_code' => 'PROD006',
            'name' => '무선 충전 패드',
            'description' => '15W 고속 무선 충전',
            'base_price' => 35000,
            'discount_price' => 29000,
            'category' => '액세서리',
            'status' => 'active',
            'badge' => 'NEW',
            'badge_color' => 'primary',
            'image_url' => 'https://via.placeholder.com/300x300/06b6d4/ffffff?text=Charger'
        ],
        [
            'product_code' => 'PROD007',
            'name' => '기계식 키보드',
            'description' => '청축 스위치 RGB 키보드',
            'base_price' => 129000,
            'discount_price' => null,
            'category' => '게이밍',
            'status' => 'active',
            'badge' => 'NEW',
            'badge_color' => 'primary',
            'image_url' => 'https://via.placeholder.com/300x300/f43f5e/ffffff?text=Keyboard'
        ],
        [
            'product_code' => 'PROD008',
            'name' => '웹캠 HD',
            'description' => '1080p 화상 회의용 웹캠',
            'base_price' => 69000,
            'discount_price' => 59000,
            'category' => '전자기기',
            'status' => 'active',
            'badge' => 'NEW',
            'badge_color' => 'primary',
            'image_url' => 'https://via.placeholder.com/300x300/84cc16/ffffff?text=Webcam'
        ],
    ];

    return view('home', compact('featuredProducts', 'newProducts'));
});

// 상품 라우트
Route::get('/products', function () {
    // 데모용 상품 목록 데이터
    $products = [
        [
            'product_code' => 'PROD001',
            'name' => 'Premium Wireless Headphones',
            'description' => 'High-quality wireless headphones with active noise cancellation',
            'base_price' => 199.99,
            'discount_price' => 149.99,
            'category' => 'Electronics',
            'status' => 'active',
            'badge' => 'New Arrival',
            'badge_color' => 'success',
            'image_url' => 'https://via.placeholder.com/300x300?text=Headphones'
        ],
        [
            'product_code' => 'PROD002',
            'name' => 'Smart Watch Pro',
            'description' => 'Advanced fitness tracking and notifications',
            'base_price' => 299.99,
            'discount_price' => 249.99,
            'category' => 'Wearables',
            'status' => 'active',
            'badge' => 'Best Seller',
            'badge_color' => 'warning',
            'image_url' => 'https://via.placeholder.com/300x300?text=Watch'
        ],
        [
            'product_code' => 'PROD003',
            'name' => 'Laptop Backpack',
            'description' => 'Durable and stylish laptop backpack',
            'base_price' => 79.99,
            'discount_price' => null,
            'category' => 'Accessories',
            'status' => 'active',
            'badge' => null,
            'badge_color' => null,
            'image_url' => 'https://via.placeholder.com/300x300?text=Backpack'
        ]
    ];

    return view('shop.products.index', compact('products'));
})->name('products.index');

Route::get('/products/{productCode}', function (string $productCode) {
    // Mock product data for demo
    $product = [
        'product_code' => $productCode,
        'name' => 'Premium Wireless Headphones',
        'description' => 'High-quality wireless headphones with active noise cancellation and premium sound quality.',
        'long_description' => 'Experience superior sound quality with our Premium Wireless Headphones. Featuring advanced active noise cancellation technology, premium drivers, and long-lasting battery life, these headphones deliver an exceptional audio experience. Perfect for music lovers, travelers, and professionals who demand the best.',
        'base_price' => 199.99,
        'discount_price' => 149.99,
        'category' => 'Electronics',
        'status' => 'active',
        'badge' => 'New Arrival',
        'badge_color' => 'success',
        'brand' => 'TechAudio',
        'weight' => '250g',
        'dimensions' => '20 x 18 x 8 cm',
        'material' => 'Premium Plastic & Leather',
        'warranty' => '2 Year Manufacturer Warranty',
        'image_url' => 'https://via.placeholder.com/600x600?text=Premium+Headphones',
        'images' => [
            'https://via.placeholder.com/600x600?text=Premium+Headphones',
            'https://via.placeholder.com/600x600?text=Side+View',
            'https://via.placeholder.com/600x600?text=Top+View',
            'https://via.placeholder.com/600x600?text=In+Case'
        ]
    ];

    return view('shop.products.show', compact('product'));
})->name('products.show');

// About route
Route::get('/about', function () {
    return view('about');
})->name('about');

// Cart route
Route::get('/cart', function () {
    return view('shop.cart');
})->name('cart');

// Checkout route
Route::get('/checkout', function () {
    return view('shop.checkout');
})->name('checkout');

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/orders', function () {
        return view('admin.orders.index');
    })->name('admin.orders');

    Route::get('/payments', function () {
        return view('admin.payments.index');
    })->name('admin.payments');
});
