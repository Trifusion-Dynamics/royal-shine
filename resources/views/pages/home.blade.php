<x-layout>
    @php
        $settingsService = app(\App\Services\SettingsService::class);
        $imageService = app(\App\Services\ImageService::class);
        $whatsapp = $settingsService->get('whatsapp_number');

        $announcementEnabled = $settingsService->get('announcement_enabled', false);
        $announcementText = $settingsService->get('announcement_text');
        $announcementLink = $settingsService->get('announcement_link');

        $aboutImage = $settingsService->get('about_image');
        $aboutPage = \App\Models\Page::where('slug', 'about-us')->where('status', 'published')->first();
    @endphp

    {{-- Announcement Bar --}}
    @if ($announcementEnabled && $announcementText)
        <div class="bg-charcoal text-champagne border-b border-gold/20">
            <div class="container-luxury flex items-center justify-center gap-3 py-2.5 text-xs sm:text-sm font-medium tracking-wide">
                @if ($announcementLink)
                    <a href="{{ $announcementLink }}" class="hover:text-gold transition-colors inline-flex items-center gap-2">
                        {{ $announcementText }}
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @else
                    {{ $announcementText }}
                @endif
            </div>
        </div>
    @endif

    {{-- Hero Editorial Swiper --}}
    <section class="relative overflow-hidden bg-charcoal">
        @if ($banners->count() > 0)
            <div class="swiper h-[640px] md:h-[760px] lg:h-[900px] overflow-hidden"
                 data-loop="true"
                 data-autoplay="true"
                 data-effect="fade">
                <div class="swiper-wrapper">
                    @foreach ($banners as $banner)
                        <div class="swiper-slide relative h-full overflow-hidden">
                            <picture>
                                @if ($banner->mobile_image)
                                    <source media="(max-width: 767px)"
                                            srcset="{{ $imageService->url($banner->mobile_image) }}">
                                @endif
                                <img src="{{ $imageService->url($banner->image) }}"
                                     alt="{{ $banner->alt_text ?: $banner->heading }}"
                                     class="w-full h-full object-cover object-center"
                                     loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                     @if($loop->first) fetchpriority="high" @endif>
                            </picture>
                            <div class="absolute inset-0 bg-gradient-to-t from-charcoal/90 via-charcoal/40 to-charcoal/10"></div>
                            <div class="absolute inset-0 px-6 md:px-12 pt-16 md:pt-28 pb-20 flex items-end lg:items-center z-10">
                                <div class="max-w-2xl lg:max-w-xl reveal reveal-delay-1">
                                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white/10 backdrop-blur-sm text-gold text-[11px] font-semibold uppercase tracking-[0.18em] mb-6 border border-gold/40 rounded-full backdrop-filter">
                                        New Collection
                                    </span>
                                    <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl text-white mb-6 leading-[1.05] tracking-tight">
                                        {{ $banner->heading }}
                                    </h1>
                                    @if ($banner->subheading)
                                        <p class="text-base md:text-lg text-white/85 mb-10 leading-relaxed max-w-md">
                                            {{ $banner->subheading }}
                                        </p>
                                    @endif
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        @if ($banner->cta_label && $banner->cta_url)
                                            <a href="{{ $banner->cta_url }}" class="btn btn-gold btn-lg">{{ $banner->cta_label }}</a>
                                        @else
                                            <a href="{{ route('jewellery.index') }}" class="btn btn-gold btn-lg">Explore Collection</a>
                                        @endif
                                        <a href="{{ route('about') }}" class="btn btn-outline text-white border-white hover:bg-white hover:text-charcoal btn-lg">Discover Our Story</a>
                                    </div>
                                </div>
                            </div>
                            {{-- Swiper navigation per slide area handled globally --}}
                        </div>
                    @endforeach
                </div>
                {{-- Pagination --}}
                <div class="swiper-pagination bottom-6 left-1/2 -translate-x-1/2 z-30"></div>
                {{-- Navigation Arrows --}}
                <button class="swiper-button-prev absolute left-6 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-charcoal transition-all flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button class="swiper-button-next absolute right-6 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-charcoal transition-all flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        @else
            {{-- Single hero image --}}
            @php
                $heroImage = $imageService->url('images/royal-shine-necklace-collection-hero.webp');
            @endphp
            <div class="relative h-[640px] md:h-[760px] lg:h-[900px] overflow-hidden">
                <img src="{{ $heroImage }}"
                     alt="Royal Shine jewellery collection"
                     class="w-full h-full object-cover object-center"
                     loading="eager" fetchpriority="high">
                <div class="absolute inset-0 bg-gradient-to-t from-charcoal/90 via-charcoal/40 to-charcoal/10"></div>
                <div class="absolute inset-0 px-6 md:px-12 pt-16 md:pt-28 pb-20 flex items-end lg:items-center z-10">
                    <div class="max-w-2xl lg:max-w-xl z-10 reveal reveal-delay-1">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white/10 backdrop-blur-sm text-gold text-[11px] font-semibold uppercase tracking-[0.18em] mb-6 border border-gold/40 rounded-full backdrop-filter">
                            New Collection
                        </span>
                        <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl text-white mb-6 leading-[1.05] tracking-tight">
                            Elegance That Lives Forever
                        </h1>
                        <p class="text-base md:text-lg text-white/85 mb-10 leading-relaxed max-w-md">
                            Discover exquisite jewellery crafted to celebrate your most beautiful moments.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('jewellery.index') }}" class="btn btn-gold btn-lg">Explore Collection</a>
                            <a href="{{ route('about') }}" class="btn btn-outline text-white border-white hover:bg-white hover:text-charcoal btn-lg">Discover Our Story</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    {{-- Shop by Category --}}
    @if ($categories->count() > 0)
        <section class="bg-champagne py-24">
            <div class="container-luxury">
                <x-section-heading
                    eyebrow="Curated Collections"
                    title="Shop by Category"
                    subtitle="Explore our curated jewellery collections, crafted for every moment." />

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 md:gap-8">
                    @foreach ($categories->take(12) as $category)
                        <x-category-card :category="$category" />
                    @endforeach
                </div>

                <div class="mt-16 text-center">
                    <a href="{{ route('jewellery.index') }}" class="btn btn-outline">
                        View All Categories
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Featured Collections --}}
    @if ($featuredCollections->count() > 0)
        <section class="py-24">
            <div class="container-luxury">
                <x-section-heading
                    eyebrow="Signature Edits"
                    title="Featured Collections"
                    subtitle="Handcrafted pieces, thoughtfully curated for every occasion." />

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                    @foreach ($featuredCollections as $index => $collection)
                        @php $first = $index === 0; @endphp
                        <a href="{{ route('collection.show', $collection->slug) }}"
                           class="group relative block overflow-hidden rounded-xl border border-beige bg-white aspect-[4/5] @if ($first) lg:col-span-2 @endif">
                            @if ($collection->image)
                                <x-image :path="$collection->image" :alt="$collection->name"
                                         class="w-full h-full object-cover transition-transform duration-[900ms] ease-out group-hover:scale-105"
                                         loading="lazy" />
                            @else
                                <div class="w-full h-full lazy-placeholder flex items-center justify-center">
                                    <span class="text-warm-gray text-sm font-medium uppercase tracking-[0.2em]">
                                        {{ str($collection->name)->substr(0, 2)->upper() }}
                                    </span>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-charcoal/90 via-charcoal/25 to-transparent"></div>

                            <div class="absolute inset-x-0 bottom-0 p-6 md:p-8 z-10">
                                <span class="inline-block mb-3 px-3 py-1 bg-gold text-white text-[10px] font-bold uppercase tracking-[0.18em] rounded-full">
                                    Collection {{ $index + 1 }}
                                </span>
                                <h3 class="font-serif text-xl md:text-2xl text-white mb-2 leading-tight transition-colors group-hover:text-gold/95">
                                    {{ $collection->name }}
                                </h3>
                                @if ($collection->description)
                                    <p class="text-sm md:text-base text-white/80 leading-relaxed mb-4 line-clamp-2 max-w-lg">
                                        {{ $collection->description }}
                                    </p>
                                @endif
                                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-gold uppercase tracking-[0.14em]">
                                    Explore Collection
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured Products --}}
    @if ($featuredProducts->count() > 0)
        <section class="bg-ivory py-24">
            <div class="container-luxury">
                <div class="flex flex-col lg:flex-row items-end justify-between gap-8 mb-16">
                    <div class="max-w-2xl">
                        <x-section-heading
                            eyebrow="Curator's Picks"
                            title="Featured Pieces"
                            subtitle="Handpicked designs our clients love most." />
                    </div>
                    <a href="{{ route('jewellery.index') }}" class="btn btn-outline whitespace-nowrap">
                        Browse All
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-8 md:gap-10">
                    @foreach ($featuredProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- New Arrivals --}}
    @if ($newArrivals->count() > 0)
        <section class="py-24">
            <div class="container-luxury">
                <x-section-heading
                    eyebrow="Just In"
                    title="New Arrivals"
                    subtitle="The latest additions to our collection." />

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-8 md:gap-10">
                    @foreach ($newArrivals as $product)
                        @php
                            $firstImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                        @endphp
                        <article class="card group">
                            <div class="image-wrap aspect-square relative">
                                @if ($firstImage)
                                    <x-image :path="$firstImage->image"
                                             :alt="$firstImage->alt_text ?: $product->name"
                                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                             loading="lazy" />
                                @else
                                    <div class="w-full h-full lazy-placeholder flex items-center justify-center">
                                        <span class="text-warm-gray text-xs font-medium uppercase tracking-[0.2em]">
                                            {{ str($product->name)->substr(0, 1)->upper() }}
                                        </span>
                                    </div>
                                @endif

                                <span class="absolute top-3 left-3 z-10 inline-flex items-center px-2.5 py-1 bg-charcoal text-white text-[10px] font-semibold uppercase tracking-[0.16em] rounded-full shadow-sm">
                                    New
                                </span>
                            </div>

                            <div class="p-6">
                                @if ($product->category)
                                    <p class="text-[11px] font-semibold text-gold uppercase tracking-[0.16em] mb-1.5">
                                        {{ $product->category->name }}
                                    </p>
                                @endif
                                <h3 class="font-serif text-lg text-charcoal mb-3 leading-tight group-hover:text-gold transition-colors">
                                    <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>

                                <div class="flex items-baseline gap-2 mb-6">
                                    @if ($product->price_on_request || ! $product->price)
                                        <span class="text-sm font-medium text-gold">Price on Request</span>
                                    @else
                                        <span class="text-xl font-semibold text-charcoal">₹{{ number_format($product->price, 0) }}</span>
                                    @endif
                                </div>

                                <a href="{{ route('enquiry.create', ['product' => $product->id]) }}"
                                   class="btn btn-text text-gold hover:text-brown text-sm inline-flex items-center gap-1.5">
                                    Enquire
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Brand Story --}}
    @if ($aboutImage || $aboutPage?->content)
        <section class="py-24">
            <div class="container-luxury">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-24 items-center">
                    @if ($aboutImage)
                        <div class="relative h-[560px] md:h-[660px] overflow-hidden rounded-xl border border-beige shadow-luxury-lg reveal reveal-delay-1">
                            <x-image :path="$aboutImage"
                                     :alt="$aboutPage->title ?? 'Royal Shine Brand Story'"
                                     class="w-full h-full object-cover object-center transition-transform duration-[900ms] ease-out"
                                     loading="lazy" />
                        </div>
                    @endif

                    <div class="reveal reveal-delay-2 max-w-lg">
                        <span class="inline-block text-[11px] font-semibold text-gold uppercase tracking-[0.2em] mb-5">
                            Our Story
                        </span>
                        <h2 class="font-serif text-3xl md:text-5xl text-charcoal mb-7 leading-tight">
                            Crafted with Passion.<br><span class="text-gold">Worn with Pride.</span>
                        </h2>

                        @if ($aboutPage?->content)
                            <div class="prose text-warm-gray leading-relaxed max-w-none mb-8">
                                {!! $aboutPage->content !!}
                            </div>
                        @else
                            <p class="text-base md:text-lg text-warm-gray leading-relaxed mb-6 max-w-lg">
                                Jewellery can hold a memory, mark a milestone, or simply make an ordinary day feel special.
                                Explore the Royal Shine catalogue to discover pieces for celebrations and everyday expression.
                            </p>
                            <p class="text-base md:text-lg text-warm-gray leading-relaxed mb-8 max-w-lg">
                                Browse necklaces, earrings, rings, bangles and more. Each product page shares the available
                                details and an easy way to ask our team a question.
                            </p>
                        @endif

                        <a href="{{ route('about') }}" class="btn btn-outline">
                            Our Story
                            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Gallery --}}
    @if ($galleryItems->count() > 0)
        <section class="bg-champagne py-24">
            <div class="container-luxury">
                <x-section-heading
                    eyebrow="Captured Moments"
                    title="Our Gallery"
                    subtitle="Moments captured in gold, light and elegance." />

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                    @foreach ($galleryItems->take(8) as $item)
                        <div class="relative aspect-[4/5] overflow-hidden rounded-xl border border-beige bg-white group card">
                            <x-image :path="$item->image"
                                     :alt="$item->alt_text ?: ($item->title ?? 'Gallery')"
                                     class="w-full h-full object-cover object-center transition-transform duration-[900ms] ease-out group-hover:scale-105"
                                     loading="lazy" />
                            <div class="absolute inset-x-0 bottom-0 p-5 pt-16 bg-gradient-to-t from-[#25221f]/90 to-transparent">
                                <span class="text-sm font-medium text-white block mb-1">{{ $item->title ?? 'Royal Shine' }}</span>
                                @if ($item->description)
                                    <p class="text-xs text-white/70 line-clamp-1">{{ $item->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-16 text-center">
                    <a href="{{ route('gallery') }}" class="btn btn-text text-gold hover:text-brown">
                        View Gallery
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
        <section class="py-24">
            <div class="container-luxury">
                <x-section-heading
                    eyebrow="Client Experiences"
                    title="Kind Words"
                    subtitle="Experiences shared by our customers." />

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($testimonials as $testimonial)
                        <figure class="border border-beige bg-white p-8 rounded-lg card">
                            <div class="flex items-center gap-4 mb-6">
                                @if ($testimonial->image)
                                    <div class="w-10 h-10 shrink-0 rounded-full overflow-hidden border border-beige">
                                        <x-image :path="$testimonial->image" :alt="$testimonial->customer_name"
                                                 class="w-full h-full object-cover" loading="lazy" />
                                    </div>
                                @else
                                    <div class="w-10 h-10 shrink-0 rounded-full bg-beige flex items-center justify-center">
                                        <span class="text-warm-gray text-xs font-medium uppercase tracking-wider">
                                            {{ str($testimonial->customer_name)->substr(0, 1)->upper() }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-charcoal leading-tight">{{ $testimonial->customer_name }}</p>
                                    @if ($testimonial->customer_location)
                                        <p class="text-xs text-warm-gray uppercase tracking-wider">{{ $testimonial->customer_location }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex text-gold mb-6">
                                @for ($i = 1; $i <= ($testimonial->rating ?? 5); $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.97 0 1.399 2.18.747 2.979l-1.536 1.912a1 1 0 00.343 1.378l1.804 2.627a1 1 0 01-.156 1.219l-2.823.416a1 1 0 00-.997.66l-.393 3.167a1 1 0 01-1.596.347l-2.395-1.74a1 1 0 00-1.16-.577l-2.394 1.74a1 1 0 01-1.597-.347l-.393-3.167a1 1 0 00-.996-.66l-2.823-.416a1 1 0 01-.156-1.219l1.804-2.627a1 1 0 00.343-1.378L4.35 6.897a1 1 0 00.342-.69z"></path></svg>
                                @endfor
                            </div>
                            <p class="text-warm-gray leading-relaxed italic">“{{ $testimonial->testimonial }}”</p>
                        </figure>
                    @endforeach
                </div>

                <div class="mt-14 text-center">
                    <a href="{{ route('testimonials') }}" class="btn btn-text text-gold hover:text-brown">
                        Read All Stories
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Contact / WhatsApp CTA --}}
    <section class="py-24">
        <div class="container-luxury">
            <div class="border border-beige bg-ivory rounded-2xl p-8 md:p-14 text-center lg:text-left shadow-sm">
                <div class="lg:flex lg:items-center lg:justify-between gap-10">
                    <div class="lg:max-w-2xl">
                        <h2 class="text-3xl md:text-4xl font-serif text-charcoal mb-5 leading-tight">
                            Let&rsquo;s Find Something Beautiful
                        </h2>
                        <p class="text-warm-gray leading-relaxed mb-8 max-w-lg">
                            Have a question about a design or looking for something special? Our team is here to help.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-end">
                        @if ($whatsapp)
                            <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}"
                               target="_blank" rel="noopener"
                               class="btn btn-brown btn-lg whitespace-nowrap flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Chat on WhatsApp
                            </a>
                        @endif
                        <a href="{{ route('contact') }}" class="btn btn-outline btn-lg whitespace-nowrap">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
