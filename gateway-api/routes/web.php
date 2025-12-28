<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Product routes
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
});
