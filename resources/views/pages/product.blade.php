<x-layout :seoTitle="$metaTitle" :metaDescription="$metaDescription" :canonicalUrl="$canonicalUrl" :ogImage="$ogImage">
    @php
        $settingsService = app(\\App\\Services\\SettingsService::class);
        $imageService = app(\\App\\Services\\ImageService::class);
        $whatsapp = $settingsService->get('whatsapp_number');
        $catName = optional($product->category)->name;
        $heroImage = $primaryImage ? $imageService->url($primaryImage->image) : $imageService->url(null);
        $galleryCount = $galleryImages->count();
    @endphp

    <div class="container-luxury py-6 md:py-10">
        <nav class="mb-7 text-sm text-warm-gray" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="hover:text-gold">Home</a></li>
                <li aria-hidden="true">/</li>
                @if ($catName)
                    <li><a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-gold">{{ $catName }}</a></li>
                    <li aria-hidden="true">/</li>
                @endif
                <li class="text-charcoal" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="grid lg:grid-cols-[minmax(0,1.15fr)_minmax(360px,.85fr)] gap-8 lg:gap-14 items-start"
             x-data="{ currentIndex: 0, open: false, images: @js($imageUrls) }"
             @keydown.escape.window="open = false"
             @keydown.arrow-right.window="if (open && images.length > 1) currentIndex = (currentIndex + 1) % images.length"
             @keydown.arrow-left.window="if (open && images.length > 1) currentIndex = (currentIndex + images.length - 1) % images.length">

            <section class="min-w-0 lg:sticky lg:top-28">
                <div class="product-gallery-main relative overflow-hidden rounded-sm bg-[#f3ece2] border border-[#e9dfd1]">
                    <button type="button" class="block w-full cursor-zoom-in" @click="open = true" aria-label="Open large product image">
                        <img src="{{ $heroImage }}" x-bind:src="images[currentIndex] || '{{ $heroImage }}'"
                             alt="{{ $product->name }}" class="w-full aspect-[4/5] md:aspect-[5/4] lg:aspect-[4/5] object-contain p-3 md:p-7"
                             fetchpriority="high" decoding="async">
                    </button>
                    <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-[10px] tracking-[.18em] uppercase text-[#795d38]">Royal Shine</span>
                    @if ($galleryCount > 1)
                        <span class="absolute bottom-4 right-4 rounded-full bg-[#29231e]/75 px-3 py-1.5 text-xs text-white" x-text="(currentIndex + 1) + ' / ' + images.length"></span>
                    @endif
                </div>

                @if ($galleryCount > 1)
                    <div class="mt-4 grid grid-cols-4 sm:grid-cols-5 gap-3" aria-label="Product image thumbnails">
                        @foreach ($galleryImages as $index => $image)
                            <button type="button" @click="currentIndex = {{ $index }}"
                                class="overflow-hidden border bg-white transition"
                                :class="currentIndex === {{ $index }} ? 'border-[#a77b43] ring-1 ring-[#a77b43]' : 'border-[#e9dfd1] hover:border-[#c6a16c]'"
                                aria-label="Show image {{ $index + 1 }}">
                                <img src="{{ $imageUrls[$index] ?? $imageService->url($image->image) }}"
                                     alt="{{ $image->alt_text ?: $product->name }}"
                                     class="w-full aspect-square object-contain p-1.5" loading="lazy" decoding="async">
                            </button>
                        @endforeach
                    </div>
                @endif

                <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-[100] bg-[#17130f]/95 p-4 md:p-8 flex items-center justify-center"
                     role="dialog" aria-modal="true" aria-label="Product image viewer" @click.self="open = false">
                    <button type="button" @click="open = false" class="absolute right-5 top-4 text-4xl text-white hover:text-[#d8b47c]" aria-label="Close image viewer">&times;</button>
                    <button type="button" x-show="images.length > 1" @click="currentIndex = (currentIndex + images.length - 1) % images.length" class="absolute left-3 md:left-8 top-1/2 -translate-y-1/2 text-5xl text-white hover:text-[#d8b47c]" aria-label="Previous image">&lsaquo;</button>
                    <img x-bind:src="images[currentIndex] || '{{ $heroImage }}'" alt="{{ $product->name }}" class="max-h-[86vh] max-w-[88vw] object-contain">
                    <button type="button" x-show="images.length > 1" @click="currentIndex = (currentIndex + 1) % images.length" class="absolute right-3 md:right-8 top-1/2 -translate-y-1/2 text-5xl text-white hover:text-[#d8b47c]" aria-label="Next image">&rsaquo;</button>
                    <span class="absolute bottom-5 left-1/2 -translate-x-1/2 rounded-full bg-white/10 px-4 py-2 text-sm text-white"><span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span></span>
                </div>
            </section>

            <section class="min-w-0 pt-1">
                @if ($catName)
                    <a href="{{ route('category.show', $product->category->slug) }}" class="text-[11px] font-semibold uppercase tracking-[.22em] text-[#a77b43] hover:underline">{{ $catName }}</a>
                @endif
                <h1 class="mt-3 font-serif text-4xl md:text-5xl lg:text-[3.4rem] leading-[1.05] text-[#29231e]">{{ $product->name }}</h1>
                @if ($product->sku)
                    <p class="mt-3 text-xs uppercase tracking-[.16em] text-[#8d8173]">Reference: {{ $product->sku }}</p>
                @endif

                <div class="mt-7 border-y border-[#e9dfd1] py-5">
                    @if ($product->price_on_request || ! $product->price)
                        <p class="font-serif text-2xl text-[#9a713e]">Price on Request</p>
                    @else
                        <p class="font-serif text-3xl text-[#29231e]">₹{{ number_format($product->price, 0) }}</p>
                        <p class="mt-1 text-xs text-[#8d8173]">Inclusive of all taxes</p>
                    @endif
                </div>

                @if ($product->short_description || $product->description)
                    <div class="py-6 border-b border-[#e9dfd1]">
                        <h2 class="font-serif text-2xl text-[#29231e] mb-3">A closer look</h2>
                        <p class="text-sm leading-7 text-[#71685e]">{{ $product->short_description ?: $product->description }}</p>
                    </div>
                @endif

                @if ($product->metal_type || $product->gold_purity || $product->weight || $product->stone_details)
                    <div class="py-6 border-b border-[#e9dfd1]">
                        <h2 class="font-serif text-2xl text-[#29231e] mb-4">Product details</h2>
                        <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            @if ($product->metal_type)<div><dt class="text-xs uppercase tracking-wider text-[#9a8d7e]">Metal</dt><dd class="mt-1 text-[#29231e]">{{ ucfirst($product->metal_type) }}</dd></div>@endif
                            @if ($product->gold_purity)<div><dt class="text-xs uppercase tracking-wider text-[#9a8d7e]">Purity</dt><dd class="mt-1 text-[#29231e]">{{ $product->gold_purity->value }} Gold</dd></div>@endif
                            @if ($product->weight)<div><dt class="text-xs uppercase tracking-wider text-[#9a8d7e]">Weight</dt><dd class="mt-1 text-[#29231e]">{{ $product->weight }} {{ $product->weight_unit }}</dd></div>@endif
                            @if ($product->stone_details)<div class="col-span-2"><dt class="text-xs uppercase tracking-wider text-[#9a8d7e]">Stone details</dt><dd class="mt-1 text-[#29231e]">{{ $product->stone_details }}</dd></div>@endif
                        </dl>
                    </div>
                @endif
                @if ($product->description && $product->short_description)
                    <div class="py-6 border-b border-[#e9dfd1]"><h2 class="font-serif text-2xl text-[#29231e] mb-3">Description</h2><p class="text-sm leading-7 text-[#71685e]">{{ $product->description }}</p></div>
                @endif

                <div id="product-enquiry" class="mt-7 rounded-sm border border-[#e5d8c7] bg-white p-5 md:p-7 shadow-[0_12px_40px_rgba(52,39,24,.05)]">
                    @if (session('success'))
                        <div class="mb-5 border border-green-200 bg-green-50 p-3 text-sm text-green-800">{{ session('success') }}</div>
                    @endif
                    <p class="text-[10px] uppercase tracking-[.22em] text-[#a77b43] font-semibold">Personal assistance</p>
                    <h2 class="mt-2 font-serif text-3xl text-[#29231e]">Enquire about this piece</h2>
                    <p class="mt-2 text-sm leading-6 text-[#71685e]">Share your details and our team will get in touch with you about this design.</p>
                    <form action="{{ route('enquiry.store') }}" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_reference" value="{{ $product->sku }}">
                        <input type="hidden" name="subject" value="Enquiry for {{ $product->name }}">
                        <input type="hidden" name="enquiry_type" value="product">
                        <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div><label for="enquiry-name" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#66594b]">Full name *</label><input id="enquiry-name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" required maxlength="255" class="w-full border border-[#e6dccf] bg-[#fdfbf8] px-4 py-3.5 text-sm text-[#29231e] focus:border-[#a77b43] focus:outline-none">@error('name')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                            <div><label for="enquiry-mobile" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#66594b]">Mobile number *</label><input id="enquiry-mobile" type="tel" name="mobile" value="{{ old('mobile') }}" autocomplete="tel" inputmode="numeric" pattern="[6-9][0-9]{9}" minlength="10" maxlength="10" placeholder="10-digit mobile number" required class="w-full border border-[#e6dccf] bg-[#fdfbf8] px-4 py-3.5 text-sm text-[#29231e] focus:border-[#a77b43] focus:outline-none">@error('mobile')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                        </div>
                        <div><label for="enquiry-email" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#66594b]">Email (optional)</label><input id="enquiry-email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" class="w-full border border-[#e6dccf] bg-[#fdfbf8] px-4 py-3.5 text-sm text-[#29231e] focus:border-[#a77b43] focus:outline-none">@error('email')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                        <div><label for="enquiry-message" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#66594b]">What would you like to know? *</label><textarea id="enquiry-message" name="message" rows="4" required maxlength="3000" class="w-full resize-y border border-[#e6dccf] bg-[#fdfbf8] px-4 py-3.5 text-sm text-[#29231e] focus:border-[#a77b43] focus:outline-none">{{ old('message', 'I am interested in ' . $product->name . ' (Reference: ' . $product->sku . '). Please share more details.') }}</textarea>@error('message')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                        <button type="submit" class="w-full bg-[#a77b43] px-6 py-4 text-xs font-semibold uppercase tracking-[.18em] text-white transition hover:bg-[#78552f]">Send product enquiry</button>
                    </form>
                    @if ($whatsapp)
                        <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hello Royal Shine, I am interested in ' . $product->name . ' (Reference: ' . $product->sku . '). Please share more details.') }}" target="_blank" rel="noopener" class="mt-3 flex w-full items-center justify-center border border-[#d8c7b2] px-6 py-3.5 text-xs font-semibold uppercase tracking-[.14em] text-[#6f5537] transition hover:bg-[#f8f2e9]">Continue on WhatsApp</a>
                    @endif
                </div>
            </section>
        </div>

        @if ($related->count() > 0)
            <section class="mt-20 border-t border-[#e9dfd1] pt-14">
                <x-section-heading eyebrow="Complete the look" title="You may also like" subtitle="Discover more pieces selected from our collection." />
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                    @foreach ($related as $relatedProduct)
                        <x-product-card :product="$relatedProduct" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-layout>