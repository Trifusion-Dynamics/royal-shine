@php
    $settingsService = app(\App\Services\SettingsService::class);
    $logo = $settingsService->get('logo');
    $siteName = $settingsService->get('site_name', 'Royal Shine');
    $whatsapp = $settingsService->get('whatsapp_number');
@endphp

<header x-data="mobileMenu" class="bg-white border-b border-[#e5e5e5] sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                @if ($logo)
                    <a href="{{ route('home') }}">
                        <x-image :path="$logo" :alt="$siteName" class="h-10 w-auto" loading="eager" />
                    </a>
                @else
                    <a href="{{ route('home') }}" class="text-xl font-bold serif text-[#d4a373] tracking-wider">{{ $siteName }}</a>
                @endif
            </div>

            <!-- Desktop Nav -->
            <nav class="hidden lg:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] transition-colors">Home</a>
                <a href="{{ route('about') }}" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] transition-colors">About Us</a>

                <!-- Jewellery Mega Menu -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <a href="{{ route('jewellery.index') }}" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] transition-colors flex items-center gap-1">
                        Jewellery
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                    <div
                        x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute left-1/2 -translate-x-1/2 mt-3 w-[640px] bg-white border border-[#e5e5e5] shadow-xl grid grid-cols-3 gap-1 p-4 z-50"
                    >
                        @foreach ($navCategories as $category)
                            <a href="{{ route('category.show', $category->slug) }}" class="block px-4 py-2.5 text-sm text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373] transition-colors">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('gallery') }}" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] transition-colors">Gallery</a>
                <a href="{{ route('contact') }}" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] transition-colors">Contact Us</a>
            </nav>

            <!-- Desktop Actions -->
            <div class="hidden lg:flex items-center gap-4">
                <a href="{{ route('search') }}" aria-label="Search" class="text-[#1a1a1a] hover:text-[#d4a373] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </a>
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="text-[#25D366] hover:opacity-80 transition-opacity">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                    </a>
                @endif
                <a href="{{ route('contact') }}" class="btn-gold px-5 py-2.5 text-sm">Enquire Now</a>
            </div>

            <!-- Mobile Buttons -->
            <div class="lg:hidden flex items-center gap-4">
                <a href="{{ route('search') }}" aria-label="Search" class="text-[#1a1a1a] hover:text-[#d4a373]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </a>
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="text-[#25D366]">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                    </a>
                @endif
                <button
                    @click="toggle()"
                    aria-label="Menu"
                    class="text-[#1a1a1a] hover:text-[#d4a373]"
                >
                    <svg x-show="!open" class="block h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="lg:hidden bg-white border-t border-[#e5e5e5] z-40 max-h-[80vh] overflow-y-auto">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('home') }}" @click="close()" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">Home</a>
            <a href="{{ route('about') }}" @click="close()" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">About Us</a>
            <a href="{{ route('jewellery.index') }}" @click="close()" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">Jewellery</a>

            <div class="border-t border-[#e5e5e5] pt-2">
                <h3 class="px-3 py-2 text-sm font-medium text-[#999] uppercase tracking-wider">Categories</h3>
                @foreach ($navCategories as $category)
                    <a href="{{ route('category.show', $category->slug) }}" @click="close()" class="block px-3 py-2 text-base text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">{{ $category->name }}</a>
                @endforeach
            </div>

            <a href="{{ route('gallery') }}" @click="close()" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">Gallery</a>
            <a href="{{ route('contact') }}" @click="close()" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">Contact Us</a>
            <a href="{{ route('search') }}" @click="close()" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">Search</a>
        </div>
    </div>
</header>
