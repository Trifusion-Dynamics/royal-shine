<x-layout>
    <!-- Hero Banner Slider -->
    @if ($banners->count() > 0)
        <section class="relative mb-16">
            <div class="swiper" data-loop="true" data-autoplay="true" class="h-[600px] md:h-[700px]">
                <div class="swiper-wrapper">
                    @foreach ($banners as $banner)
                        <div class="swiper-slide relative h-[600px] md:h-[700px] overflow-hidden">
                            <x-image
                                :path="$banner->image"
                                :alt="$banner->heading"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            />
                            <div class="absolute inset-0 bg-black/20 flex items-center justify-center px-4">
                                <div class="text-center text-white max-w-3xl">
                                    <span class="inline-block px-4 py-1.5 bg-[#d4a373]/20 text-[#d4a373] text-sm font-medium uppercase tracking-wider mb-4 border border-[#d4a373]/30 rounded-full">
                                        New Collection
                                    </span>
                                    <h2 class="text-4xl md:text-6xl font-serif font-bold mb-6 leading-tight">{{ $banner->heading }}</h2>
                                    @if ($banner->subheading)
                                        <p class="text-lg md:text-xl text-[#e5e5e5] mb-8 max-w-xl mx-auto">{{ $banner->subheading }}</p>
                                    @endif
                                    @if ($banner->cta_label && $banner->cta_url)
                                        <a href="{{ $banner->cta_url }}" class="btn-gold inline-block px-8 py-4 text-lg">
                                            {{ $banner->cta_label }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </section>
    @endif

    <!-- Shop by Category -->
    @if ($categories->count() > 0)
        <section class="mb-20">
            <x-section-heading title="Shop by Category" subtitle="Explore our curated jewellery collections" />
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
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
    @endif

    <!-- Featured Products -->
    @if ($featuredProducts->count() > 0)
        <section class="mb-20 bg-[#fdfaf5] py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <x-section-heading title="Featured Collection" subtitle="Handcrafted with precision and care" />
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
        <section class="mb-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <x-section-heading title="Curated Collections" subtitle="Shop our specially curated jewellery collections" />
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach ($featuredCollections as $collection)
                        <div class="group border border-[#e5e5e5] bg-white">
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

    <!-- New Arrivals -->
    @if ($newArrivals->count() > 0)
        <section class="mb-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <x-section-heading title="New Arrivals" subtitle="Latest additions to our collection" />
                    </div>
                    <a href="{{ route('jewellery.index') }}" class="text-[#d4a373] font-medium hover:underline">View All</a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($newArrivals as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- About Section -->
    <section class="mb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-serif text-[#1a1a1a] mb-6">Crafted with Passion, Worn with Pride</h2>
                    <p class="text-lg text-[#666] leading-relaxed mb-8">
                        Royal Shine brings you exquisite jewellery that celebrates every moment of your life. From timeless classics to contemporary designs, each piece is crafted with the finest materials and attention to detail.
                    </p>
                    <p class="text-lg text-[#666] leading-relaxed mb-8">
                        Discover our collection of necklaces, earrings, rings, bangles, and more — designed to make you shine on every occasion.
                    </p>
                    <x-button type="button" href="{{ route('about') }}" text="Our Story" class="px-8 py-3" />
                </div>
                <div class="relative h-[400px]">
                    <x-image
                        :path="app(\App\Services\SettingsService::class)->get('logo') ?: null"
                        :alt="app(\App\Services\SettingsService::class)->get('site_name', 'Royal Shine')"
                        class="w-full h-full object-cover rounded-none shadow-xl"
                        loading="lazy"
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Strip -->
    @php
        $galleryItems = App\Models\Gallery::where('status', 'active')->orderBy('sort_order')->take(6)->get();
    @endphp
    @if ($galleryItems->count() > 0)
        <section class="mb-20">
            <x-section-heading title="Our Gallery" subtitle="Moments captured in gold" />
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($galleryItems as $item)
                    <div class="aspect-square bg-[#f3f3f3] overflow-hidden group">
                        <x-image
                            :path="$item->image"
                            :alt="$item->title ?? 'Gallery Image'"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy"
                        />
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- WhatsApp CTA -->
    <section class="bg-[#d4a373] text-white py-20 px-6 rounded-none text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-[#d4a373]/20"></div>
        <div class="relative z-10">
            <h2 class="text-4xl md:text-5xl font-serif font-bold mb-4">Have a Question?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Reach us on WhatsApp for enquiries, custom orders, and more.</p>
            @php
                $whatsapp = app(\App\Services\SettingsService::class)->get('whatsapp_number');
            @endphp
            @if ($whatsapp)
                <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hello Royal Shine, I have an enquiry about your jewellery collection.') }}" class="bg-white text-[#d4a373] px-8 py-4 rounded-none font-bold text-lg inline-block hover:bg-[#fdfaf5] transition-colors shadow-lg">
                    Message on WhatsApp
                </a>
            @else
                <x-button text="Contact Us" href="{{ route('contact') }}" class="bg-white text-[#d4a373] hover:text-[#1a1a1a]" />
            @endif
        </div>
    </section>
</x-layout>
