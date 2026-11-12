<x-layout>
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-serif text-[#1a1a1a] mb-6">Customer Testimonials</h1>
                <div class="w-20 h-1 bg-[#d4a373] mx-auto"></div>
                <p class="text-lg text-[#666] mt-4">What our customers say about Royal Shine</p>
            </div>

            @if ($testimonials->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($testimonials as $testimonial)
                        <div class="bg-[#fdfaf5] border border-[#e5e5e5] p-8 rounded-none">
                            <div class="flex items-center gap-3 mb-4">
                                @if ($testimonial->image)
                                    <x-image
                                        :path="$testimonial->image"
                                        :alt="$testimonial->customer_name"
                                        class="w-10 h-10 rounded-full object-cover"
                                        loading="lazy"
                                    />
                                @else
                                    <div class="w-10 h-10 bg-[#f3f3f3] rounded-full flex items-center justify-center">
                                        <span class="text-[#999] text-xs uppercase tracking-wider">{{ str($testimonial->customer_name)->substr(0, 1)->upper() }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-[#1a1a1a] leading-tight">{{ $testimonial->customer_name }}</p>
                                    @if ($testimonial->customer_location)
                                        <p class="text-xs text-[#666]">{{ $testimonial->customer_location }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex text-[#d4a373] mb-4">
                                @for ($i = 1; $i <= $testimonial->rating; $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.97 0 1.399 2.18.747 2.979l-1.536 1.912a1 1 0 00.343 1.378l1.804 2.627a1 1 0 01-.156 1.219l-2.823.416a1 1 0 00-.997.66l-.393 3.167a1 1 0 01-1.596.347l-2.395-1.74a1 1 0 00-1.16-.577l-2.394 1.74a1 1 0 01-1.597-.347l-.393-3.167a1 1 0 00-.996-.66l-2.823-.416a1 1 0 01-.156-1.219l1.804-2.627a1 1 0 00.343-1.378L4.35 6.897a1 1 0 00.342-.69H1.876c-.652 0-1.037-2.18-.747-2.979l1.07-3.292c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.97 0 1.399 2.18.747 2.979l-1.536 1.912a1 1 0 00.343 1.378l1.804 2.627a1 1 0 01-.156 1.219l-2.823.416a1 1 0 00-.997.66l-.393 3.167a1 1 0 01-1.596.347l-2.395-1.74a1 1 0 00-1.16-.577l-2.394 1.74a1 1 0 01-1.597-.347l-.393-3.167a1 1 0 00-.996-.66l-2.823-.416a1 1 0 01-.156-1.219l1.804-2.627a1 1 0 00.343-1.378L4.35 6.897a1 1 0 00.342-.69z"></path></svg>
                                @endfor
                            </div>
                            <p class="text-[#666] leading-relaxed italic">{{ $testimonial->testimonial }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-[#d4a373] text-6xl mb-6">⭐</div>
                    <h2 class="text-2xl font-serif text-[#1a1a1a] mb-4">No Testimonials Yet</h2>
                    <p class="text-[#666]">Customer testimonials will appear here once approved from the admin panel.</p>
                </div>
            @endif

            <!-- WhatsApp CTA -->
            <div class="mt-16 bg-[#d4a373] text-white py-16 px-6 rounded-none text-center">
                <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4">Share Your Experience</h2>
                <p class="text-lg mb-6 max-w-2xl mx-auto">We'd love to hear about your experience with Royal Shine jewellery.</p>
                @php
                    $whatsapp = app(\App\Services\SettingsService::class)->get('whatsapp_number');
                @endphp
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hello Royal Shine, I would like to share my testimonial about your jewellery.') }}" class="bg-white text-[#d4a373] px-8 py-4 rounded-none font-bold text-lg inline-block hover:bg-[#fdfaf5] transition-colors shadow-lg">
                        Message on WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </section>
</x-layout>
