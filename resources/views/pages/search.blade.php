<x-layout :seoTitle="$seoTitle" :canonicalUrl="$canonicalUrl">
    <section class="py-12">
        <div class="container-luxury">
            <h1 class="text-3xl md:text-4xl font-serif text-charcoal mb-10 text-center leading-tight">
                {{ $term !== '' ? 'Search Results' : 'Search Our Jewellery' }}
            </h1>

            <form action="{{ route('search') }}" method="GET" role="search" class="mx-auto mb-12 max-w-3xl">
                <div class="relative">
                    <label class="sr-only" for="search-input">Search by product name or SKU</label>
                    <input type="search" name="q" id="search-input" value="{{ $term }}"
                           placeholder="Search by product name or SKU..."
                           class="w-full border border-beige py-4 pl-12 pr-10 text-base text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 rounded-md">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-warm-gray">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 btn btn-gold text-sm px-6 py-2">Search</button>
                </div>
            </form>

            @if ($term !== '')
                <p class="text-sm text-warm-gray mb-8 text-center">
                    {{ $products->total() }} {{ \Illuminate\Support\Str::plural('result', $products->total()) }} for &ldquo;{{ $term }}&rdquo;
                </p>
            @endif

            @if ($products->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                <div class="mt-12 flex justify-center">
                    {{ $products->onEachSide(1)->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-gold text-6xl mb-6">💎</div>
                    <h2 class="text-3xl font-serif text-charcoal mb-4">No Products Found</h2>
                    <p class="text-warm-gray max-w-md mx-auto leading-relaxed">
                        @if ($term !== '')
                            We couldn&rsquo;t find any products matching &ldquo;{{ $term }}&rdquo;. Try a different keyword.
                        @else
                            Enter a keyword above to search our jewellery collection.
                        @endif
                    </p>
                    <a href="{{ route('jewellery.index') }}" class="mt-8 inline-block btn btn-gold px-8 py-3">Browse Jewellery</a>
                </div>
            @endif
        </div>
    </section>
</x-layout>
