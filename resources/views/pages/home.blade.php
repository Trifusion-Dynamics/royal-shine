<x-layout>
    <!-- Announcement Bar -->
    @php
        $announcementEnabled = app(\App\Services\SettingsService::class)->get('announcement_enabled', false);
        $announcementText = app(\App\Services\SettingsService::class)->get('announcement_text');
        $announcementLink = app(\App\Services\SettingsService::class)->get('announcement_link');
    @endphp
    @if ($announcementEnabled && $announcementText)
        <div class="bg-[#d4a373] text-white text-center py-3 px-4 text-sm font-medium border-b border-[#b08548]">
            @if ($announcementLink)
                <a href="{{ $announcementLink }}" class="text-white hover:underline">{{ $announcementText }}</a>
            @else
                {{ $announcementText }}
            @endif
        </div>
    @endif

    <!-- Hero Banner Slider -->
    @if ($banners->count() > 0)
        <section class="relative mb-16">
            <div class="swiper h-[600px] md:h-[700px]" data-loop="true" data-autoplay="true">
                <div class="swiper-wrapper">
                    @foreach ($banners as $banner)
                        <div class="swiper-slide relative h-[600px] md:h-[700px] overflow-hidden">
                            <picture>
                                @if ($banner->mobile_image)
                                    <source media="(max-width: 767px)" srcset="{{ app(\App\Services\ImageService::class)->url($banner->mobile_image) }}">
                                @endif
                                <img
                                    src="{{ app(\App\Services\ImageService::class)->url($banner->image) }}"
                                    alt="{{ $banner->alt_text ?: $banner->heading }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                >
                            </picture>
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

    @if ($banners->isEmpty())
        <section class="relative overflow-hidden bg-[#241d18] text-white">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_75%_45%,rgba(212,163,115,0.2),transparent_38%)]"></div>
            <div class="relative mx-auto grid max-w-7xl items-center gap-8 px-6 py-20 sm:px-8 md:min-h-[560px] md:grid-cols-2 md:py-24">
                <div class="max-w-xl">
                    <p class="mb-5 text-xs font-semibold uppercase tracking-[0.3em] text-[#d4a373]">Royal Shine Jewellery</p>
                    <h1 class="serif mb-6 text-5xl leading-tight sm:text-6xl">A little brilliance for every moment.</h1>
                    <p class="mb-8 max-w-lg text-base leading-7 text-white/75 sm:text-lg">
                        Explore a considered collection of necklaces, earrings, rings and keepsakes. Find a piece you love, then enquire with our team.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('jewellery.index') }}" class="btn-gold">Explore Jewellery</a>
                        <a href="{{ route('enquiry.create') }}" class="inline-flex items-center border border-white/40 px-6 py-3 font-medium text-white transition hover:border-[#d4a373] hover:text-[#d4a373]">Enquire Now</a>
                    </div>
                </div>
                <div class="relative mx-auto flex aspect-square w-full max-w-[420px] items-center justify-center">
                    <div class="absolute inset-5 rounded-full border border-[#d4a373]/20"></div>
                    <div class="absolute inset-12 rounded-full border border-[#d4a373]/35"></div>
                    <svg viewBox="0 0 360 360" role="img" aria-label="Illustration of a gold pendant necklace" class="relative h-full w-full drop-shadow-[0_25px_45px_rgba(0,0,0,0.35)]">
                        <defs>
                            <linearGradient id="gold-chain" x1="0" x2="1" y1="0" y2="1">
                                <stop offset="0" stop-color="#f8dfa7"/>
                                <stop offset=".5" stop-color="#c4934e"/>
                                <stop offset="1" stop-color="#f2d394"/>
                            </linearGradient>
                            <radialGradient id="pendant-stone">
                                <stop offset="0" stop-color="#fff5d8"/>
                                <stop offset="1" stop-color="#bd8740"/>
                            </radialGradient>
                        </defs>
                        <path d="M75 60c8 104 37 151 105 205 68-54 97-101 105-205" fill="none" stroke="url(#gold-chain)" stroke-width="5"/>
                        <path d="M180 248v20" stroke="url(#gold-chain)" stroke-width="5"/>
                        <path d="M180 266l31 33-31 34-31-34z" fill="url(#gold-chain)" stroke="#f8dfa7" stroke-width="2"/>
                        <path d="M180 276l19 23-19 22-19-22z" fill="url(#pendant-stone)" stroke="#fff0c8" stroke-width="2"/>
                        <circle cx="75" cy="60" r="5" fill="#f8dfa7"/>
                        <circle cx="285" cy="60" r="5" fill="#f8dfa7"/>
                    </svg>
                    <span class="absolute bottom-1 left-1/2 -translate-x-1/2 text-[10px] uppercase tracking-[0.35em] text-white/55">A timeless keepsake</span>
                </div>
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
                        <a href="{{ route('collection.show', $collection->slug) }}" class="group block border border-[#e5e5e5] bg-white">
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
                            </a>
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
                    @if ($aboutPage?->content)
                        <div class="prose prose-lg mb-8 max-w-none text-[#666]">{!! $aboutPage->content !!}</div>
                    @else
                        <p class="text-lg text-[#666] leading-relaxed mb-8">
                            Jewellery can hold a memory, mark a milestone, or simply make an ordinary day feel special. Explore the Royal Shine catalogue to discover pieces for celebrations and everyday expression.
                        </p>
                        <p class="text-lg text-[#666] leading-relaxed mb-8">
                            Browse necklaces, earrings, rings, bangles and more. Each product page includes the available details and an easy way to ask our team a question.
                        </p>
                    @endif
                    <x-button type="button" href="{{ route('about') }}" text="Our Story" class="px-8 py-3" />
                </div>
                <div class="relative h-[400px]">
                    @php $aboutImage = app(\App\Services\SettingsService::class)->get('about_image'); @endphp
                    @if ($aboutImage)
                        <x-image :path="$aboutImage" :alt="$aboutPage->title ?? 'Royal Shine'" class="h-full w-full object-cover shadow-xl" loading="lazy" />
                    @else
                        <div class="flex h-full items-center justify-center bg-[#efe6d9] text-center">
                            <div>
                                <span class="serif block text-6xl text-[#b08548]">RS</span>
                                <span class="mt-3 block text-xs uppercase tracking-[0.3em] text-[#7b6853]">Royal Shine</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Strip -->
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

    @if ($testimonials->isNotEmpty())
        <section class="mb-20 bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <x-section-heading title="Kind Words" subtitle="Experiences shared by our customers" />
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <figure class="border border-[#e5e5e5] bg-[#fdfaf5] p-7">
                            <blockquote class="leading-7 text-[#555]">“{{ $testimonial->testimonial }}”</blockquote>
                            <figcaption class="mt-5 text-sm font-semibold text-[#1a1a1a]">{{ $testimonial->customer_name }}</figcaption>
                        </figure>
                    @endforeach
                </div>
                <div class="mt-8 text-center">
                    <a href="{{ route('testimonials') }}" class="text-sm font-medium text-[#b08548] hover:underline">Read customer stories</a>
                </div>
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
