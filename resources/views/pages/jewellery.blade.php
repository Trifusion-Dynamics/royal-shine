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

    <!-- Our Jewellery Collections -->
    @if ($collections->isNotEmpty())
        <section class="mb-20 bg-[#f7f1e8] py-16 md:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto mb-12 max-w-2xl text-center">
                    <p class="mb-3 text-[10px] font-semibold uppercase tracking-[.28em] text-[#a77b43]">The Royal Shine Edit</p>
                    <h2 class="font-serif text-3xl leading-tight text-[#29231e] md:text-5xl">Our Jewellery Collections</h2>
                    <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-[#766b60] md:text-base">Explore thoughtfully curated edits for celebrations, meaningful gifts and everyday elegance.</p>
                    <span class="mx-auto mt-6 block h-px w-14 bg-[#bd955e]"></span>
                </div>
                <div class="grid grid-cols-1 items-stretch gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($collections as $collection)
                        <a href="{{ route('collection.show', $collection->slug) }}"
                           class="group flex h-full min-w-0 flex-col overflow-hidden border border-[#e8ddcf] bg-white shadow-[0_4px_18px_rgba(50,35,20,.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_16px_38px_rgba(50,35,20,.12)]">
                            <div class="relative aspect-[4/3] overflow-hidden bg-[#eee5d8]">
                                @if ($collection->image)
                                    <x-image :path="$collection->image" :alt="$collection->name" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" />
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#f3eadc] to-[#e3d1b6]">
                                        <span class="font-serif text-5xl text-[#a77b43]">{{ str($collection->name)->substr(0, 2)->upper() }}</span>
                                    </div>
                                @endif
                                <span class="absolute left-4 top-4 bg-white/95 px-3 py-1.5 text-[9px] font-semibold uppercase tracking-[.2em] text-[#8f6a3d]">Royal Shine</span>
                            </div>
                            <div class="flex flex-1 items-center justify-between gap-4 px-5 py-5 md:px-6">
                                <div class="min-w-0">
                                    <h3 class="font-serif text-xl text-[#29231e] transition-colors group-hover:text-[#9a713e] md:text-2xl">{{ $collection->name }}</h3>
                                    <p class="mt-2 line-clamp-2 text-xs leading-5 text-[#81766a] md:text-sm">{{ $collection->description ?: 'Discover the designs in this collection.' }}</p>
                                </div>
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full border border-[#dfceb5] text-lg text-[#9a713e] transition-all group-hover:border-[#9a713e] group-hover:bg-[#9a713e] group-hover:text-white" aria-hidden="true">↗</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

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
