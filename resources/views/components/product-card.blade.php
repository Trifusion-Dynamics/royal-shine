@props(['product', 'link' => true])
@php
    $firstImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $categoryName = optional($product->category)->name;
    $hasDiscount = $product->price && $product->compare_price && $product->compare_price > $product->price;
    $imageService = app(\App\Services\ImageService::class);
@endphp
<article class="product-card group min-w-0">
    <a href="{{ $link ? route('product.show', $product->slug) : '#' }}" class="product-card__image relative block overflow-hidden bg-[#f3ece2]" @if(!$link) aria-disabled="true" @endif>
        @if ($firstImage)
            <x-image :path="$firstImage->image" :alt="$firstImage->alt_text ?: $product->name" class="h-full w-full object-contain p-3 md:p-5 transition-transform duration-700 group-hover:scale-[1.035]" loading="lazy" />
        @else
            <div class="flex h-full w-full items-center justify-center bg-[#f3ece2] text-[#a18d73]"><span class="font-serif text-2xl">{{ str($product->name)->substr(0, 1)->upper() }}</span></div>
        @endif
        @if ($product->price_on_request || !$product->price)
            <span class="absolute left-3 top-3 bg-white/90 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[.14em] text-[#795d38]">Price on request</span>
        @elseif ($hasDiscount)
            <span class="absolute left-3 top-3 bg-[#a77b43] px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[.14em] text-white">Save {{ round(($product->compare_price - $product->price) / $product->compare_price * 100) }}%</span>
        @endif
        <span class="absolute bottom-3 right-3 grid h-9 w-9 place-items-center rounded-full bg-white/90 text-[#795d38] opacity-0 shadow-sm transition group-hover:opacity-100" aria-hidden="true">↗</span>
    </a>
    <div class="pt-4">
        @if ($categoryName)<p class="mb-1.5 text-[10px] font-semibold uppercase tracking-[.18em] text-[#a77b43]">{{ $categoryName }}</p>@endif
        <h3 class="font-serif text-lg leading-snug text-[#29231e] transition-colors group-hover:text-[#9a713e]"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
        <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
            <span class="text-sm font-semibold text-[#29231e]">
                @if ($product->price_on_request || !$product->price) Price on Request @else ₹{{ number_format($product->price, 0) }} @endif
            </span>
            @if ($link)<a href="{{ route('product.show', $product->slug) }}" class="text-[10px] font-semibold uppercase tracking-[.14em] text-[#9a713e] hover:underline">View piece</a>@endif
        </div>
    </div>
</article>