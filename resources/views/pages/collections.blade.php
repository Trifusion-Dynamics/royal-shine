<x-layout>
    <section class="bg-[#fbf8f2] py-14 md:py-20">
        <div class="container-luxury">
            <header class="mx-auto mb-12 max-w-2xl text-center">
                <p class="mb-3 text-[10px] font-semibold uppercase tracking-[.24em] text-[#a77b43]">Curated for you</p>
                <h1 class="font-serif text-4xl leading-tight text-[#29231e] md:text-6xl">Our Collections</h1>
                <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-[#71685e] md:text-base">Explore considered edits of timeless jewellery, each with its own character and story.</p>
                <div class="mx-auto mt-6 h-px w-16 bg-[#b68a4a]"></div>
            </header>
            @if ($collections->count() > 0)
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($collections as $collection)
                        <a href="{{ route('collection.show', $collection->slug) }}" class="group overflow-hidden border border-[#e8ddcf] bg-white transition hover:-translate-y-1 hover:shadow-[0_18px_45px_rgba(45,32,20,.09)]">
                            <div class="relative overflow-hidden bg-[#f3ece2]">
                                @if ($collection->image)
                                    <x-image :path="$collection->image" :alt="$collection->name" class="block aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-[1.04]" loading="lazy" />
                                @else
                                    <div class="flex aspect-[4/3] items-center justify-center"><span class="font-serif text-4xl text-[#a58c6a]">{{ str($collection->name)->substr(0, 2)->upper() }}</span></div>
                                @endif
                            </div>
                            <div class="p-5 md:p-6">
                                <h2 class="font-serif text-2xl text-[#29231e] transition-colors group-hover:text-[#9a713e]">{{ $collection->name }}</h2>
                                @if ($collection->description)<p class="mt-2 line-clamp-2 text-sm leading-6 text-[#71685e]">{{ $collection->description }}</p>@endif
                                <span class="mt-5 inline-flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[.17em] text-[#9a713e]">Explore collection <span aria-hidden="true">↗</span></span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="border border-[#e8ddcf] bg-white py-16 text-center"><h2 class="font-serif text-3xl text-[#29231e]">Collections coming soon</h2><p class="mt-2 text-sm text-[#71685e]">New edits will appear here as they are added by our team.</p></div>
            @endif
            @if ($featuredProducts->count() > 0)
                <section class="mt-20 border-t border-[#e8ddcf] pt-14">
                    <x-section-heading eyebrow="Selected pieces" title="Featured Jewellery" subtitle="A few favourites from across the collection." />
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4 md:gap-6">
                        @foreach ($featuredProducts as $product)<x-product-card :product="$product" />@endforeach
                    </div>
                </section>
            @endif
        </div>
    </section>
</x-layout>