<x-layout>
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-serif text-[#1a1a1a] mb-6">Our Gallery</h1>
                <div class="w-20 h-1 bg-[#d4a373] mx-auto"></div>
                <p class="text-lg text-[#666] mt-4">Explore our jewellery collection through captured moments</p>
            </div>

            @php
                $galleryItems = App\Models\Gallery::where('status', 'active')->orderBy('sort_order')->get();
            @endphp

            @if ($galleryItems->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($galleryItems as $item)
                        <div class="aspect-[4/5] bg-[#f3f3f3] overflow-hidden group">
                            <x-image
                                :path="$item->image"
                                :alt="$item->title ?? 'Gallery Image'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                            />
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-sm font-medium uppercase tracking-wider">{{ $item->title ?? 'Jewellery' }}</span>
                            </div>
                        </div>
                    @endforeach
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
