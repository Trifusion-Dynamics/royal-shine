@php
    $siteName = app(\App\Services\SettingsService::class)->get('site_name', 'Royal Shine');
@endphp

<x-layout :seoTitle="'Jewellery'" :canonicalUrl="route('jewellery.index', absolute: true)">
    <!-- Hero -->
    <section class="bg-[#1a1a1a] text-white py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl md:text-6xl font-serif font-bold mb-4">Our Jewellery</h1>
            <div class="w-20 h-1 bg-[#d4a373] mx-auto mb-6"></div>
            <p class="text-lg text-[#e5e5e5] max-w-2xl mx-auto">
                Discover handcrafted designs across our complete collection — from timeless bridal pieces to everyday elegance.
            </p>
        </div>
    </section>

    <!-- All Categories -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading title="Shop by Category" subtitle="Explore all our jewellery categories" />

            @if ($categories->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" class="group block bg-white border border-[#e5e5e5] p-4 text-center hover:border-[#d4a373] transition-colors">
                            <div class="aspect-square relative mb-4 overflow-hidden">
                                @if ($category->image)
                                    <x-image
                                        :path="$category->image"
                                        :alt="$category->name"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    />
                                @else
                                    <div class="w-full h-full bg-[#f3f3f3] flex items-center justify-center">
                                        <span class="text-[#999] text-sm uppercase tracking-wider">{{ str($category->name)->substr(0, 2)->upper() }}</span>
                                    </div>
                                @endif
                            </div>
                            <h3 class="text-lg font-serif text-[#1a1a1a] group-hover:text-[#d4a373] transition-colors">{{ $category->name }}</h3>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-center text-[#666]">Categories will appear here once added.</p>
            @endif
        </div>
    </section>

    <!-- Featured Products -->
    @if ($featuredProducts->count() > 0)
        <section class="py-16 bg-[#fdfaf5]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <x-section-heading title="Featured Products" subtitle="Selected pieces from our collection" />
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($featuredProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Collections -->
    @if ($featuredCollections->count() > 0)
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <x-section-heading title="Curated Collections" subtitle="Shop our specially curated jewellery collections" />
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach ($featuredCollections as $collection)
                        <div class="group relative overflow-hidden border border-[#e5e5e5] bg-white">
                            <div class="aspect-[4/5] overflow-hidden">
                                @if ($collection->image)
                                    <x-image
                                        :path="$collection->image"
                                        :alt="$collection->name"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    />
                                @else
                                    <div class="w-full h-full bg-[#f3f3f3]"></div>
                                @endif
                            </div>
                            <div class="p-4 text-center">
                                <h3 class="text-lg font-serif text-[#1a1a1a] group-hover:text-[#d4a373] transition-colors">{{ $collection->name }}</h3>
                                @if ($collection->description)
                                    <p class="text-sm text-[#666] mt-1 line-clamp-2">{{ $collection->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Enquiry CTA -->
    <section class="bg-[#d4a373] text-white py-16 px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4">Looking for Something Special?</h2>
        <p class="text-lg mb-8 max-w-2xl mx-auto">Reach out to us for custom designs and personalised assistance.</p>
        <a href="{{ route('contact') }}" class="bg-white text-[#d4a373] px-8 py-4 font-bold text-lg inline-block hover:bg-[#fdfaf5] transition-colors">
            Enquire Now
        </a>
    </section>
</x-layout>
