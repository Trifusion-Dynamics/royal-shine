<x-layout :seoTitle="$seoTitle" :canonicalUrl="$canonicalUrl">
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-serif text-[#1a1a1a] mb-8 text-center">
                {{ $term !== '' ? 'Search Results' : 'Search Our Jewellery' }}
            </h1>

            <form action="{{ route('search') }}" method="GET" role="search" class="mx-auto mb-12 max-w-4xl">
                <div class="relative">
                    <input type="search" name="q" value="{{ $term }}"
                        placeholder="Search by product name or SKU..."
                        class="w-full border border-[#e5e5e5] py-4 pl-12 pr-32 text-base focus:outline-none focus:ring-2 focus:ring-[#d4a373]">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-[#999]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3 md:grid-cols-5">
                        <select name="category_id" class="border border-[#e5e5e5] px-3 py-2 text-sm">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="collection_id" class="border border-[#e5e5e5] px-3 py-2 text-sm">
                            <option value="">All collections</option>
                            @foreach ($collections as $collection)
                                <option value="{{ $collection->id }}" @selected(request('collection_id') == $collection->id)>{{ $collection->name }}</option>
                            @endforeach
                        </select>
                        <select name="metal_type" class="border border-[#e5e5e5] px-3 py-2 text-sm">
                            <option value="">All materials</option>
                            @foreach (['gold' => 'Gold', 'silver' => 'Silver', 'artificial' => 'Artificial', 'other' => 'Other'] as $value => $label)
                                <option value="{{ $value }}" @selected(request('metal_type') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <select name="gold_purity" class="border border-[#e5e5e5] px-3 py-2 text-sm">
                            <option value="">All purities</option>
                            @foreach (['9K', '14K', '18K'] as $purity)
                                <option value="{{ $purity }}" @selected(request('gold_purity') === $purity)>{{ $purity }}</option>
                            @endforeach
                        </select>
                        <select name="sort" class="border border-[#e5e5e5] px-3 py-2 text-sm">
                            <option value="latest" @selected($sort === 'latest')>Latest first</option>
                            <option value="price_low" @selected($sort === 'price_low')>Price: low to high</option>
                            <option value="price_high" @selected($sort === 'price_high')>Price: high to low</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-gold absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2">Search</button>
                </div>
            </form>

            @if ($term !== '')
                <p class="text-sm text-[#666] mb-6 text-center">
                    {{ $products->total() }} {{ \Illuminate\Support\Str::plural('result', $products->total()) }} for "{{ $term }}"
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
                    <div class="text-[#d4a373] text-6xl mb-6">💎</div>
                    <h2 class="text-2xl font-serif text-[#1a1a1a] mb-4">No products found</h2>
                    <p class="text-[#666] max-w-md mx-auto">
                        @if ($term !== '')
                            We couldn't find any products matching "{{ $term }}". Try a different keyword.
                        @else
                            Enter a keyword above to search our jewellery collection.
                        @endif
                    </p>
                    <a href="{{ route('jewellery.index') }}" class="mt-6 inline-block btn-gold px-6 py-3">Browse Jewellery</a>
                </div>
            @endif
        </div>
    </section>
</x-layout>
