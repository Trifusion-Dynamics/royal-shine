@php
    $settingsService = app(\App\Services\SettingsService::class);
    $logo = $settingsService->get('logo');
    $siteName = $settingsService->get('site_name', 'Royal Shine');
    $phone = $settingsService->get('phone');
    $whatsapp = $settingsService->get('whatsapp_number');
    $email = $settingsService->get('email');
    $address = $settingsService->get('address');
    $businessHours = $settingsService->get('business_hours');
    $mapLink = $settingsService->get('map_link');
    $fb = $settingsService->get('social_facebook');
    $ig = $settingsService->get('social_instagram');
    $tw = $settingsService->get('social_twitter');
    $wa = $settingsService->get('social_whatsapp');
@endphp

<footer class="border-t border-beige bg-charcoal text-white mt-auto">
    <div class="container-luxury py-12 md:py-18 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 mb-14">
            <!-- Brand -->
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2.5 mb-5">
                    @if ($logo)
                        <x-image :path="$logo" :alt="$siteName" class="h-7 w-auto" loading="eager" />
                    @else
                        <span class="font-serif text-xl text-gold tracking-tight">{{ $siteName }}</span>
                    @endif
                </div>
                <p class="text-warm-gray/70 text-sm leading-relaxed max-w-xs mb-6">
                    {{ $settingsService->get('footer_text', 'Fine jewellery crafted with passion and worn with pride. Timeless designs for every beautiful moment.') }}
                </p>
                <div class="flex gap-5">
                    @if ($ig)
                        <a href="{{ $ig }}" class="text-warm-gray/60 hover:text-gold transition-colors" target="_blank" rel="noopener" aria-label="Instagram">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </a>
                    @endif
                    @if ($fb)
                        <a href="{{ $fb }}" class="text-warm-gray/60 hover:text-gold transition-colors" target="_blank" rel="noopener" aria-label="Facebook">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                            </svg>
                        </a>
                    @endif
                    @if ($tw)
                        <a href="{{ $tw }}" class="text-warm-gray/60 hover:text-gold transition-colors" target="_blank" rel="noopener" aria-label="Twitter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                            </svg>
                        </a>
                    @endif
                    @if ($wa)
                        <a href="{{ $wa }}" class="text-warm-gray/60 hover:text-gold transition-colors" target="_blank" rel="noopener" aria-label="WhatsApp">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-xs font-semibold text-gold uppercase tracking-wider mb-6">Quick Links</h4>
                <ul class="space-y-3.5 text-sm text-warm-gray/70">
                    <li><a href="{{ route('home') }}" class="hover:text-gold transition-colors">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-gold transition-colors">About Us</a></li>
                    <li><a href="{{ route('jewellery.index') }}" class="hover:text-gold transition-colors">Jewellery</a></li>
                    <li><a href="{{ route('collections') }}" class="hover:text-gold transition-colors">Collections</a></li>
                    <li><a href="{{ route('gallery') }}" class="hover:text-gold transition-colors">Gallery</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-gold transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <!-- Jewellery Categories -->
            <div>
                <h4 class="text-xs font-semibold text-gold uppercase tracking-wider mb-6">Jewellery</h4>
                <ul class="space-y-3.5 text-sm text-warm-gray/70">
                    @foreach ($navCategories->take(8) as $category)
                        <li><a href="{{ route('category.show', $category->slug) }}"
                               class="hover:text-gold transition-colors">{{ $category->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('jewellery.index') }}" class="text-gold hover:underline">View All Categories</a></li>
                </ul>
            </div>

            <!-- Customer Support -->
            <div>
                <h4 class="text-xs font-semibold text-gold uppercase tracking-wider mb-6">Customer Support</h4>
                <ul class="space-y-4 text-sm text-warm-gray/70">
                    @if ($businessHours)
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 shrink-0 text-gold mt-0.5 flex items-center justify-center rounded-full bg-gold/10">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs font-medium text-charcoal/60 uppercase tracking-wider">Business Hours</p>
                                <p class="text-sm text-white">{{ $businessHours }}</p>
                            </div>
                        </li>
                    @endif
                    @if ($phone)
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 shrink-0 text-gold mt-0.5 flex items-center justify-center rounded-full bg-gold/10">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 011.515.736l.67 3.277a2 2 0 01-.446 1.802l-.545 1.27a.5.5 0 00.133.33c.336.155.55.04.735-.156l1.186-.593c.389-.194.593-.604.593-1.02V5.802a2 2 0 00-.593-1.02l-.546-1.27A2 2 0 018.28 2H5a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2v-1.172c0-.416-.204-.828-.593-1.02l-.545-1.27a2 2 0 01-.446-1.802l.67-3.277a1 1 0 011.515-.736H19a2 2 0 012 2v2a2 2 0 01-2 2z"></path>
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs font-medium text-charcoal/60 uppercase tracking-wider">Phone</p>
                                <a href="tel:{{ $phone }}" class="text-sm text-white hover:text-gold transition-colors">{{ $phone }}</a>
                            </div>
                        </li>
                    @endif
                    @if ($whatsapp)
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 shrink-0 text-gold mt-0.5 flex items-center justify-center rounded-full bg-gold/10">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs font-medium text-charcoal/60 uppercase tracking-wider">WhatsApp</p>
                                <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}"
                                   target="_blank" rel="noopener"
                                   class="text-sm text-white hover:text-gold transition-colors">{{ $whatsapp }}</a>
                            </div>
                        </li>
                    @endif
                    @if ($email)
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 shrink-0 text-gold mt-0.5 flex items-center justify-center rounded-full bg-gold/10">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 8l7.89 5.26a2 2 0 002.22-2.22L6 18m-3 3v1h12V3z"></path>
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs font-medium text-charcoal/60 uppercase tracking-wider">Email</p>
                                <a href="mailto:{{ $email }}" class="text-sm text-white hover:text-gold transition-colors">{{ $email }}</a>
                            </div>
                        </li>
                    @endif
                    @if ($address)
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 shrink-0 text-gold mt-0.5 flex items-center justify-center rounded-full bg-gold/10">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs font-medium text-charcoal/60 uppercase tracking-wider">Address</p>
                                <p class="text-sm text-white">{{ $address }}</p>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="border-t border-[rgba(233,225,214,0.25)] pt-10 flex flex-col lg:flex-row items-center justify-between gap-6 text-xs text-warm-gray/60">
            <p>&copy; {{ now()->year }} {{ $siteName }}. All rights reserved.</p>
            <div class="flex gap-8">
                <a href="{{ route('privacy') }}" class="hover:text-gold transition-colors">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="hover:text-gold transition-colors">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
</footer>
