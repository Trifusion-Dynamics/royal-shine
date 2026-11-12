<x-layout>
    <!-- Hero -->
    <section class="relative h-[500px] md:h-[600px] overflow-hidden mb-16">
        <div class="absolute inset-0 bg-[#fdfaf5]"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-serif text-[#1a1a1a] mb-6">Our Jewellery Collections</h1>
                <p class="text-lg text-[#666] max-w-2xl mx-auto mb-8">
                    Explore our curated collections of fine jewellery. From timeless necklaces to elegant earrings, each piece is crafted with passion and precision.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('category.show', $categories->first()->slug ?? 'necklaces') }}" class="btn-gold px-8 py-4 text-lg">Shop All Categories</a>
                    @php
                        $whatsapp = app(\App\Services\SettingsService::class)->get('whatsapp_number');
                    @endphp
                    @if ($whatsapp)
                        <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hello Royal Shine, I would like to know more about your jewellery collections.') }}" class="btn-outline px-8 py-4 text-lg text-center text-white hover:bg-white hover:text-[#d4a373] border-white">WhatsApp Us</a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Introduction -->
    <section class="mb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-serif text-[#1a1a1a] mb-6">Discover Our Collections</h2>
            <p class="text-[#666] leading-relaxed mb-8 max-w-2xl mx-auto">
                Royal Shine offers a wide range of jewellery across 12 categories, each designed to celebrate life's special moments. Whether you're looking for bridal jewellery, everyday wear, or something unique, find your perfect piece here.
            </p>
        </div>
    </section>

    <!-- All Categories -->
    <section class="mb-20">
        <x-section-heading title="All Categories" subtitle="Browse by category" />
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($categories as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="group block">
                    <div class="aspect-square relative mb-4 overflow-hidden mx-auto w-32 h-32">
                        @if ($category->image)
                            <x-image
                                :path="$category->image"
                                :alt="$category->name"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                loading="lazy"
                            />
                        @else
                            <div class="w-full h-full bg-[#f3f3f3] flex items-center justify-center">
                                <span class="text-[#999] text-xs uppercase tracking-wider">{{ str($category->name)->substr(0, 2)->upper() }}</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                    </div>
                    <h3 class="text-lg font-serif text-center text-[#1a1a1a] group-hover:text-[#d4a373] transition-colors">{{ $category->name }}</h3>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Featured Products -->
    @if ($featuredProducts->count() > 0)
        <section class="mb-20 bg-[#fdfaf5] py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <x-section-heading title="Featured Products" subtitle="Handpicked for you" />
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($featuredProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- WhatsApp CTA -->
    <section class="bg-[#d4a373] text-white py-20 px-6 rounded-none text-center">
        <div class="relative z-10">
            <h2 class="text-4xl md:text-5xl font-serif font-bold mb-4">Have a Question?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">WhatsApp us for enquiries, custom orders, and more.</p>
            @php
                $whatsapp = app(\App\Services\SettingsService::class)->get('whatsapp_number');
            @endphp
            @if ($whatsapp)
                <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hello Royal Shine, I have an enquiry about your jewellery collection.') }}" class="bg-white text-[#d4a373] px-8 py-4 rounded-none font-bold text-lg inline-block hover:bg-[#fdfaf5] transition-colors shadow-lg">
                    Message on WhatsApp
                </a>
            @endif
        </div>
    </section>
</x-layout>
