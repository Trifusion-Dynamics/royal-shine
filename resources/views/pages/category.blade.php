<x-layout :seoTitle="$seoTitle" :metaDescription="$metaDescription" :canonicalUrl="$canonicalUrl" :ogImage="$ogImage">
    @php
        $settingsService = app(\App\Services\SettingsService::class);
        $whatsapp = $settingsService->get('whatsapp_number');
    @endphp

    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex flex-wrap gap-2 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-warm-gray">
            <li><a href="{{ route('home') }}" class="hover:text-gold transition-colors">Home</a></li>
            <li aria-hidden="true" class="text-beige">/</li>
            <li><a href="{{ route('jewellery.index') }}" class="hover:text-gold transition-colors">Jewellery</a></li>
            <li aria-hidden="true" class="text-beige">/</li>
            <li><span class="text-charcoal font-medium">{{ $category->name }}</span></li>
        </ol>
    </nav>

    <header class="mb-12">
        <div class="container-luxury text-center">
            <h1 class="text-4xl md:text-5xl font-serif text-charcoal mb-3 leading-tight">{{ $category->name }}</h1>
            @if ($category->description)
                <p class="text-lg text-warm-gray max-w-2xl mx-auto leading-relaxed">{{ $category->description }}</p>
            @endif
        </div>
    </header>

    <div class="container-luxury">
        @php
            $sortOptions = ['latest' => 'Latest First'];
            if ($hasPrices) {
                $sortOptions['price_low'] = 'Price: Low to High';
                $sortOptions['price_high'] = 'Price: High to Low';
            }
            $metalLabels = ['gold' => 'Gold', 'silver' => 'Silver', 'artificial' => 'Artificial', 'other' => 'Other'];
            $metalOptions = [];
            foreach ($metalTypes as $metal) {
                $value = method_exists($metal, 'value') ? $metal->value : (string) $metal;
                $metalOptions[$value] = $metalLabels[$value] ?? ucfirst($value);
            }
            $purityOptions = [];
            foreach ($goldPurities as $purity) {
                $purityOptions[$purity] = $purity . ' Gold';
            }
        @endphp

        <!-- Controls -->
        <form method="GET" action="{{ route('category.show', $category->slug) }}"
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <div>
                <label class="block text-xs font-semibold text-gold uppercase tracking-wider mb-2">Sort By</label>
                <select name="sort" class="w-full bg-white border border-beige rounded-md px-4 py-3 text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition-colors" onchange="this.form.submit()">
                    @foreach ($sortOptions as $value => $label)
                        <option value="{{ $value }}" {{ request()->get('sort', 'latest') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @if ($metalTypes->count() > 0)
                <div>
                    <label class="block text-xs font-semibold text-gold uppercase tracking-wider mb-2">Metal Type</label>
                    <select name="metal_type" class="w-full bg-white border border-beige rounded-md px-4 py-3 text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition-colors" onchange="this.form.submit()">
                        <option value="">All Metals</option>
                        @foreach ($metalOptions as $value => $label)
                            <option value="{{ $value }}" {{ request()->get('metal_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if ($goldPurities->count() > 0)
                <div>
                    <label class="block text-xs font-semibold text-gold uppercase tracking-wider mb-2">Gold Purity</label>
                    <select name="gold_purity" class="w-full bg-white border border-beige rounded-md px-4 py-3 text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition-colors" onchange="this.form.submit()">
                        <option value="">All Purities</option>
                        @foreach ($purityOptions as $value => $label)
                            <option value="{{ $value }}" {{ request()->get('gold_purity') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if ($collections->isNotEmpty())
                <div>
                    <label class="block text-xs font-semibold text-gold uppercase tracking-wider mb-2">Collection</label>
                    <select name="collection_id" class="w-full bg-white border border-beige rounded-md px-4 py-3 text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition-colors" onchange="this.form.submit()">
                        <option value="">All Collections</option>
                        @foreach ($collections as $collection)
                            <option value="{{ $collection->id }}" {{ request('collection_id') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </form>

        @if ($products->count() > 0)
            <div class="mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <span class="text-sm text-warm-gray">
                    Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} pieces
                </span>
                @if (request()->filled('search') || request()->filled('metal_type') || request()->filled('gold_purity') || request()->filled('collection_id'))
                    <a href="{{ route('category.show', $category->slug) }}"
                       class="text-sm text-warm-gray hover:text-gold underline transition-colors">Clear All Filters</a>
                @endif
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-14 flex justify-center">
                <div class="flex -space-x-px">
                    {{ $products->onEachSide(1)->withQueryString()->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-20">
                <div class="text-gold text-6xl mb-6">💎</div>
                <h2 class="text-3xl font-serif text-charcoal mb-4">No pieces found</h2>
                <p class="text-warm-gray max-w-md mx-auto leading-relaxed">
                    We couldn't find any products matching your criteria. Try adjusting your filters or search.
                </p>
                <a href="{{ route('category.show', $category->slug) }}"
                   class="mt-8 inline-block btn btn-gold px-8 py-3">Reset Filters</a>
            </div>
        @endif
    </div>
</x-layout>
