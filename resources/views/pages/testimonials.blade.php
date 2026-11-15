<x-layout>
    @php
        $settingsService = app(\App\Services\SettingsService::class);
        $whatsapp = $settingsService->get('whatsapp_number');
    @endphp

    <section class="py-16">
        <div class="container-luxury">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-serif text-charcoal mb-4 leading-tight">Customer Testimonials</h1>
                <div class="w-20 h-[1px] bg-gold mx-auto mb-6"></div>
                <p class="text-lg text-warm-gray max-w-2xl mx-auto leading-relaxed">What our customers say about Royal Shine</p>
            </div>

            @if ($testimonials->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($testimonials as $testimonial)
                        <figure class="border border-beige bg-white p-8 rounded-lg card">
                            <div class="flex items-center gap-4 mb-5">
                                @if ($testimonial->image)
                                    <div class="w-10 h-10 shrink-0 rounded-full overflow-hidden border border-beige">
                                        <x-image :path="$testimonial->image" :alt="$testimonial->customer_name"
                                                 class="w-full h-full object-cover" loading="lazy" />
                                    </div>
                                @else
                                    <div class="w-10 h-10 shrink-0 rounded-full bg-beige flex items-center justify-center">
                                        <span class="text-warm-gray text-xs font-medium uppercase tracking-wider">
                                            {{ str($testimonial->customer_name)->substr(0, 1)->upper() }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-charcoal leading-tight">{{ $testimonial->customer_name }}</p>
                                    @if ($testimonial->customer_location)
                                        <p class="text-xs text-warm-gray uppercase tracking-wider">{{ $testimonial->customer_location }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex text-gold mb-5">
                                @for ($i = 1; $i <= ($testimonial->rating ?? 5); $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.97 0 1.399 2.18.747 2.979l-1.536 1.912a1 1 0 00.343 1.378l1.804 2.627a1 1 0 01-.156 1.219l-2.823.416a1 1 0 00-.997.66l-.393 3.167a1 1 0 01-1.596.347l-2.395-1.74a1 1 0 00-1.16-.577l-2.394 1.74a1 1 0 01-1.597-.347l-.393-3.167a1 1 0 00-.996-.66l-2.823-.416a1 1 0 01-.156-1.219l1.804-2.627a1 1 0 00.343-1.378L4.35 6.897a1 1 0 00.342-.69z"></path></svg>
                                @endfor
                            </div>
                            <p class="text-warm-gray leading-relaxed italic">“{{ $testimonial->testimonial }}”</p>
                        </figure>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-gold text-6xl mb-6">⭐</div>
                    <h2 class="text-3xl font-serif text-charcoal mb-4">No Testimonials Yet</h2>
                    <p class="text-warm-gray max-w-md mx-auto leading-relaxed">Customer testimonials will appear here once approved from the admin panel.</p>
                </div>
            @endif

            @if ($whatsapp)
                <div class="mt-16 border border-beige bg-champagne rounded-lg p-10 md:p-14 text-center">
                    <h2 class="text-3xl md:text-4xl font-serif text-charcoal mb-4 leading-tight">Share Your Experience</h2>
                    <p class="text-lg text-warm-gray mb-6 max-w-2xl mx-auto leading-relaxed">We&rsquo;d love to hear about your experience with Royal Shine jewellery.</p>
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hello Royal Shine, I would like to share my testimonial about your jewellery.') }}"
                       target="_blank" rel="noopener"
                       class="btn btn-gold btn-lg whitespace-nowrap">
                        Message on WhatsApp
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-layout>
