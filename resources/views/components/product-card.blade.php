@props([
    'product',
    'link' => true,
])

@php
    $firstImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $categoryName = optional($product->category)->name;
    $hasDiscount = $product->price && $product->compare_price && $product->compare_price > $product->price;
@endphp

<article class="card group">
    <div class="image-wrap aspect-square">
        @if ($firstImage)
            <x-image
                :path="$firstImage->image"
                :alt="$firstImage->alt_text ?: $product->name"
                class="w-full h-full object-cover group-hover:scale-105"
                loading="lazy"
                priority="{{ $loop->first ?? false }}"
            />
        @else
            <div class="w-full h-full lazy-placeholder flex items-center justify-center">
                <span class="text-warm-gray text-xs font-medium uppercase tracking-wider">
                    {{ str($product->name)->substr(0, 1)->upper() }}
                </span>
            </div>
        @endif

        @if ($product->price_on_request)
            <span class="absolute top-3 left-3 z-10 inline-flex items-center gap-1 px-2.5 py-1 bg-white text-brown text-[10px] font-medium uppercase tracking-wider rounded-full border border-beige shadow-sm">
                Price on Request
            </span>
        @elseif ($hasDiscount)
            <span class="absolute top-3 left-3 z-10 inline-flex items-center gap-1 px-2.5 py-1 bg-gold text-white text-[10px] font-medium uppercase tracking-wider rounded-full shadow-sm">
                Save {{ round(($product->compare_price - $product->price) / $product->compare_price * 100) }}%
            </span>
        @endif

        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-opacity duration-500"></div>
    </div>

    <div class="p-5">
        @if ($categoryName)
            <p class="text-xs font-medium text-gold uppercase tracking-wider mb-1.5">
                {{ $categoryName }}
            </p>
        @endif

        <h3 class="font-serif text-base text-charcoal mb-2 leading-tight group-hover:text-gold transition-colors">
            @if ($link)
                <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
            @else
                {{ $product->name }}
            @endif
        </h3>

        <div class="flex items-baseline gap-2 mb-3">
            @if ($product->price_on_request)
                <span class="text-sm font-medium text-gold">Price on Request</span>
            @elseif ($product->price)
                <span class="text-lg font-bold text-charcoal">₹{{ number_format($product->price, 0) }}</span>
                @if ($hasDiscount)
                    <span class="text-sm text-warm-gray line-through">{{ $product->compare_price }}</span>
                @endif
            @else
                <span class="text-sm font-medium text-gold">Price on Request</span>
            @endif
        </div>

        <div class="flex gap-2">
            @if ($link)
                <a href="{{ route('enquiry.create', ['product' => $product->id]) }}"
                   class="btn flex-1 btn-text btn-sm text-gold hover:text-brown focus:ring-gold">
                    Enquire
                </a>
            @endif
        </div>
    </div>
</article>
