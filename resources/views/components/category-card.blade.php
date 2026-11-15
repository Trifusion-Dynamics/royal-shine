@props(['category', 'link' => true])

<a href="{{ route('category.show', $category->slug) }}"
   class="block group">
    <div class="image-wrap aspect-[1] mb-3">
        @if ($category->image)
            <x-image
                :path="$category->image"
                :alt="$category->name"
                class="w-full h-full object-cover group-hover:scale-105"
                loading="lazy"
            />
        @else
            <div class="w-full h-full lazy-placeholder flex items-center justify-center">
                <span class="text-warm-gray text-xs font-medium uppercase tracking-wider">
                    {{ str($category->name)->substr(0, 2)->upper() }}
                </span>
            </div>
        @endif
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-opacity duration-500"></div>
    </div>

    <h3 class="font-serif text-lg text-charcoal leading-tight group-hover:text-gold transition-colors">
        {{ $category->name }}
    </h3>

    @if ($category->description)
        <p class="text-sm text-warm-gray mt-1.5 line-clamp-2 leading-relaxed">
            {{ $category->description }}
        </p>
    @endif
</a>
