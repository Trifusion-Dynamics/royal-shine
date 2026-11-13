@php
    $settingsService = app(\App\Services\SettingsService::class);
    $logo = $settingsService->get('logo');
    $siteName = $settingsService->get('site_name', 'Royal Shine');
    $phone = $settingsService->get('phone');
    $whatsapp = $settingsService->get('whatsapp_number');
    $email = $settingsService->get('email');
    $address = $settingsService->get('address');
    $fb = $settingsService->get('social_facebook');
    $ig = $settingsService->get('social_instagram');
    $tw = $settingsService->get('social_twitter');
@endphp

<footer class="bg-[#1a1a1a] text-[#e5e5e5] border-t border-[#333] mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Brand -->
            <div class="lg:col-span-1">
                @if ($logo)
                    <x-image :path="$logo" :alt="$siteName" class="h-10 w-auto mb-4" loading="eager" />
                @else
                    <div class="text-xl font-bold serif text-[#d4a373] mb-4">{{ $siteName }}</div>
                @endif
                <p class="text-sm text-[#999] leading-relaxed max-w-xs mb-6">
                    {{ $settingsService->get('footer_text', 'Premium jewellery crafted with care.') }}
                </p>
                <div class="flex gap-4 text-sm">
                    @if ($fb)
                        <a href="{{ $fb }}" class="text-[#999] hover:text-[#d4a373] transition-colors" target="_blank" rel="noopener">Facebook</a>
                    @endif
                    @if ($ig)
                        <a href="{{ $ig }}" class="text-[#999] hover:text-[#d4a373] transition-colors" target="_blank" rel="noopener">Instagram</a>
                    @endif
                    @if ($tw)
                        <a href="{{ $tw }}" class="text-[#999] hover:text-[#d4a373] transition-colors" target="_blank" rel="noopener">Twitter</a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-medium serif mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm text-[#999]">
                    <li><a href="{{ route('home') }}" class="hover:text-[#d4a373] transition-colors">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-[#d4a373] transition-colors">About Us</a></li>
                    <li><a href="{{ route('jewellery.index') }}" class="hover:text-[#d4a373] transition-colors">Jewellery</a></li>
                    <li><a href="{{ route('gallery') }}" class="hover:text-[#d4a373] transition-colors">Gallery</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#d4a373] transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <!-- Jewellery Categories -->
            <div>
                <h4 class="text-white font-medium serif mb-4">Jewellery</h4>
                <ul class="space-y-2 text-sm text-[#999]">
                    @foreach ($navCategories->take(6) as $category)
                        <li><a href="{{ route('category.show', $category->slug) }}" class="hover:text-[#d4a373] transition-colors">{{ $category->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('jewellery.index') }}" class="text-[#d4a373] hover:underline">View All Categories</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white font-medium serif mb-4">Contact</h4>
                <ul class="space-y-3 text-sm text-[#999]">
                    @if ($phone)
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 011.515.736l.67 3.277a2 2 0 01-.446 1.802l-.545 1.27a.5.5 0 00.133.33c.336.155.55.04.735-.156l1.186-.593c.389-.194.593-.604.593-1.02V5.802a2 2 0 00-.593-1.02l-.546-1.27A2 2 0 018.28 2H5a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2v-1.172c0-.416-.204-.828-.593-1.02l-.545-1.27a2 2 0 01-.446-1.802l.67-3.277a1 1 0 011.515-.736H19a2 2 0 012 2v2a2 2 0 01-2 2z"></path></svg>
                            <a href="tel:{{ $phone }}" class="hover:text-[#d4a373]">{{ $phone }}</a>
                        </li>
                    @endif
                    @if ($whatsapp)
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                            <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}" target="_blank" rel="noopener" class="hover:text-[#d4a373]">WhatsApp</a>
                        </li>
                    @endif
                    @if ($email)
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22-2.22L6 18m-3 3v1h12V3z"></path></svg>
                            <a href="mailto:{{ $email }}" class="hover:text-[#d4a373]">{{ $email }}</a>
                        </li>
                    @endif
                    @if ($address)
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>{{ $address }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="border-t border-[#333] pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#666]">
            <p>&copy; {{ now()->year }} {{ $siteName }}. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="{{ route('privacy') }}" class="hover:text-[#d4a373] transition-colors">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="hover:text-[#d4a373] transition-colors">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
</footer>
