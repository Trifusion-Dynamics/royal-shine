<x-layout :seoTitle="$metaTitle" :metaDescription="$metaDescription" :canonicalUrl="$canonicalUrl" :ogImage="$ogImage">
    @php
        $settingsService = app(\App\Services\SettingsService::class);
        $whatsapp = $settingsService->get('whatsapp_number');
        $catName = optional($product->category)->name;
    @endphp

    <!-- Breadcrumb -->
    <nav class="mb-10">
        <ol class="flex flex-wrap gap-2 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-warm-gray">
            <li><a href="{{ route('home') }}" class="hover:text-gold transition-colors">Home</a></li>
            @if ($catName)
                <li aria-hidden="true" class="text-beige">/</li>
                <li><a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-gold transition-colors">{{ $catName }}</a></li>
                <li aria-hidden="true" class="text-beige">/</li>
            @endif
            <li><span class="text-charcoal font-medium">{{ $product->name }}</span></li>
        </ol>
    </nav>

    <div class="container-luxury">
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Image Gallery -->
            <div class="space-y-5">
                <div class="relative aspect-square overflow-hidden rounded-lg border border-beige bg-champagne shadow-sm cursor-pointer group"
                     @click="open = true"
                     role="button" tabindex="0"
                     aria-label="Open product image gallery"
                     x-data="{ open: false }"
                     @keydown.escape.window="open = false"
                     @keydown.arrow-right.window="if (open) currentIndex = (currentIndex + 1) % images.length"
                     @keydown.arrow-left.window="if (open) currentIndex = (currentIndex + images.length - 1) % images.length">
                    <img x-bind:src="images[currentIndex]" alt="{{ $product->name }}"
                         class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700"
                         loading="eager">
                </div>

                @if ($galleryImages->count() > 1)
                    <div class="flex gap-3 overflow-x-auto pb-2" aria-label="Product images">
                        @foreach ($galleryImages as $index => $image)
                            <button type="button" @click="currentIndex = {{ $index }}"
                                class="h-20 w-20 shrink-0 rounded-md border-2 border-transparent overflow-hidden"
                                :class="currentIndex === {{ $index }} ? 'border-gold' : 'border-beige'"
                                aria-label="View product image {{ $index + 1 }}">
                                <img src="{{ $imageUrls[$index] }}" alt="{{ $image->alt_text ?: $product->name }}"
                                     class="h-full w-full object-cover object-center" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Lightbox -->
                <div x-cloak x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-[#25221f]/95 p-4 lg:p-6"
                     role="dialog" aria-modal="true" aria-label="Product image gallery" @click.self="open = false">
                    <button type="button" @click="open = false" class="absolute right-4 top-4 z-10 text-3xl text-white" aria-label="Close gallery">&times;</button>
                    <button type="button" @click="currentIndex = (currentIndex + images.length - 1) % images.length" class="absolute left-4 top-1/2 z-10 -translate-y-1/2 text-4xl text-white hover:text-gold" x-show="images.length > 1" aria-label="Previous image">&lsaquo;</button>
                    <button type="button" @click="currentIndex = (currentIndex + 1) % images.length" class="absolute right-4 top-1/2 z-10 -translate-y-1/2 text-4xl text-white hover:text-gold" x-show="images.length > 1" aria-label="Next image">&rsaquo;</button>
                    <div class="relative flex max-h-[90vh] max-w-4xl items-center justify-center">
                        <template x-for="(image, index) in images" :key="index">
                            <img x-show="open && currentIndex === index" x-bind:src="image"
                                 class="max-h-[85vh] max-w-full object-contain shadow-2xl" alt="{{ $product->name }}" loading="eager">
                        </template>
                    </div>
                    <div class="absolute bottom-4 left-1/2 z-10 -translate-x-1/2 rounded-full bg-[#25221f]/70 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm">
                        <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="space-y-8">
                <div>
                    @if ($catName)
                        <a href="{{ route('category.show', $product->category->slug) }}" class="inline-block text-sm font-medium text-gold uppercase tracking-wider hover:underline mb-3">{{ $catName }}</a>
                    @endif
                    <h1 class="text-4xl md:text-5xl font-serif text-charcoal mb-4 leading-tight">{{ $product->name }}</h1>
                    <p class="text-sm text-warm-gray uppercase tracking-wider font-medium">SKU: {{ $product->sku }}</p>
                </div>

                <!-- Price -->
                <div class="space-y-2">
                    @if ($product->price_on_request)
                        <p class="text-2xl font-medium text-gold">Price on Request</p>
                    @elseif ($product->price)
                        <p class="text-4xl font-bold text-charcoal">₹{{ number_format($product->price, 0) }}</p>
                        <p class="text-sm text-warm-gray">Inclusive of all taxes</p>
                    @else
                        <p class="text-2xl font-medium text-gold">Price on Request</p>
                    @endif
                </div>

                <!-- Specifications -->
                @if ($product->metal_type || $product->gold_purity || $product->weight || $product->stone_details)
                    <div class="border border-beige bg-white p-6 rounded-lg">
                        <h3 class="font-serif font-bold text-charcoal mb-4 text-lg">Specifications</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                            @if ($product->metal_type)
                                <dt class="text-warm-gray font-medium">Material</dt>
                                <dd class="text-charcoal">{{ ucfirst($product->metal_type) }}</dd>
                            @endif
                            @if ($product->gold_purity)
                                <dt class="text-warm-gray font-medium">Gold Purity</dt>
                                <dd class="text-charcoal">{{ $product->gold_purity->value }} Gold</dd>
                            @endif
                            @if ($product->weight && $product->weight_unit)
                                <dt class="text-warm-gray font-medium">Weight</dt>
                                <dd class="text-charcoal">{{ $product->weight }} {{ $product->weight_unit }}</dd>
                            @elseif ($product->weight)
                                <dt class="text-warm-gray font-medium">Weight</dt>
                                <dd class="text-charcoal">{{ $product->weight }}</dd>
                            @endif
                            @if ($product->stone_details)
                                <dt class="text-warm-gray font-medium">Stone Details</dt>
                                <dd class="text-charcoal">{{ $product->stone_details }}</dd>
                            @endif
                        </dl>
                    </div>
                @endif

                <!-- Description -->
                @if ($product->description)
                    <div>
                        <h3 class="font-serif font-bold text-charcoal mb-4 text-lg">Description</h3>
                        <div class="text-warm-gray leading-relaxed">{{ $product->description }}</div>
                    </div>
                @endif

                @if ($product->short_description && !$product->description)
                    <div class="text-warm-gray text-sm leading-relaxed">{{ $product->short_description }}</div>
                @endif

                <!-- Actions -->
                <div class="pt-8 border-t border-beige">
                    @if (session('success'))
                        <div class="bg-gold/5 border border-gold/20 text-brown px-4 py-3 text-sm mb-6 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="font-serif font-bold text-charcoal mb-4 text-lg">Enquire About This Piece</h3>
                    <form action="{{ route('enquiry.store') }}" method="POST" class="space-y-4 mb-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_reference" value="{{ $product->sku }}">
                        <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-charcoal mb-1.5">Full Name <span class="text-gold">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 @error('name') border-brown @enderror">
                                @error('name') <p class="mt-1 text-xs text-brown">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-charcoal mb-1.5">Mobile Number <span class="text-gold">*</span></label>
                                <input type="tel" name="mobile" value="{{ old('mobile') }}" required
                                    class="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 @error('mobile') border-brown @enderror">
                                @error('mobile') <p class="mt-1 text-xs text-brown">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-charcoal mb-1.5">Email (optional)</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 @error('email') border-brown @enderror">
                            @error('email') <p class="mt-1 text-xs text-brown">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-charcoal mb-1.5">Message <span class="text-gold">*</span></label>
                            <textarea name="message" required rows="3" placeholder="Type your enquiry here..."
                                class="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 @error('message') border-brown @enderror">{{ old('message', 'I am interested in ' . $product->name . ' (SKU: ' . $product->sku . ').') }}</textarea>
                            @error('message') <p class="mt-1 text-xs text-brown">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="btn btn-gold w-full px-8 py-3">Send Enquiry</button>
                    </form>

                    @if ($whatsapp)
                        <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I am interested in ' . $product->name . ' (SKU: ' . $product->sku . '). Please share more details and pricing.') }}"
                           target="_blank" rel="noopener"
                           class="block w-full sm:w-auto btn btn-outline text-center text-sm px-8 py-3">
                            <svg class="w-4 h-4 inline mr-2 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Chat on WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if ($related->count() > 0)
            <section class="mt-16">
                <x-section-heading title="You May Also Like"
                                   subtitle="Complementary pieces from our collection" />
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($related as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-layout>
