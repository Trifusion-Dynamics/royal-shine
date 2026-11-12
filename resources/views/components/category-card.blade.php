@props(['category', 'link' => true])

<div class="category-card group">
    @if ($category->image)
        <div class="aspect-square relative mb-4 overflow-hidden mx-auto w-32 h-32">
            <x-image
                :path="$category->image"
                :alt="$category->name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
        </div>
    @else
        <div class="aspect-square bg-[#f3f3f3] mb-4 mx-auto w-32 h-32 flex items-center justify-center rounded-none">
            <span class="text-[#999] text-sm">No Image</span>
        </div>
    @endif

    <h3 class="text-lg font-medium text-[#1a1a1a] serif group-hover:text-[#d4a373] transition-colors mb-2">
        @if ($link)
            <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
        @else
            {{ $category->name }}
        @endif
    </h3>

    @if ($category->description)
        <p class="text-sm text-[#666] line-clamp-2">{{ $category->description }}</p>
    @endif
</div>
