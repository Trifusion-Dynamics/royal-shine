<header x-data="{ open: false }" class="bg-white border-b border-[#e5e5e5] sticky top-0 z-50">
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
            <nav class="hidden md:flex items-center space-x-6">
                <x-button type="button" href="{{ route('home') }}" text="Home" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] p-0 bg-transparent border-none hover:bg-transparent" />
                <x-button type="button" href="{{ route('about') }}" text="About Us" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] p-0 bg-transparent border-none hover:bg-transparent" />

                <!-- Jewellery Mega Menu (Dark Theme, Multi-Column) -->
                <div class="relative group">
                    <a href="{{ route('jewellery.index') }}" class="text-[#1a1a1a] font-medium hover:text-[#d4a373]">Jewellery</a>
                    <div class="absolute left-0 mt-2 w-[560px] bg-[#0f0f0f] border border-[#2a2a2a] shadow-2xl hidden group-hover:block py-6 z-50">
                        <div class="px-6">
                            <div class="grid grid-cols-2 gap-8">
                                <!-- Left Column -->
                                <div>
                                    <h3 class="text-xs font-semibold text-[#d4a373] uppercase tracking-wider mb-4">Categories</h3>
                                    <nav class="space-y-2">
                                        @foreach ($navCategories->chunk(6)->first() as $category)
                                            <a href="{{ route('category.show', $category->slug) }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-[#e5e5e5] hover:bg-[#1a1a1a] hover:text-[#d4a373] transition-colors rounded-md">
                                                <svg class="w-4 h-4 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path></svg>
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                    </nav>
                                </div>

                                <!-- Right Column -->
                                <div>
                                    <h3 class="text-xs font-semibold text-[#d4a373] uppercase tracking-wider mb-4">Shop by</h3>
                                    <nav class="space-y-2">
                                        @foreach ($navCategories->chunk(6)->last() as $category)
                                            <a href="{{ route('category.show', $category->slug) }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-[#e5e5e5] hover:bg-[#1a1a1a] hover:text-[#d4a373] transition-colors rounded-md">
                                                <svg class="w-4 h-4 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path></svg>
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                    </nav>
                                </div>
                            </div>

                            <!-- Bottom CTA -->
                            <div class="mt-6 pt-6 border-t border-[#2a2a2a] flex gap-3">
                                <a href="{{ route('jewellery.index') }}" class="flex-1 px-4 py-2.5 bg-[#d4a373] text-[#1a1a1a] text-sm font-semibold text-center hover:bg-[#b08548] transition-colors rounded-md">
                                    View All Jewellery
                                </a>
                                @php
                                    $whatsapp = app(\App\Services\SettingsService::class)->get('whatsapp_number');
                                @endphp
                                @if ($whatsapp)
                                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}" target="_blank" rel="noopener" class="flex-1 px-4 py-2.5 border border-[#d4a373] text-[#d4a373] text-sm font-semibold text-center hover:bg-[#d4a373] hover:text-[#1a1a1a] transition-colors rounded-md">
                                        WhatsApp Enquiry
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <x-button type="button" href="{{ route('gallery') }}" text="Gallery" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] p-0 bg-transparent border-none hover:bg-transparent" />
                <x-button type="button" href="{{ route('contact') }}" text="Contact Us" class="text-[#1a1a1a] font-medium hover:text-[#d4a373] p-0 bg-transparent border-none hover:bg-transparent" />
            </nav>

            <!-- Desktop: Search + WhatsApp + Enquire Now -->
            <div class="hidden md:flex items-center space-x-3">
                <!-- Search -->
                <form action="{{ route('search') }}" method="GET" role="search" class="flex items-center">
                    <label class="sr-only" for="site-search">Search products</label>
                    <input id="site-search" type="search" name="q" value="{{ request('q') }}" placeholder="Search jewellery"
                        class="w-36 px-3 py-2 border border-[#e5e5e5] text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373]">
                </form>

                <!-- WhatsApp -->
                @php
                    $whatsapp = app(\App\Services\SettingsService::class)->get('whatsapp_number');
                @endphp
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}" target="_blank" rel="noopener" class="flex items-center gap-2 px-4 py-2.5 bg-[#d4a373] text-white text-sm font-medium hover:bg-[#b08548] transition-colors whitespace-nowrap" title="WhatsApp">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        WhatsApp
                    </a>
                @endif

                <!-- Enquire Now CTA -->
                <a href="{{ route('enquiry.create') }}" class="btn-gold px-6 py-2.5 text-sm font-medium whitespace-nowrap">Enquire Now</a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center gap-2">
                <!-- Mobile WhatsApp Shortcut -->
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}" target="_blank" rel="noopener" class="text-[#1a1a1a] hover:text-[#d4a373] p-2" title="WhatsApp">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </a>
                @endif
                <!-- Mobile Search -->
                <a href="{{ route('search') }}" class="text-[#1a1a1a] hover:text-[#d4a373] p-2" aria-label="Search products">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </a>
                <!-- Mobile Menu Toggle -->
                <button
                    @click="open = !open"
                    :aria-expanded="open.toString()"
                    aria-label="Toggle navigation"
                    class="text-[#1a1a1a] hover:text-[#d4a373]"
                >
                    <svg x-show="!open" class="block h-6 w-6 fill-none stroke-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="open" x-cloak class="h-6 w-6 fill-none stroke-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="md:hidden bg-white border-t border-[#e5e5e5] z-40">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <form action="{{ route('search') }}" method="GET" role="search" class="px-3 py-2">
                <label class="sr-only" for="mobile-site-search">Search products</label>
                <input id="mobile-site-search" type="search" name="q" value="{{ request('q') }}" placeholder="Search jewellery"
                    class="w-full px-3 py-2 border border-[#e5e5e5] text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373]">
            </form>
            <a href="{{ route('home') }}" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">Home</a>
            <a href="{{ route('about') }}" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">About Us</a>

            <div class="border-t border-[#2a2a2a] pt-2">
                <h3 class="px-3 py-2 text-base font-medium text-[#d4a373] uppercase tracking-wider">Jewellery</h3>
                <nav class="space-y-1">
                    @foreach ($navCategories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" @click="open = false" class="flex items-center gap-3 px-3 py-2.5 text-sm text-[#e5e5e5] hover:bg-[#1a1a1a] hover:text-[#d4a373] transition-colors rounded-md">
                            <svg class="w-4 h-4 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path></svg>
                            {{ $category->name }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <a href="{{ route('gallery') }}" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">Gallery</a>
            <a href="{{ route('contact') }}" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]">Contact Us</a>

            <div class="border-t border-[#e5e5e5] pt-4 pb-2">
                <h3 class="px-3 py-2 text-base font-medium text-[#1a1a1a]">Quick Actions</h3>
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}" target="_blank" rel="noopener" @click="close()" class="block px-3 py-3 rounded-md text-base font-medium text-[#1a1a1a] bg-[#fdfaf5] border border-[#e5e5e5] hover:bg-[#d4a373] hover:text-white transition-colors flex items-center gap-3">
                        <svg class="w-5 h-5 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        WhatsApp Us
                    </a>
                @endif
                <a href="{{ route('enquiry.create') }}" @click="open = false" class="block px-3 py-3 rounded-md text-base font-medium text-[#1a1a1a] bg-[#fdfaf5] border border-[#e5e5e5] hover:bg-[#d4a373] hover:text-white transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22-2.22L6 18m-3 3v1h12V3z"></path></svg>
                    Enquire Now
                </a>
            </div>
        </div>
    </div>
</header>
