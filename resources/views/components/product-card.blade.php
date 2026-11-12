@props([
    'product',
    'link' => true,
])

@php
    $firstImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $categoryName = optional($product->category)->name;
@endphp

<div class="product-card group">
    @if ($firstImage)
        <div class="aspect-square relative mb-4 overflow-hidden">
            <x-image
                :path="$firstImage->image"
                :alt="$product->name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
            />
        </div>
    @else
        <div class="aspect-square bg-[#f3f3f3] mb-4 flex items-center justify-center rounded-none">
            <span class="text-[#999] text-sm">No Image</span>
        </div>
    @endif

    <h3 class="text-base font-medium text-[#1a1a1a] mb-1 group-hover:text-[#d4a373] transition-colors">
        @if ($link)
            <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
        @else
            {{ $product->name }}
        @endif
    </h3>

    @if ($categoryName)
        <p class="text-xs text-[#666] mb-1">{{ $categoryName }}</p>
    @endif
    <p class="mb-2 text-[11px] uppercase tracking-wider text-[#999]">SKU: {{ $product->sku }}</p>

    <div class="flex items-baseline gap-2 mb-2">
        @if ($product->price_on_request)
            <span class="text-sm font-medium text-[#d4a373]">Price on Request</span>
        @elseif ($product->price)
            <span class="text-lg font-semibold text-[#1a1a1a]">₹{{ number_format($product->price, 0) }}</span>
        @else
            <span class="text-sm font-medium text-[#d4a373]">Price on Request</span>
        @endif
    </div>

    @if ($link)
        <a
            href="{{ route('enquiry.create', ['product' => $product->id]) }}"
            class="btn-gold block text-center text-sm"
        >
            Enquire Now
        </a>
    @endif
</div>
