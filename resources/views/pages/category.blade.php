<x-layout>
    <!-- Breadcrumb -->
    <nav class="mb-8 text-sm text-[#666]">
        <ol class="flex space-x-2">
            <li><a href="{{ route('home') }}" class="hover:text-[#d4a373]">Home</a></li>
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
        <!-- Controls Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Sort -->
            <div class="col-span-1 sm:col-span-1 lg:col-span-1">
                <label class="block text-xs font-medium text-[#666] uppercase tracking-wider mb-2">Sort By</label>
                <select
                    name="sort"
                    id="sort"
                    class="w-full bg-white border border-[#e5e5e5] px-4 py-3 rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373]"
                    onchange="this.form.submit()"
                >
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest First</option>
                    @if ($hasPrices)
                        <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    @endif
                </select>
            </div>

            <!-- Metal Filter -->
            @if ($metalTypes->count() > 0)
                <div class="col-span-1 sm:col-span-1 lg:col-span-1">
                    <label class="block text-xs font-medium text-[#666] uppercase tracking-wider mb-2">Metal Type</label>
                    <select
                        name="metal_type"
                        id="metal_type"
                        class="w-full bg-white border border-[#e5e5e5] px-4 py-3 rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373]"
                        onchange="this.form.submit()"
                    >
                        <option value="">All Metals</option>
                        @foreach ($metalTypes as $metal)
                            <option value="{{ $metal }}" {{ request()->get('metal_type') === $metal ? 'selected' : '' }}>{{ $metal }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Purity Filter -->
            @if ($goldPurities->count() > 0)
                <div class="col-span-1 sm:col-span-1 lg:col-span-1">
                    <label class="block text-xs font-medium text-[#666] uppercase tracking-wider mb-2">Gold Purity</label>
                    <select
                        name="gold_purity"
                        id="gold_purity"
                        class="w-full bg-white border border-[#e5e5e5] px-4 py-3 rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373]"
                        onchange="this.form.submit()"
                    >
                        <option value="">All Purities</option>
                        @foreach ($goldPurities as $purity)
                            <option value="{{ $purity }}" {{ request()->get('gold_purity') === $purity ? 'selected' : '' }}>{{ $purity }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Search -->
            <div class="col-span-1 sm:col-span-1 lg:col-span-1 relative">
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
        </div>

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
