<x-layout :seoTitle="$page->seo_title ?? 'Terms & Conditions'" :metaDescription="$page->seo_description ?? null" :canonicalUrl="route('terms', absolute: true)">
    <section class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl md:text-4xl font-serif text-[#1a1a1a] mb-8">{{ $page->title ?? 'Terms & Conditions' }}</h1>
        <div class="prose prose-lg text-[#666] leading-relaxed max-w-none">
            @if ($page?->content)
                {!! $page->content !!}
            @else
                <p>These Terms &amp; Conditions govern your use of the Royal Shine website and the submission of product enquiries.</p>
            @endif
        </div>
    </section>
</x-layout>
