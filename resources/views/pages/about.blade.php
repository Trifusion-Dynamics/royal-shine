<x-layout :seoTitle="$page->seo_title ?? 'About Us'" :metaDescription="$page->seo_description ?? null" :canonicalUrl="route('about', absolute: true)">
    @php
        $settingsService = app(\App\Services\SettingsService::class);
        $aboutImage = $settingsService->get('about_image');
    @endphp

    <section class="py-16">
        <div class="container-luxury">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-serif text-charcoal mb-4 leading-tight">{{ $page->title ?? 'About Royal Shine' }}</h1>
                <div class="w-20 h-[1px] bg-gold mx-auto mb-6"></div>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="order-last lg:order-first">
                    @if ($aboutImage)
                        <div class="relative h-[600px] overflow-hidden rounded-lg border border-beige shadow-luxury-lg">
                            <x-image :path="$aboutImage" :alt="$page->title ?? 'Royal Shine'"
                                     class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 reveal reveal-delay-1"
                                     loading="lazy" />
                        </div>
                    @else
                        <div class="relative h-[600px] bg-champagne rounded-lg border border-beige flex items-center justify-center reveal reveal-delay-1">
                            <span class="font-serif text-8xl text-gold/20">RS</span>
                        </div>
                    @endif
                </div>

                <div class="reveal reveal-delay-2">
                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-gold/10 text-gold text-xs font-semibold uppercase tracking-wider mb-5 border border-gold/30 rounded-full">
                        Our Story
                    </div>
                    <h2 class="text-4xl md:text-5xl font-serif text-charcoal mb-6 leading-tight">Crafted with Passion. Worn with Pride.</h2>
                    @if ($page?->content)
                        <div class="prose text-warm-gray leading-relaxed max-w-none mb-8">
                            {!! $page->content !!}
                        </div>
                    @else
                        <p class="text-lg text-warm-gray leading-relaxed mb-6 max-w-lg">
                            Royal Shine brings together fine jewellery for celebrations, thoughtful gifts, and everyday style.
                            Our catalogue makes it easy to explore each piece and ask our team for details.
                        </p>
                        <p class="text-lg text-warm-gray leading-relaxed mb-6 max-w-lg">
                            Discover a range of designs shaped by timeless forms and contemporary details. Each product page
                            shares the available materials and specifications so you can make an informed enquiry.
                        </p>
                    @endif
                    <a href="{{ route('contact') }}" class="btn btn-gold inline-block">Get in Touch</a>
                </div>
            </div>

            <!-- Commitment / Values -->
            <div class="mt-18">
                <h2 class="text-3xl md:text-4xl font-serif text-charcoal text-center mb-12 leading-tight">Our Commitment</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-8 border border-beige rounded-lg card">
                        <div class="w-12 h-12 bg-gold/10 text-gold rounded-full flex items-center justify-center mx-auto mb-5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h4 class="font-serif font-bold text-charcoal mb-2">Quality Assured</h4>
                        <p class="text-warm-gray text-sm leading-relaxed">Every piece undergoes strict quality checks before reaching you.</p>
                    </div>
                    <div class="text-center p-8 border border-beige rounded-lg card">
                        <div class="w-12 h-12 bg-gold/10 text-gold rounded-full flex items-center justify-center mx-auto mb-5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h4 class="font-serif font-bold text-charcoal mb-2">Certified Purity</h4>
                        <p class="text-warm-gray text-sm leading-relaxed">Product specifications are listed individually and can be confirmed with our team before you enquire.</p>
                    </div>
                    <div class="text-center p-8 border border-beige rounded-lg card">
                        <div class="w-12 h-12 bg-gold/10 text-gold rounded-full flex items-center justify-center mx-auto mb-5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h4 class="font-serif font-bold text-charcoal mb-2">Customer First</h4>
                        <p class="text-warm-gray text-sm leading-relaxed">Contact details appear here once configured by the Royal Shine team.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
