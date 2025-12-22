@extends('layouts.shop')

@section('title', $product['name'] ?? 'Product Detail')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/products">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product['name'] ?? 'Product' }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image Gallery -->
        <div class="col-lg-6 mb-4">
            <div class="product-detail-gallery">
                <!-- Main Image -->
                <div class="product-detail-gallery__main mb-3">
                    <img
                        id="mainProductImage"
                        src="{{ $product['image_url'] ?? 'https://via.placeholder.com/600x600?text=Product+Image' }}"
                        alt="{{ $product['name'] ?? 'Product' }}"
                        class="img-fluid rounded"
                    >
                    @if(!empty($product['badge']))
                    <span class="badge bg-{{ $product['badge_color'] ?? 'primary' }} product-detail-gallery__badge">
                        {{ $product['badge'] }}
                    </span>
                    @endif
                </div>

                <!-- Thumbnail Images -->
                <div class="product-detail-gallery__thumbnails">
                    <div class="row g-2">
                        @php
                            $thumbnails = $product['images'] ?? [
                                $product['image_url'] ?? 'https://via.placeholder.com/150?text=1',
                                'https://via.placeholder.com/150?text=2',
                                'https://via.placeholder.com/150?text=3',
                                'https://via.placeholder.com/150?text=4'
                            ];
                        @endphp
                        @foreach($thumbnails as $index => $thumbnail)
                        <div class="col-3">
                            <img
                                src="{{ $thumbnail }}"
                                alt="Thumbnail {{ $index + 1 }}"
                                class="img-fluid rounded product-detail-gallery__thumbnail {{ $index === 0 ? 'active' : '' }}"
                                data-full-image="{{ $thumbnail }}"
                                style="cursor: pointer;"
                            >
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="col-lg-6">
            <div class="product-detail-info">
                <!-- Product Title -->
                <h1 class="product-detail-info__title h2 mb-3">
                    {{ $product['name'] ?? 'Product Name' }}
                </h1>

                <!-- Product Category -->
                <div class="product-detail-info__category mb-3">
                    <span class="badge bg-secondary">{{ $product['category'] ?? 'Electronics' }}</span>
                    @if(!empty($product['status']))
                    <span class="badge bg-{{ $product['status'] === 'active' ? 'success' : 'danger' }}">
                        {{ ucfirst($product['status'] ?? 'active') }}
                    </span>
                    @endif
                </div>

                <!-- Product Price -->
                <div class="product-detail-info__price mb-4">
                    @if(!empty($product['discount_price']))
                    <div class="d-flex align-items-center gap-3">
                        <span class="price--original h5 mb-0">${{ number_format($product['base_price'] ?? 0, 2) }}</span>
                        <span class="price--discount h3 mb-0">${{ number_format($product['discount_price'], 2) }}</span>
                        <span class="badge bg-danger">
                            -{{ round((($product['base_price'] - $product['discount_price']) / $product['base_price']) * 100) }}%
                        </span>
                    </div>
                    @else
                    <span class="h3 mb-0 text-primary fw-bold">${{ number_format($product['base_price'] ?? 99.99, 2) }}</span>
                    @endif
                </div>

                <!-- Product Description -->
                <div class="product-detail-info__description mb-4">
                    <h5 class="fw-semibold">Description</h5>
                    <p class="text-muted">
                        {{ $product['description'] ?? 'This is a high-quality product designed to meet your needs. It features excellent craftsmanship and modern design that will complement any space.' }}
                    </p>
                </div>

                <!-- Product Specifications -->
                <div class="product-detail-info__specs mb-4">
                    <h5 class="fw-semibold mb-3">Specifications</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Product Code:</span>
                            <span class="fw-semibold">{{ $product['product_code'] ?? 'PROD-001' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Category:</span>
                            <span class="fw-semibold">{{ $product['category'] ?? 'Electronics' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Availability:</span>
                            <span class="badge bg-success">In Stock</span>
                        </li>
                        @if(!empty($product['brand']))
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Brand:</span>
                            <span class="fw-semibold">{{ $product['brand'] }}</span>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- Quantity & Add to Cart -->
                <div class="product-detail-info__actions">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label fw-semibold">Quantity</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" id="decreaseQty">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input
                                    type="number"
                                    class="form-control text-center"
                                    id="quantity"
                                    value="1"
                                    min="1"
                                    max="99"
                                >
                                <button class="btn btn-outline-secondary" type="button" id="increaseQty">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mb-3">
                        <button class="btn btn-primary btn-lg flex-grow-1" id="addToCartBtn">
                            <i class="bi bi-cart-plus me-2"></i>
                            Add to Cart
                        </button>
                        <button class="btn btn-outline-secondary btn-lg" id="addToWishlistBtn">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>

                    <button class="btn btn-success btn-lg w-100" id="buyNowBtn">
                        <i class="bi bi-lightning-charge me-2"></i>
                        Buy Now
                    </button>
                </div>

                <!-- Additional Info -->
                <div class="product-detail-info__extras mt-4 pt-4 border-top">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-truck fs-4 text-primary me-3"></i>
                                <div>
                                    <div class="fw-semibold">Free Shipping</div>
                                    <small class="text-muted">Orders over $50</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-arrow-clockwise fs-4 text-primary me-3"></i>
                                <div>
                                    <div class="fw-semibold">30-Day Returns</div>
                                    <small class="text-muted">Money back guarantee</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-shield-check fs-4 text-primary me-3"></i>
                                <div>
                                    <div class="fw-semibold">Secure Payment</div>
                                    <small class="text-muted">100% secure checkout</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-headset fs-4 text-primary me-3"></i>
                                <div>
                                    <div class="fw-semibold">24/7 Support</div>
                                    <small class="text-muted">Dedicated support</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                        Details
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">
                        Specifications
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                        Reviews (12)
                    </button>
                </li>
            </ul>
            <div class="tab-content p-4 border border-top-0 rounded-bottom" id="productDetailTabsContent">
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <h5 class="mb-3">Product Details</h5>
                    <p class="text-muted">
                        {{ $product['long_description'] ?? 'This product is crafted with premium materials and designed to last. It combines functionality with aesthetic appeal, making it perfect for both professional and personal use. The attention to detail in every aspect ensures you receive a product that exceeds expectations.' }}
                    </p>
                    <ul class="text-muted">
                        <li>Premium quality materials</li>
                        <li>Modern and sleek design</li>
                        <li>Easy to use and maintain</li>
                        <li>Environmentally friendly</li>
                        <li>Backed by manufacturer warranty</li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="specifications" role="tabpanel">
                    <h5 class="mb-3">Technical Specifications</h5>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th width="30%">Product Code</th>
                                <td>{{ $product['product_code'] ?? 'PROD-001' }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $product['category'] ?? 'Electronics' }}</td>
                            </tr>
                            <tr>
                                <th>Weight</th>
                                <td>{{ $product['weight'] ?? '1.5 kg' }}</td>
                            </tr>
                            <tr>
                                <th>Dimensions</th>
                                <td>{{ $product['dimensions'] ?? '30 x 20 x 10 cm' }}</td>
                            </tr>
                            <tr>
                                <th>Material</th>
                                <td>{{ $product['material'] ?? 'Premium Plastic & Metal' }}</td>
                            </tr>
                            <tr>
                                <th>Warranty</th>
                                <td>{{ $product['warranty'] ?? '1 Year Manufacturer Warranty' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <h5 class="mb-3">Customer Reviews</h5>

                    <!-- Review Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="display-4 fw-bold text-primary">4.5</div>
                            <div class="text-warning mb-2">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </div>
                            <p class="text-muted mb-0">Based on 12 reviews</p>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">5 <i class="bi bi-star-fill text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2">
                                    <div class="progress-bar bg-warning" style="width: 75%"></div>
                                </div>
                                <span class="text-muted">75%</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">4 <i class="bi bi-star-fill text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2">
                                    <div class="progress-bar bg-warning" style="width: 15%"></div>
                                </div>
                                <span class="text-muted">15%</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">3 <i class="bi bi-star-fill text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2">
                                    <div class="progress-bar bg-warning" style="width: 8%"></div>
                                </div>
                                <span class="text-muted">8%</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">2 <i class="bi bi-star-fill text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2">
                                    <div class="progress-bar bg-warning" style="width: 2%"></div>
                                </div>
                                <span class="text-muted">2%</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="me-2">1 <i class="bi bi-star-fill text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2">
                                    <div class="progress-bar bg-warning" style="width: 0%"></div>
                                </div>
                                <span class="text-muted">0%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Individual Reviews -->
                    <div class="review-item border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>John Doe</strong>
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                            <small class="text-muted">2 days ago</small>
                        </div>
                        <p class="mb-0">Excellent product! Exactly what I was looking for. Highly recommended.</p>
                    </div>

                    <div class="review-item border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>Jane Smith</strong>
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                            </div>
                            <small class="text-muted">1 week ago</small>
                        </div>
                        <p class="mb-0">Very good quality. Fast shipping and great customer service.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Related Products</h3>
            <div class="row g-4">
                @for($i = 1; $i <= 4; $i++)
                <div class="col-md-3">
                    <div class="card product-card h-100 shadow-hover">
                        <img
                            src="https://via.placeholder.com/300x300?text=Product+{{ $i }}"
                            class="card-img-top"
                            alt="Related Product {{ $i }}"
                        >
                        <div class="card-body">
                            <h6 class="card-title">Related Product {{ $i }}</h6>
                            <p class="card-text text-muted small">Sample description text</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 mb-0 text-primary">${{ 49.99 + ($i * 10) }}</span>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    const qtyInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');

    decreaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(qtyInput.value);
        if (currentValue > 1) {
            qtyInput.value = currentValue - 1;
        }
    });

    increaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(qtyInput.value);
        if (currentValue < 99) {
            qtyInput.value = currentValue + 1;
        }
    });

    // Thumbnail image click
    const thumbnails = document.querySelectorAll('.product-detail-gallery__thumbnail');
    const mainImage = document.getElementById('mainProductImage');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));

            // Add active class to clicked thumbnail
            this.classList.add('active');

            // Update main image
            mainImage.src = this.dataset.fullImage;
        });
    });

    // Add to cart button
    document.getElementById('addToCartBtn').addEventListener('click', function() {
        const quantity = qtyInput.value;
        alert(`Added ${quantity} item(s) to cart!`);
        // TODO: Implement actual cart functionality
    });

    // Add to wishlist button
    document.getElementById('addToWishlistBtn').addEventListener('click', function() {
        this.classList.toggle('btn-outline-secondary');
        this.classList.toggle('btn-danger');
        const icon = this.querySelector('i');
        icon.classList.toggle('bi-heart');
        icon.classList.toggle('bi-heart-fill');
    });

    // Buy now button
    document.getElementById('buyNowBtn').addEventListener('click', function() {
        alert('Redirecting to checkout...');
        // TODO: Implement checkout redirect
    });
});
</script>
@endpush
