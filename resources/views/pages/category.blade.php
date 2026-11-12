<x-layout :seoTitle="$seoTitle" :metaDescription="$metaDescription" :canonicalUrl="$canonicalUrl" :ogImage="$ogImage">
    <!-- Breadcrumb -->
    <nav class="mb-8 text-sm text-[#666] pt-8">
        <ol class="flex space-x-2 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <li><a href="{{ route('home') }}" class="hover:text-[#d4a373]">Home</a></li>
            <li><span>/</span></li>
            <li><a href="{{ route('jewellery.index') }}" class="hover:text-[#d4a373]">Jewellery</a></li>
            <li><span>/</span></li>
            <li><span class="text-[#1a1a1a] font-medium">{{ $category->name }}</span></li>
        </ol>
    </nav>

    <header class="mb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-serif text-[#1a1a1a] mb-2">{{ $category->name }}</h1>
            @if ($category->description)
                <p class="text-lg text-[#666] max-w-2xl">{{ $category->description }}</p>
            @endif
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $sortOptions = ['latest' => 'Latest First'];
            if ($hasPrices) {
                $sortOptions['price_low'] = 'Price: Low to High';
                $sortOptions['price_high'] = 'Price: High to Low';
            }

            $metalLabels = [
                'gold' => 'Gold',
                'silver' => 'Silver',
                'artificial' => 'Artificial',
                'other' => 'Other',
            ];
            $metalOptions = collect($metalTypes)
                ->mapWithKeys(function ($metal) use ($metalLabels) {
                    $value = $metal instanceof \BackedEnum ? $metal->value : (string) $metal;
                    return [$value => $metalLabels[$value] ?? ucfirst($value)];
                })
                ->all();
            $purityOptions = collect($goldPurities)
                ->mapWithKeys(function ($purity) {
                    $value = $purity instanceof \BackedEnum ? $purity->value : (string) $purity;
                    return [$value => $value . ' Gold'];
                })
                ->all();
        @endphp

        <!-- Controls Row -->
        <form method="GET" action="{{ route('category.show', $category->slug) }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Sort -->
            <div>
                <label class="block text-xs font-medium text-[#666] uppercase tracking-wider mb-2">Sort By</label>
                <x-filter-select name="sort" placeholder="Latest First" :selected="$sort" :options="$sortOptions" />
            </div>

            <!-- Metal Filter -->
            @if ($metalTypes->count() > 0)
                <div>
                    <label class="block text-xs font-medium text-[#666] uppercase tracking-wider mb-2">Metal Type</label>
                    <x-filter-select name="metal_type" placeholder="All Metals" :selected="request()->get('metal_type', '')" :options="$metalOptions" />
                </div>
            @endif

            <!-- Purity Filter -->
            @if ($goldPurities->count() > 0)
                <div>
                    <label class="block text-xs font-medium text-[#666] uppercase tracking-wider mb-2">Gold Purity</label>
                    <x-filter-select name="gold_purity" placeholder="All Purities" :selected="request()->get('gold_purity', '')" :options="$purityOptions" />
                </div>
            @endif

            <!-- Search -->
            <div class="relative">
                <label class="block text-xs font-medium text-[#666] uppercase tracking-wider mb-2">Search</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request()->get('search', '') }}"
                        placeholder="Search by name or SKU..."
                        class="w-full pl-10 pr-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373]">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[#999]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                @if (request()->filled('search'))
                    <a href="{{ route('category.show', $category->slug) }}" class="mt-1 text-xs text-[#666] hover:text-[#d4a373]">Clear search</a>
                @endif
            </div>
        </form>

        @if ($products->count() > 0)
            <!-- Product Count Info -->
            <div class="mb-6 flex items-center justify-between">
                <span class="text-sm text-[#666]">
                    Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} products
                </span>
                @if (request()->filled('search') || request()->filled('metal_type') || request()->filled('gold_purity') || request()->filled('sort'))
                    <a href="{{ route('category.show', $category->slug) }}" class="text-sm text-[#666] hover:text-[#d4a373]">Clear All Filters</a>
                @endif
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                <div class="flex space-x-2">
                    {{ $products->onEachSide(1)->withQueryString()->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-20">
                <div class="text-[#d4a373] text-6xl mb-6">💎</div>
                <h2 class="text-2xl font-serif text-[#1a1a1a] mb-4">No products found</h2>
                <p class="text-[#666] max-w-md mx-auto">We couldn't find any products matching your criteria. Try adjusting your filters or search.</p>
                <a href="{{ route('category.show', $category->slug) }}" class="mt-6 inline-block btn-gold px-6 py-3">Reset Filters</a>
            </div>
        @endif
    </div>
</x-layout>
