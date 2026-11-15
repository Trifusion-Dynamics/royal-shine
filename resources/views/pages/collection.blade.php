<x-layout :seoTitle="$seoTitle" :metaDescription="$metaDescription" :canonicalUrl="$canonicalUrl" :ogImage="$ogImage">
    @php
        $settingsService = app(\App\Services\SettingsService::class);
        $whatsapp = $settingsService->get('whatsapp_number');
    @endphp

    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex flex-wrap gap-2 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-warm-gray">
            <li><a href="{{ route('home') }}" class="hover:text-gold transition-colors">Home</a></li>
            <li aria-hidden="true" class="text-beige">/</li>
            <li><a href="{{ route('collections') }}" class="hover:text-gold transition-colors">Collections</a></li>
            <li aria-hidden="true" class="text-beige">/</li>
            <li><span class="text-charcoal font-medium">{{ $collection->name }}</span></li>
        </ol>
    </nav>

    <div class="container-luxury">
        <header class="grid lg:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <p class="mb-3 text-xs font-semibold text-gold uppercase tracking-wider">Royal Shine Collection</p>
                <h1 class="text-4xl md:text-5xl font-serif text-charcoal mb-5 leading-tight">{{ $collection->name }}</h1>
                @if ($collection->description)
                    <p class="max-w-2xl leading-7 text-warm-gray">{{ $collection->description }}</p>
                @endif
                <a href="{{ route('enquiry.create') }}" class="btn btn-gold mt-6 inline-block">Ask About This Collection</a>
            </div>
            <div class="relative aspect-[4/3] overflow-hidden rounded-lg border border-beige bg-champagne shadow-sm">
                <x-image :path="$collection->image" :alt="$collection->name"
                         class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700"
                         loading="eager" />
            </div>
        </header>

        <section class="mb-16">
            <div class="mb-8 flex items-end justify-between border-b border-beige pb-4">
                <h2 class="text-2xl font-serif text-charcoal">Pieces in This Collection</h2>
                <span class="text-sm text-warm-gray">{{ $products->total() }} pieces</span>
            </div>

            @if ($products->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                <div class="mt-12 flex justify-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="border border-beige bg-white p-16 text-center rounded-lg">
                    <h3 class="font-serif text-2xl text-charcoal mb-2">New Pieces Are on the Way</h3>
                    <p class="text-warm-gray">Please enquire with our team for more information about this collection.</p>
                </div>
            @endif
        </section>
    </div>
</x-layout>
