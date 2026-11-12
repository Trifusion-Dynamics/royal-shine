<x-layout :seoTitle="$metaTitle" :metaDescription="$metaDescription" :canonicalUrl="$canonicalUrl" :ogImage="$ogImage">
    @php
        $primary = $primaryImage ?? $galleryImages->first();
        $catName = optional($product->category)->name;
    @endphp

    <!-- Breadcrumbs -->
    <nav class="mb-8 text-sm text-[#666]">
        <ol class="flex space-x-2">
            <li><a href="{{ route('home') }}" class="hover:text-[#d4a373]">Home</a></li>
            @if ($catName)
                <li><span>/</span></li>
                <li><a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-[#d4a373]">{{ $catName }}</a></li>
            @endif
            <li><span>/</span></li>
            <li><span class="text-[#1a1a1a] font-medium">{{ $product->name }}</span></li>
        </ol>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Image Gallery -->
            <div x-data="{ open: false, images: @js($imageUrls), currentIndex: 0 }"
                @keydown.escape.window="open = false"
                @keydown.arrow-right.window="if (open) currentIndex = (currentIndex + 1) % images.length"
                @keydown.arrow-left.window="if (open) currentIndex = (currentIndex + images.length - 1) % images.length"
                class="space-y-4">
                <div class="relative aspect-square overflow-hidden bg-[#f3f3f3] shadow-sm">
                    <button type="button" @click="open = true" class="h-full w-full cursor-zoom-in" aria-label="Open product image gallery">
                        <img x-bind:src="images[currentIndex]" alt="{{ $product->name }}" class="h-full w-full object-cover" loading="eager">
                    </button>
                </div>

                @if ($galleryImages->count() > 1)
                    <div class="flex gap-3 overflow-x-auto pb-2" aria-label="Product images">
                        @foreach ($galleryImages as $index => $image)
                            <button type="button" @click="currentIndex = {{ $index }}"
                                class="h-20 w-20 shrink-0 overflow-hidden border-2"
                                :class="currentIndex === {{ $index }} ? 'border-[#b08548]' : 'border-transparent'"
                                aria-label="View product image {{ $index + 1 }}">
                                <img src="{{ $imageUrls[$index] }}" alt="{{ $image->alt_text ?: $product->name }}" class="h-full w-full object-cover" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Lightbox -->
                <div x-cloak x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
                    role="dialog" aria-modal="true" aria-label="Product image gallery" @click.self="open = false">
                    <button type="button" @click="open = false" class="absolute right-4 top-4 z-10 text-3xl text-white" aria-label="Close gallery">&times;</button>
                    <button type="button" @click="currentIndex = (currentIndex + images.length - 1) % images.length" class="absolute left-4 top-1/2 z-10 -translate-y-1/2 text-4xl text-white hover:text-[#d4a373]" x-show="images.length > 1" aria-label="Previous image">&lsaquo;</button>
                    <button type="button" @click="currentIndex = (currentIndex + 1) % images.length" class="absolute right-4 top-1/2 z-10 -translate-y-1/2 text-4xl text-white hover:text-[#d4a373]" x-show="images.length > 1" aria-label="Next image">&rsaquo;</button>
                    <div class="relative flex max-h-[90vh] max-w-4xl items-center justify-center">
                        <template x-for="(image, index) in images" :key="index">
                            <img x-show="open && currentIndex === index" x-bind:src="image" class="max-h-[85vh] max-w-full object-contain shadow-2xl" alt="{{ $product->name }}" loading="eager">
                        </template>
                    </div>
                    <div class="absolute bottom-4 left-1/2 z-10 -translate-x-1/2 rounded-full bg-black/50 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm">
                        <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="space-y-8">
                <div>
                    @if ($catName)
                        <a href="{{ route('category.show', $product->category->slug) }}" class="inline-block text-sm text-[#d4a373] font-medium hover:underline mb-2">{{ $catName }}</a>
                    @endif
                    <h1 class="text-4xl md:text-5xl font-serif text-[#1a1a1a] mb-3 leading-tight">{{ $product->name }}</h1>
                    <p class="text-sm text-[#666] uppercase tracking-wider font-medium">SKU: {{ $product->sku }}</p>
                </div>

                <!-- Price -->
                <div class="space-y-2">
                    @if ($product->price_on_request)
                        <p class="text-xl font-medium text-[#d4a373]">Price on Request</p>
                    @elseif ($product->price)
                        <p class="text-3xl font-bold text-[#1a1a1a]">₹{{ number_format($product->price, 0) }}</p>
                        <p class="text-sm text-[#666]">Inclusive of all taxes</p>
                    @else
                        <p class="text-xl font-medium text-[#d4a373]">Price on Request</p>
                    @endif
                </div>

                <!-- Specifications -->
                <div class="bg-[#fdfaf5] border border-[#e5e5e5] p-6">
                    <h3 class="font-serif font-bold text-[#1a1a1a] mb-4 text-lg">Specifications</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        @if ($product->metal_type)
                            <dt class="text-[#666] font-medium">Material</dt>
                            <dd class="text-[#1a1a1a]">{{ ucfirst($product->metal_type) }}</dd>
                        @endif
                        @if ($product->gold_purity)
                            <dt class="text-[#666] font-medium">Gold Purity</dt>
                            <dd class="text-[#1a1a1a]">{{ $product->gold_purity->value }} Gold</dd>
                        @endif
                        @if ($product->weight && $product->weight_unit)
                            <dt class="text-[#666] font-medium">Weight</dt>
                            <dd class="text-[#1a1a1a]">{{ $product->weight }} {{ $product->weight_unit }}</dd>
                        @elseif ($product->weight)
                            <dt class="text-[#666] font-medium">Weight</dt>
                            <dd class="text-[#1a1a1a]">{{ $product->weight }}</dd>
                        @endif
                        @if ($product->stone_details)
                            <dt class="text-[#666] font-medium">Stone Details</dt>
                            <dd class="text-[#1a1a1a]">{{ $product->stone_details }}</dd>
                        @endif
                    </dl>
                </div>

                <!-- Description -->
                @if ($product->description)
                    <div>
                        <h3 class="font-serif font-bold text-[#1a1a1a] mb-3 text-lg">Description</h3>
                        <div class="prose prose-sm text-[#666] max-w-none leading-relaxed">{{ $product->description }}</div>
                    </div>
                @endif

                @if ($product->short_description && !$product->description)
                    <div class="text-[#666] text-sm leading-relaxed">{{ $product->short_description }}</div>
                @endif

                <!-- Actions -->
                <div class="pt-8 border-t border-[#e5e5e5]">
                    @if (session('success'))
                        <div class="bg-[#f0fdf4] border border-[#86efac] text-[#166534] px-4 py-3 text-sm mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="font-serif font-bold text-[#1a1a1a] mb-4 text-lg">Enquire About This Piece</h3>
                    <form action="{{ route('enquiry.store') }}" method="POST" class="space-y-4 mb-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_reference" value="{{ $product->sku }}">
                        <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('name') border-red-500 @enderror">
                                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Mobile Number <span class="text-red-500">*</span></label>
                                <input type="tel" name="mobile" value="{{ old('mobile') }}" required
                                    class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('mobile') border-red-500 @enderror">
                                @error('mobile') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Email (optional)</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('email') border-red-500 @enderror">
                            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Message <span class="text-red-500">*</span></label>
                            <textarea name="message" required rows="3" placeholder="Type your enquiry here..."
                                class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('message') border-red-500 @enderror">{{ old('message', 'I am interested in ' . $product->name . ' (SKU: ' . $product->sku . ').') }}</textarea>
                            @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="btn-gold w-full px-8 py-3 text-sm">Send Enquiry</button>
                    </form>

                    @php
                        $whatsapp = app(\App\Services\SettingsService::class)->get('whatsapp_number');
                    @endphp
                    @if ($whatsapp)
                        <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I am interested in ' . $product->name . ' (SKU: ' . $product->sku . '). Please share more details and pricing.') }}"
                           class="block w-full sm:w-auto btn-outline text-center text-sm px-8 py-3">
                            <svg class="w-5 h-5 inline mr-2 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Chat on WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if ($related->count() > 0)
            <section class="mt-16">
                <x-section-heading title="Related Products" subtitle="You may also like these pieces" />
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($related as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-layout>
