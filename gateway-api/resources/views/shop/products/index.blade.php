@extends('layouts.shop')

@section('title', 'Products')

@section('content')
<div class="products-page">
    {{-- Page Header --}}
    <section class="page-header bg-light py-5 mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-5 fw-bold mb-2">Our Products</h1>
                    <p class="text-muted mb-0">Discover amazing products at great prices</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-md-end mb-0">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            {{-- Sidebar Filters --}}
            <aside class="col-lg-3 mb-4">
                <div class="filters-sidebar">
                    {{-- Category Filter --}}
                    <div class="filter-section mb-4">
                        <h5 class="fw-bold mb-3">Categories</h5>
                        <div class="list-group">
                            <a href="/products" class="list-group-item list-group-item-action active">
                                All Products
                                <span class="badge bg-primary rounded-pill float-end">48</span>
                            </a>
                            <a href="/products?category=electronics" class="list-group-item list-group-item-action">
                                Electronics
                                <span class="badge bg-secondary rounded-pill float-end">12</span>
                            </a>
                            <a href="/products?category=fashion" class="list-group-item list-group-item-action">
                                Fashion
                                <span class="badge bg-secondary rounded-pill float-end">18</span>
                            </a>
                            <a href="/products?category=home" class="list-group-item list-group-item-action">
                                Home & Living
                                <span class="badge bg-secondary rounded-pill float-end">10</span>
                            </a>
                            <a href="/products?category=sports" class="list-group-item list-group-item-action">
                                Sports
                                <span class="badge bg-secondary rounded-pill float-end">8</span>
                            </a>
                        </div>
                    </div>

                    {{-- Price Filter --}}
                    <div class="filter-section mb-4">
                        <h5 class="fw-bold mb-3">Price Range</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="priceRange" id="priceAll" checked>
                            <label class="form-check-label" for="priceAll">All Prices</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="priceRange" id="price1">
                            <label class="form-check-label" for="price1">Under $50</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="priceRange" id="price2">
                            <label class="form-check-label" for="price2">$50 - $100</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="priceRange" id="price3">
                            <label class="form-check-label" for="price3">$100 - $200</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="priceRange" id="price4">
                            <label class="form-check-label" for="price4">Over $200</label>
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div class="filter-section mb-4">
                        <h5 class="fw-bold mb-3">Availability</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="inStock" checked>
                            <label class="form-check-label" for="inStock">In Stock</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="onSale">
                            <label class="form-check-label" for="onSale">On Sale</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="newArrivals">
                            <label class="form-check-label" for="newArrivals">New Arrivals</label>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Products Grid --}}
            <div class="col-lg-9">
                {{-- Toolbar --}}
                <div class="products-toolbar d-flex justify-content-between align-items-center mb-4">
                    <div class="toolbar-left">
                        <span class="text-muted">Showing <strong>1-12</strong> of <strong>48</strong> products</span>
                    </div>
                    <div class="toolbar-right d-flex align-items-center">
                        <label class="me-2 mb-0 text-muted">Sort by:</label>
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>Featured</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Newest</option>
                            <option>Best Selling</option>
                        </select>
                    </div>
                </div>

                {{-- Products Grid --}}
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 mb-5">
                    {{-- Product Card 1 --}}
                    <div class="col">
                        @include('components.shop.product-card', [
                            'name' => 'Wireless Headphones',
                            'price' => 79.99,
                            'original_price' => 99.99,
                            'image' => 'https://via.placeholder.com/300x300/007bff/ffffff?text=Headphones',
                            'badge' => 'Sale',
                            'rating' => 4.5
                        ])
                    </div>

                    {{-- Product Card 2 --}}
                    <div class="col">
                        @include('components.shop.product-card', [
                            'name' => 'Smart Watch Pro',
                            'price' => 299.99,
                            'image' => 'https://via.placeholder.com/300x300/28a745/ffffff?text=Smart+Watch',
                            'badge' => 'New',
                            'rating' => 5
                        ])
                    </div>

                    {{-- Product Card 3 --}}
                    <div class="col">
                        @include('components.shop.product-card', [
                            'name' => 'Laptop Backpack',
                            'price' => 49.99,
                            'image' => 'https://via.placeholder.com/300x300/6c757d/ffffff?text=Backpack',
                            'rating' => 4
                        ])
                    </div>

                    {{-- Product Card 4 --}}
                    <div class="col">
                        @include('components.shop.product-card', [
                            'name' => 'Bluetooth Speaker',
                            'price' => 89.99,
                            'original_price' => 129.99,
                            'image' => 'https://via.placeholder.com/300x300/dc3545/ffffff?text=Speaker',
                            'badge' => 'Sale',
                            'rating' => 4.5
                        ])
                    </div>

                    {{-- Product Card 5 --}}
                    <div class="col">
                        @include('components.shop.product-card', [
                            'name' => 'USB-C Hub',
                            'price' => 39.99,
                            'image' => 'https://via.placeholder.com/300x300/ffc107/ffffff?text=USB+Hub',
                            'rating' => 4
                        ])
                    </div>

                    {{-- Product Card 6 --}}
                    <div class="col">
                        @include('components.shop.product-card', [
                            'name' => 'Mechanical Keyboard',
                            'price' => 149.99,
                            'image' => 'https://via.placeholder.com/300x300/17a2b8/ffffff?text=Keyboard',
                            'badge' => 'Hot',
                            'rating' => 5
                        ])
                    </div>

                    {{-- Product Card 7 --}}
                    <div class="col">
                        @include('components.shop.product-card', [
                            'name' => 'Wireless Mouse',
                            'price' => 29.99,
                            'image' => 'https://via.placeholder.com/300x300/007bff/ffffff?text=Mouse',
                            'rating' => 4.5
                        ])
                    </div>

                    {{-- Product Card 8 --}}
                    <div class="col">
                        @include('components.shop.product-card', [
                            'name' => 'HD Webcam',
                            'price' => 59.99,
                            'image' => 'https://via.placeholder.com/300x300/28a745/ffffff?text=Webcam',
                            'badge' => 'New',
                            'rating' => 4
                        ])
                    </div>

                    {{-- Product Card 9 --}}
                    <div class="col">
                        @include('components.shop.product-card', [
                            'name' => 'Phone Stand',
                            'price' => 19.99,
                            'image' => 'https://via.placeholder.com/300x300/6c757d/ffffff?text=Stand',
                            'rating' => 4.5
                        ])
                    </div>
                </div>

                {{-- Pagination --}}
                <nav aria-label="Product pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
