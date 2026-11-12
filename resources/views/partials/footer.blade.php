<footer class="bg-[#1a1a1a] text-[#e5e5e5] border-t border-[#333] mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Brand -->
            <div class="lg:col-span-1">
                @php
                    $logo = app(\App\Services\SettingsService::class)->get('logo');
                    $siteName = app(\App\Services\SettingsService::class)->get('site_name', 'Royal Shine');
                @endphp
                @if ($logo)
                    <x-image :path="$logo" :alt="$siteName" class="h-10 w-auto mb-4" loading="eager" />
                @else
                    <div class="text-xl font-bold serif text-[#d4a373] mb-4">{{ $siteName }}</div>
                @endif
                <p class="text-sm text-[#999] leading-relaxed max-w-xs">
                    {{ app(\App\Services\SettingsService::class)->get('footer_text', 'Premium jewellery crafted with care.') }}
                </p>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white font-medium serif mb-4">Contact</h4>
                <ul class="space-y-2 text-sm text-[#999]">
                    @php
                        $phone = app(\App\Services\SettingsService::class)->get('phone');
                        $whatsapp = app(\App\Services\SettingsService::class)->get('whatsapp_number');
                        $email = app(\App\Services\SettingsService::class)->get('email');
                        $address = app(\App\Services\SettingsService::class)->get('address');
                    @endphp
                    @if ($phone)
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 011.515.736l.67 3.277a2 2 0 01-.446 1.802l-.545 1.27a.5.5 0 00.133.33c.336.155.55.04.735-.156l1.186-.593c.389-.194.593-.604.593-1.02V5.802a2 2 0 00-.593-1.02l-.546-1.27A2 2 0 018.28 2H5a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2v-1.172c0-.416-.204-.828-.593-1.02l-.545-1.27a2 2 0 01-.446-1.802l.67-3.277a1 1 0 011.515-.736H19a2 2 0 012 2v2a2 2 0 01-2 2z"></path></svg>
                            <span>{{ $phone }}</span>
                        </li>
                    @endif
                    @if ($whatsapp)
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>WhatsApp: {{ $whatsapp }}</span>
                        </li>
                    @endif
                    @if ($email)
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22-2.22L6 18m-3 3v1h12V3z"></path></svg>
                            <span>{{ $email }}</span>
                        </li>
                    @endif
                    @if ($address)
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>{{ $address }}</span>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Social -->
            <div>
                <h4 class="text-white font-medium serif mb-4">Follow Us</h4>
                <div class="flex gap-4">
                    @php
                        $fb = app(\App\Services\SettingsService::class)->get('social_facebook');
                        $ig = app(\App\Services\SettingsService::class)->get('social_instagram');
                        $wa = app(\App\Services\SettingsService::class)->get('social_whatsapp');
                        $tw = app(\App\Services\SettingsService::class)->get('social_twitter');
                    @endphp
                    @if ($fb)
                        <a href="{{ $fb }}" class="text-[#999] hover:text-[#d4a373] transition-colors" target="_blank" rel="noopener">Facebook</a>
                    @endif
                    @if ($ig)
                        <a href="{{ $ig }}" class="text-[#999] hover:text-[#d4a373] transition-colors" target="_blank" rel="noopener">Instagram</a>
                    @endif
                    @if ($wa)
                        <a href="{{ $wa }}" class="text-[#999] hover:text-[#d4a373] transition-colors" target="_blank" rel="noopener">WhatsApp</a>
                    @endif
                    @if ($tw)
                        <a href="{{ $tw }}" class="text-[#999] hover:text-[#d4a373] transition-colors" target="_blank" rel="noopener">Twitter</a>
                    @endif
                </div>
            </div>

            <!-- Policies -->
            <div>
                <h4 class="text-white font-medium serif mb-4">Policies</h4>
                <ul class="space-y-2 text-sm text-[#999]">
                    <li><a href="{{ route('privacy') }}" class="hover:text-[#d4a373] transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-[#d4a373] transition-colors">Terms & Conditions</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-[#333] pt-8 text-center text-xs text-[#666]">
            &copy; {{ now()->year }} {{ app(\App\Services\SettingsService::class)->get('site_name', 'Royal Shine') }}. All rights reserved.
        </div>
    </div>
</footer>
