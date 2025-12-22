{{-- Product Card Component --}}

<div class="product-card h-100">
    <div class="card h-100 border-0 shadow-sm">
        {{-- Product Image --}}
        <div class="product-image-wrapper position-relative">
            @if(isset($badge))
                <span class="product-badge badge
                    @if($badge === 'Sale') bg-danger
                    @elseif($badge === 'New') bg-success
                    @elseif($badge === 'Hot') bg-warning text-dark
                    @else bg-primary
                    @endif
                    position-absolute">
                    {{ $badge }}
                </span>
            @endif

            <img src="{{ $image ?? 'https://via.placeholder.com/300x300' }}"
                 class="card-img-top product-image"
                 alt="{{ $name ?? 'Product' }}">

            {{-- Quick Actions (Hover) --}}
            <div class="product-actions">
                <button class="btn btn-sm btn-light rounded-circle me-1" title="Add to Wishlist">
                    <i class="bi bi-heart"></i>
                </button>
                <button class="btn btn-sm btn-light rounded-circle" title="Quick View">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <div class="card-body d-flex flex-column">
            {{-- Product Name --}}
            <h5 class="product-name card-title mb-2">
                <a href="/products/{{ Str::slug($name ?? 'product') }}" class="text-decoration-none text-dark">
                    {{ $name ?? 'Product Name' }}
                </a>
            </h5>

            {{-- Rating --}}
            @if(isset($rating))
                <div class="product-rating mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($rating))
                            <i class="bi bi-star-fill text-warning"></i>
                        @elseif($i - 0.5 <= $rating)
                            <i class="bi bi-star-half text-warning"></i>
                        @else
                            <i class="bi bi-star text-warning"></i>
                        @endif
                    @endfor
                    <span class="text-muted ms-1">({{ $rating }})</span>
                </div>
            @endif

            {{-- Price --}}
            <div class="product-price mb-3">
                <span class="price fw-bold fs-5 text-primary">${{ number_format($price ?? 0, 2) }}</span>
                @if(isset($original_price) && $original_price > $price)
                    <span class="original-price text-muted text-decoration-line-through ms-2">
                        ${{ number_format($original_price, 2) }}
                    </span>
                    <span class="badge bg-danger ms-1">
                        -{{ round((($original_price - $price) / $original_price) * 100) }}%
                    </span>
                @endif
            </div>

            {{-- Add to Cart Button --}}
            <button class="btn btn-primary w-100 mt-auto add-to-cart-btn">
                <i class="bi bi-cart-plus me-1"></i>
                Add to Cart
            </button>
        </div>
    </div>
</div>
