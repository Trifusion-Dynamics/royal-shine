@php
    $settingsService = app(\App\Services\SettingsService::class);
    $logo = $settingsService->get('logo');
    $siteName = $settingsService->get('site_name', 'Royal Shine');
    $whatsapp = $settingsService->get('whatsapp_number');
    $announcementEnabled = $settingsService->get('announcement_enabled', false);
    $announcementText = $settingsService->get('announcement_text');
    $announcementLink = $settingsService->get('announcement_link');

    // The shared header must remain renderable before any categories are configured.
    $navCategories = collect($navCategories ?? []);
    $navCategoryGroups = $navCategories->chunk(6);
    $primaryNavCategories = $navCategoryGroups->get(0, collect());
    $secondaryNavCategories = $navCategoryGroups->get(1, collect());
@endphp

<!-- Announcement Bar -->
@if ($announcementEnabled && $announcementText)
    <div class="bg-champagne text-charcoal border-b border-beige px-4 py-2">
        <div class="container-luxury flex items-center gap-3 text-sm font-medium">
            @if ($announcementLink)
                <a href="{{ $announcementLink }}" class="hover:text-gold transition-colors">{{ $announcementText }}</a>
            @else
                {{ $announcementText }}
            @endif
        </div>
    </div>
@endif

<header x-data="{ scrolled: false }"
        @window.scroll="scrolled = window.scrollY > 20"
        class="sticky top-0 z-[60] bg-white/80 backdrop-blur-md border-b border-beige transition-all duration-300"
        :class="scrolled ? 'shadow-sm' : ''">
    <div class="container-luxury">
        <div class="flex justify-between items-center h-18 md:h-20 px-4 sm:px-6 lg:px-8">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center lg:mr-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    @if ($logo)
                        <x-image :path="$logo" :alt="$siteName" class="h-8 w-auto md:h-10" loading="eager" />
                    @else
                        <span class="font-serif text-[1.5rem] text-gold tracking-tight">{{ $siteName }}</span>
                    @endif
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-1">
                <a href="{{ route('home') }}"
                   class="text-sm font-medium text-charcoal hover:text-gold transition-colors px-3 py-2">
                    Home
                </a>
                <a href="{{ route('about') }}"
                   class="text-sm font-medium text-charcoal hover:text-gold transition-colors px-3 py-2">
                    About Us
                </a>

                <!-- Jewellery Mega Menu -->
                <div class="relative group">
                    <a href="{{ route('jewellery.index') }}"
                       class="text-sm font-medium text-charcoal hover:text-gold transition-colors px-3 py-2">
                        Jewellery
                    </a>
                    <div class="absolute top-full left-0 mt-2 w-[640px] rounded-lg border border-beige bg-white shadow-luxury-lg overflow-hidden hidden group-hover:block z-50">
                        <div class="grid grid-cols-2 gap-0">
                            <!-- Left Column -->
                            <div class="border-r border-beige p-6">
                                <h4 class="text-xs font-semibold text-gold uppercase tracking-wider mb-5">Categories</h4>
                                <nav class="space-y-3">
                                    @foreach ($primaryNavCategories as $category)
                                        <a href="{{ route('category.show', $category->slug) }}"
                                           class="flex items-center gap-3 px-3 py-2.5 text-sm text-warm-gray hover:bg-champagne hover:text-gold transition-colors rounded-md">
                                            @if ($category->image)
                                                <div class="w-6 h-6 shrink-0 overflow-hidden rounded-sm border border-beige flex-shrink-0">
                                                    <x-image :path="$category->image" :alt="$category->name"
                                                             class="w-full h-full object-cover" loading="lazy" />
                                                </div>
                                            @else
                                                <div class="w-6 h-6 shrink-0 bg-beige rounded-sm flex items-center justify-center">
                                                    <span class="text-gold text-[10px] font-bold uppercase">
                                                        {{ str($category->name)->substr(0, 1)->upper() }}
                                                    </span>
                                                </div>
                                            @endif
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </nav>
                            </div>

                            <!-- Right Column -->
                            <div class="p-6">
                                <h4 class="text-xs font-semibold text-gold uppercase tracking-wider mb-5">Shop by</h4>
                                <nav class="space-y-3">
                                    @foreach ($secondaryNavCategories as $category)
                                        <a href="{{ route('category.show', $category->slug) }}"
                                           class="flex items-center gap-3 px-3 py-2.5 text-sm text-warm-gray hover:bg-champagne hover:text-gold transition-colors rounded-md">
                                            @if ($category->image)
                                                <div class="w-6 h-6 shrink-0 overflow-hidden rounded-sm border border-beige flex-shrink-0">
                                                    <x-image :path="$category->image" :alt="$category->name"
                                                             class="w-full h-full object-cover" loading="lazy" />
                                                </div>
                                            @else
                                                <div class="w-6 h-6 shrink-0 bg-beige rounded-sm flex items-center justify-center">
                                                    <span class="text-gold text-[10px] font-bold uppercase">
                                                        {{ str($category->name)->substr(0, 1)->upper() }}
                                                    </span>
                                                </div>
                                            @endif
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </nav>
                                <div class="mt-6 pt-6 border-t border-beige flex gap-3">
                                    <a href="{{ route('jewellery.index') }}"
                                       class="flex-1 px-4 py-3 bg-brown text-white text-sm font-medium text-center hover:bg-gold transition-colors rounded-md">
                                        View All Jewellery
                                    </a>
                                    @if ($whatsapp)
                                        <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}"
                                           target="_blank" rel="noopener"
                                           class="flex-1 px-4 py-3 border border-beige text-brown text-sm font-medium text-center hover:bg-gold hover:text-white transition-colors rounded-md">
                                            WhatsApp Enquiry
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('collections') }}"
                   class="text-sm font-medium text-charcoal hover:text-gold transition-colors px-3 py-2">
                    Collections
                </a>
                <a href="{{ route('gallery') }}"
                   class="text-sm font-medium text-charcoal hover:text-gold transition-colors px-3 py-2">
                    Gallery
                </a>
                <a href="{{ route('contact') }}"
                   class="text-sm font-medium text-charcoal hover:text-gold transition-colors px-3 py-2">
                    Contact Us
                </a>
            </nav>

            <!-- Desktop Actions -->
            <div class="hidden lg:flex items-center gap-4">
                <!-- Search -->
                <form action="{{ route('search') }}" method="GET" role="search"
                      class="flex items-center gap-2 border border-beige rounded-md bg-white px-3 py-1.5 focus-within:border-gold transition-colors">
                    <label class="sr-only" for="site-search">Search jewellery</label>
                    <input id="site-search" type="search" name="q" value="{{ request('q') }}"
                           placeholder="Search jewellery"
                           class="w-40 text-sm outline-none placeholder-warm-gray/60">
                    <button type="submit" class="text-warm-gray hover:text-gold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>

                <!-- WhatsApp -->
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}"
                       target="_blank" rel="noopener"
                       class="flex items-center gap-2 text-sm font-medium text-brown hover:text-gold transition-colors whitespace-nowrap"
                       title="WhatsApp">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        WhatsApp
                    </a>
                @endif

                <a href="{{ route('enquiry.create') }}"
                   class="btn btn-gold btn-sm text-brown whitespace-nowrap">
                    Enquire Now
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="lg:hidden flex items-center gap-2">
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}"
                       target="_blank" rel="noopener"
                       class="text-charcoal hover:text-gold p-2" title="WhatsApp">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </a>
                @endif
                <a href="{{ route('search') }}"
                   class="text-charcoal hover:text-gold p-2" aria-label="Search products">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </a>
                <button @click="open = !open"
                        :aria-expanded="open.toString()"
                        aria-label="Toggle navigation"
                        class="text-charcoal hover:text-gold">
                    <svg x-show="!open" class="block h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-data="{ open: false }"
         x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="fixed inset-0 z-[70] bg-white/95 backdrop-blur-sm lg:hidden">
        <div class="flex flex-col h-full">
            <div class="container-luxury flex justify-between items-center h-20 border-b border-beige">
                <a href="{{ route('home') }}" @click="open = false"
                   class="flex items-center gap-2">
                    @if ($logo)
                        <x-image :path="$logo" :alt="$siteName" class="h-8 w-auto" loading="eager" />
                    @else
                        <span class="font-serif text-[1.5rem] text-gold tracking-tight">{{ $siteName }}</span>
                    @endif
                </a>
                <button @click="open = false" class="text-charcoal hover:text-gold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-8 space-y-6">
                <form action="{{ route('search') }}" method="GET" role="search"
                      class="flex items-center gap-2 border border-beige rounded-md bg-ivory px-3 py-2">
                    <label class="sr-only" for="mobile-site-search">Search jewellery</label>
                    <input id="mobile-site-search" type="search" name="q" value="{{ request('q') }}"
                           placeholder="Search jewellery"
                           class="flex-1 text-sm outline-none placeholder-warm-gray/60">
                    <button type="submit" class="text-warm-gray hover:text-gold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>

                <nav class="space-y-2">
                    <a href="{{ route('home') }}" @click="open = false"
                       class="block px-3 py-3 text-lg font-medium text-charcoal hover:bg-champagne hover:text-gold rounded-md transition-colors">
                        Home
                    </a>
                    <a href="{{ route('about') }}" @click="open = false"
                       class="block px-3 py-3 text-lg font-medium text-charcoal hover:bg-champagne hover:text-gold rounded-md transition-colors">
                        About Us
                    </a>
                    <div class="border-t border-beige pt-4">
                        <h4 class="px-3 py-3 text-xs font-semibold text-gold uppercase tracking-wider">Jewellery</h4>
                        <nav class="space-y-1">
                            @foreach ($navCategories as $category)
                                <a href="{{ route('category.show', $category->slug) }}" @click="open = false"
                                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-warm-gray hover:bg-champagne hover:text-gold transition-colors rounded-md">
                                    <svg class="w-4 h-4 shrink-0 text-gold" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                                    </svg>
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </nav>
                    </div>
                    <a href="{{ route('collections') }}" @click="open = false"
                       class="block px-3 py-3 text-lg font-medium text-charcoal hover:bg-champagne hover:text-gold rounded-md transition-colors">
                        Collections
                    </a>
                    <a href="{{ route('gallery') }}" @click="open = false"
                       class="block px-3 py-3 text-lg font-medium text-charcoal hover:bg-champagne hover:text-gold rounded-md transition-colors">
                        Gallery
                    </a>
                    <a href="{{ route('contact') }}" @click="open = false"
                       class="block px-3 py-3 text-lg font-medium text-charcoal hover:bg-champagne hover:text-gold rounded-md transition-colors">
                        Contact Us
                    </a>
                </nav>

                <div class="border-t border-beige pt-6">
                    <h4 class="px-3 py-3 text-xs font-semibold text-charcoal uppercase tracking-wider">Quick Actions</h4>
                    <div class="space-y-2">
                        @if ($whatsapp)
                            <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}"
                               target="_blank" rel="noopener" @click="open = false"
                               class="block px-4 py-4 rounded-md text-base font-medium text-brown bg-champagne border border-beige hover:bg-gold hover:text-white transition-colors flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                WhatsApp Us
                            </a>
                        @endif
                        <a href="{{ route('enquiry.create') }}" @click="open = false"
                           class="block px-4 py-4 rounded-md text-base font-medium text-charcoal bg-ivory border border-beige hover:bg-gold hover:text-white transition-colors flex items-center gap-3">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22-2.22L6 18m-3 3v1h12V3z"></path>
                            </svg>
                            Enquire Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
