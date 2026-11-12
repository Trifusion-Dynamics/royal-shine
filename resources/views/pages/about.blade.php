<x-layout :seoTitle="$page->seo_title ?? 'About Us'" :metaDescription="$page->seo_description ?? null" :canonicalUrl="route('about', absolute: true)">
    <section class="py-20 pt-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-serif text-[#1a1a1a] mb-6">{{ $page->title ?? 'About Royal Shine' }}</h1>
                <div class="w-20 h-1 bg-[#d4a373] mx-auto"></div>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 items-center">
                @if ($page?->content)
                    <div class="prose prose-lg text-[#666] leading-relaxed max-w-none">
                        {!! $page->content !!}
                    </div>
                @else
                    <div class="prose prose-lg text-[#666] leading-relaxed space-y-6">
                        <p class="text-lg">
                            Royal Shine brings together jewellery for celebrations, thoughtful gifts and everyday style. Our catalogue makes it easy to explore each piece and ask our team for details.
                        </p>
                        <p>
                            Discover a range of designs shaped by timeless forms and contemporary details. Product pages share the available materials and specifications so you can make an informed enquiry.
                        </p>
                        <p>
                            Whether you are looking for a statement necklace, elegant earrings or a meaningful gift, browse the catalogue and get in touch with the Royal Shine team.
                        </p>
                    </div>
                @endif
                <div class="relative h-[500px]">
                    <div class="absolute inset-0 bg-[#f3f3f3] overflow-hidden">
                        <x-image
                            :path="app(\App\Services\SettingsService::class)->get('about_image') ?: null"
                            alt="{{ $page->title ?? 'About Royal Shine' }}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        />
                    </div>
                    <div class="absolute bottom-6 left-6 bg-white p-6 shadow-xl border border-[#e5e5e5]">
                        <h3 class="font-serif font-bold text-[#1a1a1a] text-lg mb-2">Our Craftsmanship</h3>
                        <p class="text-sm text-[#666]">Explore each product page for the materials, purity and stone information available for that design.</p>
                    </div>
                </div>
            </div>

            <!-- Values -->
            <div class="mt-20">
                <h2 class="text-3xl md:text-4xl font-serif text-center text-[#1a1a1a] mb-12">Our Commitment</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-[#d4a373]/10 text-[#d4a373] rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h4 class="font-serif font-bold text-[#1a1a1a] mb-2">Quality Assured</h4>
                        <p class="text-sm text-[#666]">Every piece undergoes strict quality checks before reaching you.</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-[#d4a373]/10 text-[#d4a373] rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h4 class="font-serif font-bold text-[#1a1a1a] mb-2">Certified Purity</h4>
                        <p class="text-sm text-[#666]">Product specifications are listed individually and can be confirmed with our team before you enquire.</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-[#d4a373]/10 text-[#d4a373] rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h4 class="font-serif font-bold text-[#1a1a1a] mb-2">Customer First</h4>
                        <p class="text-sm text-[#666]">Contact details appear here once they have been configured by the Royal Shine team.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
