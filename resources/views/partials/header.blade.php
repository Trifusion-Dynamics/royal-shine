<header class="bg-white border-b border-[#e5e5e5] sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                @php
                    $logo = app(\App\Services\SettingsService::class)->get('logo');
                    $siteName = app(\App\Services\SettingsService::class)->get('site_name', 'Royal Shine');
                @endphp
                @if ($logo)
                    <a href="{{ route('home') }}">
                        <x-image :path="$logo" :alt="$siteName" class="h-10 w-auto" loading="eager" />
                    </a>
                @else
                    <a href="{{ route('home') }}" class="text-xl font-bold serif text-[#d4a373] tracking-wider">{{ $siteName }}</a>
                @endif
            </div>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center space-x-8">
                <x-button type="button" href="{{ route('home') }}" text="Home" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] p-0 bg-transparent border-none hover:bg-transparent" />
                <x-button type="button" href="{{ route('about') }}" text="About Us" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] p-0 bg-transparent border-none hover:bg-transparent" />

                <!-- Jewellery Dropdown -->
                <div class="relative group">
                    <x-button type="button" href="#" text="Jewellery" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] p-0 bg-transparent border-none hover:bg-transparent" />
                    <div class="absolute left-0 mt-2 w-56 bg-white border border-[#e5e5e5] shadow-xl hidden group-hover:block py-3 z-50">
                        @foreach ($navCategories as $category)
                            <a href="{{ route('category.show', $category->slug) }}" class="block px-4 py-2.5 text-sm text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373] transition-colors">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <x-button type="button" href="{{ route('gallery') }}" text="Gallery" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] p-0 bg-transparent border-none hover:bg-transparent" />
                <x-button type="button" href="{{ route('contact') }}" text="Contact Us" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] p-0 bg-transparent border-none hover:bg-transparent" />
            </nav>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button
                    x-data="mobileMenu"
                    @click="toggle()"
                    class="text-[#1a1a1a] hover:text-[#d4a373]"
                >
                    <svg x-show="!open" class="block h-6 w-6 fill-none view-box-0-0-24-24 stroke-current" xmlns="http://www.w3.org/2000/svg" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="open" class="hidden h-6 w-6 fill-none view-box-0-0-24-24 stroke-current" xmlns="http://www.w3.org/2000/svg" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-data="mobileMenu" x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="md:hidden bg-white border-t border-[#e5e5e5] z-40">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('home') }}" @click="close()" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">Home</a>
            <a href="{{ route('about') }}" @click="close()" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">About Us</a>
            
            <div class="border-t border-[#e5e5e5] pt-2">
                <h3 class="px-3 py-2 text-base font-medium text-[#1a1a1a]">Jewellery</h3>
                @foreach ($navCategories as $category)
                    <a href="{{ route('category.show', $category->slug) }}" @click="close()" class="block px-3 py-2 text-base text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">{{ $category->name }}</a>
                @endforeach
            </div>

            <a href="{{ route('gallery') }}" @click="close()" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">Gallery</a>
            <a href="{{ route('contact') }}" @click="close()" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">Contact Us</a>
        </div>
    </div>
</header>
