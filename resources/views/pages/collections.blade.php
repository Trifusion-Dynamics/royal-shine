<x-layout>
    <section class="py-16">
        <div class="container-luxury text-center">
            <h1 class="text-5xl md:text-6xl font-serif text-charcoal mb-4 leading-tight">Our Collections</h1>
            <div class="w-20 h-[1px] bg-gold mx-auto mb-6"></div>
            <p class="text-lg text-warm-gray max-w-2xl mx-auto leading-relaxed">Curated collections for every occasion</p>
        </div>

        @if ($collections->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-10 mt-14">
                @foreach ($collections as $collection)
                    <a href="{{ route('collection.show', $collection->slug) }}"
                       class="group block border border-beige bg-white rounded-lg overflow-hidden card">
                        @if ($collection->image)
                            <div class="image-wrap aspect-[4/5]">
                                <x-image :path="$collection->image" :alt="$collection->name"
                                         class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700"
                                         loading="lazy" />
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="font-serif text-lg text-charcoal mb-2 leading-tight group-hover:text-gold transition-colors">{{ $collection->name }}</h3>
                            @if ($collection->description)
                                <p class="text-sm text-warm-gray line-clamp-2 mb-4 leading-relaxed">{{ $collection->description }}</p>
                            @endif
                            <span class="btn btn-text text-gold hover:text-brown text-sm">
                                Explore Collection
                                <svg class="w-3.5 h-3.5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-20">
                <div class="text-gold text-6xl mb-6">📦</div>
                <h2 class="text-3xl font-serif text-charcoal mb-4">No Collections Yet</h2>
                <p class="text-warm-gray max-w-md mx-auto leading-relaxed">Collections will appear here once added from the admin panel.</p>
            </div>
        @endif

        @if ($featuredProducts->count() > 0)
            <section class="mt-20">
                <x-section-heading title="Featured Products"
                                   subtitle="From our collections" />
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($featuredProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </section>
        @endif
    </section>
</x-layout>
