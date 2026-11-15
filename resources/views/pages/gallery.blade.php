<x-layout
    :seoTitle="$page->seo_title ?? 'Our Gallery'"
    :metaDescription="$page->seo_description ?? 'Explore our jewellery collection through captured moments.'"
>
    @php
        $settingsService = app(\App\Services\SettingsService::class);
    @endphp

    <section class="py-16">
        <div class="container-luxury">
            <div class="text-center mb-14">
                <h1 class="text-5xl md:text-6xl font-serif text-charcoal mb-4 leading-tight">{{ $page->title ?? 'Our Gallery' }}</h1>
                <div class="w-20 h-[1px] bg-gold mx-auto mb-6"></div>
                <p class="text-lg text-warm-gray max-w-2xl mx-auto leading-relaxed">{{ $page->seo_description ?? 'Explore our jewellery collection through captured moments' }}</p>
            </div>

            @if ($items->count() > 0)
                <div x-data="{ open: false, images: @js($imageUrls), currentIndex: 0 }"
                     @keydown.escape.window="open = false"
                     @keydown.arrow-right.window="if (open) currentIndex = (currentIndex + 1) % images.length"
                     @keydown.arrow-left.window="if (open) currentIndex = (currentIndex + images.length - 1) % images.length">
                    <div class="masonry-grid grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                        @foreach ($items as $item)
                            <button type="button" @click="currentIndex = {{ $loop->index }}; open = true"
                                class="group relative aspect-[4/5] overflow-hidden rounded-lg border border-beige bg-white">
                                <x-image
                                    :path="$item->image"
                                    :alt="$item->alt_text ?: ($item->title ?? 'Jewellery gallery image')"
                                    class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700"
                                    loading="lazy"
                                />
                                <div class="absolute inset-x-0 bottom-0 p-5 pt-14 bg-gradient-to-t from-[#25221f]/85 to-transparent">
                                    <span class="text-sm font-medium text-white">{{ $item->title ?? 'Royal Shine' }}</span>
                                    @if ($item->description)
                                        <p class="text-xs text-white/70 mt-1 line-clamp-1">{{ $item->description }}</p>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <!-- Lightbox -->
                    <div x-cloak x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-[#25221f]/95 p-4 lg:p-6"
                         role="dialog" aria-modal="true" aria-label="Gallery image" @click.self="open = false">
                        <button type="button" @click="open = false" class="absolute right-4 top-4 z-10 text-3xl text-white" aria-label="Close gallery">&times;</button>
                        <button type="button" @click="currentIndex = (currentIndex + images.length - 1) % images.length" class="absolute left-4 top-1/2 z-10 -translate-y-1/2 text-4xl text-white hover:text-gold" x-show="images.length > 1" aria-label="Previous image">&lsaquo;</button>
                        <button type="button" @click="currentIndex = (currentIndex + 1) % images.length" class="absolute right-4 top-1/2 z-10 -translate-y-1/2 text-4xl text-white hover:text-gold" x-show="images.length > 1" aria-label="Next image">&rsaquo;</button>
                        <div class="relative flex max-h-[90vh] max-w-4xl items-center justify-center">
                            <template x-for="(image, index) in images" :key="index">
                                <img x-show="open && currentIndex === index" x-bind:src="image"
                                     class="max-h-[85vh] max-w-full object-contain shadow-2xl" alt="Royal Shine jewellery gallery" loading="eager">
                            </template>
                        </div>
                        <div class="absolute bottom-4 left-1/2 z-10 -translate-x-1/2 rounded-full bg-[#25221f]/70 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm">
                            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-gold text-6xl mb-6">📸</div>
                    <h2 class="text-3xl font-serif text-charcoal mb-4">No Gallery Images</h2>
                    <p class="text-warm-gray max-w-md mx-auto leading-relaxed">Gallery images will appear here once added from the admin panel.</p>
                </div>
            @endif
        </div>
    </section>
</x-layout>
