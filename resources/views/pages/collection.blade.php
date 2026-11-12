<x-layout :seoTitle="$seoTitle" :metaDescription="$metaDescription" :canonicalUrl="$canonicalUrl" :ogImage="$ogImage">
    <nav class="mx-auto max-w-7xl px-4 pt-8 text-sm text-[#666] sm:px-6 lg:px-8" aria-label="Breadcrumb">
        <ol class="flex flex-wrap gap-2">
            <li><a href="{{ route('home') }}" class="hover:text-[#b08548]">Home</a></li>
            <li aria-hidden="true">/</li>
            <li><a href="{{ route('collections') }}" class="hover:text-[#b08548]">Collections</a></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page" class="text-[#1a1a1a]">{{ $collection->name }}</li>
        </ol>
    </nav>

    <header class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 md:grid-cols-2 md:items-center lg:px-8">
        <div>
            <p class="mb-3 text-xs font-semibold uppercase tracking-[0.25em] text-[#b08548]">Royal Shine Collection</p>
            <h1 class="serif mb-4 text-4xl text-[#1a1a1a] md:text-5xl">{{ $collection->name }}</h1>
            @if ($collection->description)
                <p class="max-w-2xl leading-7 text-[#666]">{{ $collection->description }}</p>
            @endif
            <a href="{{ route('enquiry.create') }}" class="btn-gold mt-6 inline-block">Ask about this collection</a>
        </div>
        <div class="aspect-[4/3] overflow-hidden bg-[#f3eee7]">
            <x-image :path="$collection->image" :alt="$collection->name" class="h-full w-full object-cover" loading="eager" />
        </div>
    </header>

    <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between border-b border-[#e5e5e5] pb-4">
            <h2 class="serif text-2xl text-[#1a1a1a]">Pieces in this collection</h2>
            <span class="text-sm text-[#666]">{{ $products->total() }} pieces</span>
        </div>
        @if ($products->isNotEmpty())
            <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4 lg:gap-8">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="mt-10">{{ $products->links() }}</div>
        @else
            <div class="border border-[#e5e5e5] bg-[#fdfaf5] px-6 py-16 text-center">
                <h3 class="serif text-2xl text-[#1a1a1a]">New pieces are on the way</h3>
                <p class="mt-2 text-[#666]">Please enquire with our team for more information about this collection.</p>
            </div>
        @endif
    </section>
</x-layout>
