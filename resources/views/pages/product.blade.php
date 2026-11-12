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
            <div class="space-y-6">
                <div class="relative aspect-square bg-[#f3f3f3] overflow-hidden shadow-sm">
                    <x-image
                        :path="$primary->image ?? $galleryImages->first()?->image"
                        :alt="$product->name"
                        class="w-full h-full object-cover cursor-pointer"
                        loading="eager"
                    />
                </div>

                @if ($galleryImages->count() > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach ($galleryImages as $img)
                            <div class="aspect-square bg-[#f3f3f3] overflow-hidden cursor-pointer hover:border-[#d4a373] hover:border-2 transition-colors group">
                                <x-image
                                    :path="$img->image"
                                    :alt="$product->name"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                    loading="lazy"
                                />
                            </div>
                        @endforeach
                    </div>
                @endif
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
