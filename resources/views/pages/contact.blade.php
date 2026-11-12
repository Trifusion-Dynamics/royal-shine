<x-layout>
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-serif text-[#1a1a1a] mb-6">Contact Us</h1>
                <div class="w-20 h-1 bg-[#d4a373] mx-auto"></div>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div class="space-y-8">
                    <div>
                        <h3 class="font-serif font-bold text-[#1a1a1a] text-2xl mb-6">Get in Touch</h3>
                        @if ($page?->content)
                            <div class="prose text-[#666] leading-relaxed mb-6 max-w-none">{!! $page->content !!}</div>
                        @else
                            <p class="text-[#666] leading-relaxed mb-6">
                                Have a question about our jewellery collection? Want to place a custom order? We'd love to hear from you. Reach out to us through any of the channels below.
                            </p>
                        @endif
                    </div>

                    @php
                        $phone = app(\App\Services\SettingsService::class)->get('phone');
                        $whatsapp = app(\App\Services\SettingsService::class)->get('whatsapp_number');
                        $email = app(\App\Services\SettingsService::class)->get('email');
                        $address = app(\App\Services\SettingsService::class)->get('address');
                        $mapLink = app(\App\Services\SettingsService::class)->get('map_link');
                        $businessHours = app(\App\Services\SettingsService::class)->get('business_hours');
                    @endphp

                    <div class="space-y-6">
                        @if ($businessHours)
                            <div>
                                <p class="font-medium text-[#1a1a1a]">Business Hours</p>
                                <p class="text-[#666]">{{ $businessHours }}</p>
                            </div>
                        @endif
                        @if ($phone)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-[#d4a373]/10 text-[#d4a373] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 011.515.736l.67 3.277a2 2 0 01-.446 1.802l-.545 1.27a.5.5 0 00.133.33c.336.155.55.04.735-.156l1.186-.593c.389-.194.593-.604.593-1.02V5.802a2 2 0 00-.593-1.02l-.546-1.27A2 2 0 018.28 2H5a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2v-1.172c0-.416-.204-.828-.593-1.02l-.545-1.27a2 2 0 01-.446-1.802l.67-3.277a1 1 0 011.515-.736H19a2 2 0 012 2v2a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-[#1a1a1a]">Phone</p>
                                    <p class="text-[#666]">{{ $phone }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($whatsapp)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-[#d4a373]/10 text-[#d4a373] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-[#1a1a1a]">WhatsApp</p>
                                    <p class="text-[#666]">{{ $whatsapp }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($email)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-[#d4a373]/10 text-[#d4a373] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22-2.22L6 18m-3 3v1h12V3z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-[#1a1a1a]">Email</p>
                                    <p class="text-[#666]">{{ $email }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($address)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-[#d4a373]/10 text-[#d4a373] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-[#1a1a1a]">Address</p>
                                    <p class="text-[#666]">{{ $address }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($mapLink)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-[#d4a373]/10 text-[#d4a373] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-[#1a1a1a]">Location</p>
                                    <a href="{{ $mapLink }}" class="text-[#d4a373] hover:underline text-sm" target="_blank" rel="noopener">View on Map</a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        <h4 class="font-serif font-bold text-[#1a1a1a] mb-4">Visit Our Store</h4>
                        @if ($mapLink)
                            <a href="{{ $mapLink }}" class="btn-gold inline-block px-8 py-3" target="_blank" rel="noopener">Get Directions</a>
                        @endif
                    </div>
                </div>

                <!-- Enquiry Form -->
                <div class="bg-[#fdfaf5] border border-[#e5e5e5] p-8">
                    <h3 class="font-serif font-bold text-[#1a1a1a] text-2xl mb-6">Send Us a Message</h3>
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Full Name <span class="text-[#d4a373]">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('name') border-red-500 @enderror"
                                    @error('name') aria-invalid="true" @enderror>
                                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Mobile Number <span class="text-[#d4a373]">*</span></label>
                                <input type="tel" name="mobile" value="{{ old('mobile') }}"
                                    class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('mobile') border-red-500 @enderror"
                                    @error('mobile') aria-invalid="true" @enderror>
                                @error('mobile') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Email <span class="text-[#d4a373]">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('email') border-red-500 @enderror"
                                    @error('email') aria-invalid="true" @enderror>
                                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Subject <span class="text-[#d4a373]">*</span></label>
                                <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="255"
                                    class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('subject') border-red-500 @enderror"
                                    @error('subject') aria-invalid="true" @enderror>
                                @error('subject') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Message <span class="text-[#d4a373]">*</span></label>
                                <textarea name="message" rows="5" class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('message') border-red-500 @enderror"
                                    @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                                @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn-gold w-full sm:w-auto px-8 py-3">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layout>
