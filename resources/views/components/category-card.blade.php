@props(['category', 'link' => true])
<a href="{{ route('category.show', $category->slug) }}" class="category-tile group block text-center">
    <div class="relative mb-4 overflow-hidden border border-[#e8ddcf] bg-[#f3ece2]">
        @if ($category->image)
            <x-image :path="$category->image" :alt="$category->name" class="block aspect-square w-full object-cover transition-transform duration-700 group-hover:scale-[1.045]" loading="lazy" />
        @else
            <div class="flex aspect-square w-full items-center justify-center"><span class="font-serif text-2xl text-[#a58c6a]">{{ str($category->name)->substr(0, 2)->upper() }}</span></div>
        @endif
        <span class="absolute inset-0 border-[5px] border-white/0 transition group-hover:border-white/40" aria-hidden="true"></span>
    </div>
    <h3 class="font-serif text-lg leading-tight text-[#29231e] transition-colors group-hover:text-[#9a713e]">{{ $category->name }}</h3>
    @if ($category->description)<p class="mt-1.5 text-xs leading-5 text-[#82776b] line-clamp-2">{{ $category->description }}</p>@endif
</a>