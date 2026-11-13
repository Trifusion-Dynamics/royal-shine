<x-layout
    :seoTitle="$page->seo_title ?? 'Gallery'"
    :metaDescription="$page->seo_description ?? 'Explore our jewellery collection through captured moments.'"
>
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-serif text-[#1a1a1a] mb-6">{{ $page->title ?? 'Our Gallery' }}</h1>
                <div class="w-20 h-1 bg-[#d4a373] mx-auto"></div>
                <p class="text-lg text-[#666] mt-4">Explore our jewellery collection through captured moments</p>
            </div>

            @if ($items->count() > 0)
                <div x-data="{ open: false, images: @js($imageUrls), currentIndex: 0 }"
                    @keydown.escape.window="open = false"
                    @keydown.arrow-right.window="if (open) currentIndex = (currentIndex + 1) % images.length"
                    @keydown.arrow-left.window="if (open) currentIndex = (currentIndex + images.length - 1) % images.length">
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 lg:gap-6">
                        @foreach ($items as $item)
                            <button type="button" @click="currentIndex = {{ $loop->index }}; open = true"
                                class="group relative aspect-[4/5] overflow-hidden bg-[#f3f3f3] text-left">
                                <x-image
                                    :path="$item->image"
                                    :alt="$item->alt_text ?: ($item->title ?? 'Jewellery gallery image')"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    loading="lazy"
                                />
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-4 pt-12">
                                    <span class="text-sm font-medium text-white">{{ $item->title ?? 'Royal Shine' }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                    <div x-cloak x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
                        role="dialog" aria-modal="true" aria-label="Gallery image" @click.self="open = false">
                        <button type="button" @click="open = false" class="absolute right-4 top-4 text-3xl text-white" aria-label="Close gallery">&times;</button>
                        <button type="button" @click="currentIndex = (currentIndex + images.length - 1) % images.length" class="absolute left-4 top-1/2 -translate-y-1/2 text-4xl text-white" x-show="images.length > 1" aria-label="Previous image">&lsaquo;</button>
                        <button type="button" @click="currentIndex = (currentIndex + 1) % images.length" class="absolute right-4 top-1/2 -translate-y-1/2 text-4xl text-white" x-show="images.length > 1" aria-label="Next image">&rsaquo;</button>
                        <img x-bind:src="images[currentIndex]" alt="Royal Shine jewellery gallery" class="max-h-[85vh] max-w-full object-contain">
                    </div>
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-[#d4a373] text-6xl mb-6">📸</div>
                    <h2 class="text-2xl font-serif text-[#1a1a1a] mb-4">No Gallery Images</h2>
                    <p class="text-[#666]">Gallery images will appear here once added from the admin panel.</p>
                </div>
            @endif
        </div>
    </section>
</x-layout>
