<x-layout>
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-serif text-[#1a1a1a] mb-6">Our Collections</h1>
                <div class="w-20 h-1 bg-[#d4a373] mx-auto"></div>
                <p class="text-lg text-[#666] mt-4">Curated collections for every occasion</p>
            </div>

            @if ($collections->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($collections as $collection)
                        <div class="group bg-white border border-[#e5e5e5] p-6 hover:border-[#d4a373] transition-colors">
                            @if ($collection->image)
                                <div class="aspect-square mb-4 overflow-hidden mx-auto w-40 h-40">
                                    <x-image
                                        :path="$collection->image"
                                        :alt="$collection->name"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    />
                                </div>
                            @endif
                            <h3 class="text-lg font-serif text-[#1a1a1a] mb-2 group-hover:text-[#d4a373] transition-colors">{{ $collection->name }}</h3>
                            @if ($collection->description)
                                <p class="text-sm text-[#666] line-clamp-2 mb-4">{{ $collection->description }}</p>
                            @endif
                            <a href="{{ route('collection.show', $collection->slug) }}" class="text-sm text-[#d4a373] font-medium hover:underline">Explore Collection</a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-[#d4a373] text-6xl mb-6">📦</div>
                    <h2 class="text-2xl font-serif text-[#1a1a1a] mb-4">No Collections Yet</h2>
                    <p class="text-[#666]">Collections will appear here once added from the admin panel.</p>
                </div>
            @endif

            <!-- Featured Products -->
            @if ($featuredProducts->count() > 0)
                <section class="mt-20">
                    <x-section-heading title="Featured Products" subtitle="From our collections" />
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                        @foreach ($featuredProducts as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </section>
</x-layout>
