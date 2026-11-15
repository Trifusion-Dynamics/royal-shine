<x-layout :seoTitle="null" :metaDescription="null">
    @php
        $settingsService = app(\App\Services\SettingsService::class);
        $phone = $settingsService->get('phone');
        $whatsapp = $settingsService->get('whatsapp_number');
        $email = $settingsService->get('email');
        $address = $settingsService->get('address');
        $businessHours = $settingsService->get('business_hours');
        $mapLink = $settingsService->get('map_link');
        $pageTitle = 'Contact Us';
    @endphp

    <section class="py-16">
        <div class="container-luxury">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-serif text-charcoal mb-4 leading-tight">{{ $pageTitle }}</h1>
                <div class="w-20 h-[1px] bg-gold mx-auto mb-6"></div>
                <p class="text-lg text-warm-gray max-w-2xl mx-auto leading-relaxed">
                    Have a question about our jewellery collection? Want to place a custom order? We&rsquo;d love to hear from you.
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div class="space-y-10">
                    <div>
                        <h2 class="text-3xl font-serif text-charcoal mb-6 leading-tight">Get in Touch</h2>
                        <p class="text-warm-gray leading-relaxed mb-6">
                            Reach out to us through any of the channels below. Our team is here to help with designs, custom orders,
                            and any questions about our collection.
                        </p>
                    </div>

                    <div class="space-y-6">
                        @if ($businessHours)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-gold/10 text-gold rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-charcoal">Business Hours</p>
                                    <p class="text-warm-gray">{{ $businessHours }}</p>
                                </div>
                            </div>
                        @endif
                        @if ($phone)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-gold/10 text-gold rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 011.515.736l.67 3.277a2 2 0 01-.446 1.802l-.545 1.27a.5.5 0 00.133.33c.336.155.55.04.735-.156l1.186-.593c.389-.194.593-.604.593-1.02V5.802a2 2 0 00-.593-1.02l-.546-1.27A2 2 0 018.28 2H5a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2v-1.172c0-.416-.204-.828-.593-1.02l-.545-1.27a2 2 0 01-.446-1.802l.67-3.277a1 1 0 011.515-.736H19a2 2 0 012 2v2a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-charcoal">Phone</p>
                                    <a href="tel:{{ $phone }}" class="text-warm-gray hover:text-gold transition-colors">{{ $phone }}</a>
                                </div>
                            </div>
                        @endif
                        @if ($whatsapp)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-gold/10 text-gold rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-charcoal">WhatsApp</p>
                                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I would like to enquire about your jewellery collection.') }}" target="_blank" rel="noopener" class="text-warm-gray hover:text-gold transition-colors">{{ $whatsapp }}</a>
                                </div>
                            </div>
                        @endif
                        @if ($email)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-gold/10 text-gold rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22-2.22L6 18m-3 3v1h12V3z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-charcoal">Email</p>
                                    <a href="mailto:{{ $email }}" class="text-warm-gray hover:text-gold transition-colors">{{ $email }}</a>
                                </div>
                            </div>
                        @endif
                        @if ($address)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-gold/10 text-gold rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-charcoal">Address</p>
                                    <p class="text-warm-gray">{{ $address }}</p>
                                </div>
                            </div>
                        @endif
                        @if ($mapLink)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 shrink-0 bg-gold/10 text-gold rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-charcoal">Location</p>
                                    <a href="{{ $mapLink }}" class="text-gold hover:underline text-sm" target="_blank" rel="noopener">View on Map</a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        <h3 class="font-serif font-bold text-charcoal mb-4 text-xl">Visit Our Store</h3>
                        @if ($mapLink)
                            <a href="{{ $mapLink }}" class="btn btn-gold inline-block px-8 py-3" target="_blank" rel="noopener">Get Directions</a>
                        @endif
                    </div>
                </div>

                <!-- Enquiry Form -->
                <div class="border border-beige bg-white rounded-lg p-8 md:p-12 shadow-sm">
                    <h2 class="font-serif font-bold text-charcoal mb-6 text-2xl leading-tight">Send Us a Message</h2>
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-charcoal mb-1.5">Full Name <span class="text-gold">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 @error('name') border-brown @enderror">
                                @error('name') <p class="mt-1 text-xs text-brown">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-charcoal mb-1.5">Mobile Number <span class="text-gold">*</span></label>
                                <input type="tel" name="mobile" value="{{ old('mobile') }}"
                                    class="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 @error('mobile') border-brown @enderror">
                                @error('mobile') <p class="mt-1 text-xs text-brown">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-charcoal mb-1.5">Email <span class="text-gold">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 @error('email') border-brown @enderror">
                                @error('email') <p class="mt-1 text-xs text-brown">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-charcoal mb-1.5">Subject <span class="text-gold">*</span></label>
                                <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="255"
                                    class="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 @error('subject') border-brown @enderror">
                                @error('subject') <p class="mt-1 text-xs text-brown">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-charcoal mb-1.5">Message <span class="text-gold">*</span></label>
                                <textarea name="message" rows="5" class="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 @error('message') border-brown @enderror">{{ old('message') }}</textarea>
                                @error('message') <p class="mt-1 text-xs text-brown">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gold w-full sm:w-auto px-8 py-3">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layout>
