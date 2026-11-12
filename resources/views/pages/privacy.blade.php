<x-layout :seoTitle="$page->seo_title ?? 'Privacy Policy'" :metaDescription="$page->seo_description ?? null" :canonicalUrl="route('privacy', absolute: true)">
    <section class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl md:text-4xl font-serif text-[#1a1a1a] mb-8">{{ $page->title ?? 'Privacy Policy' }}</h1>
        <div class="prose prose-lg text-[#666] leading-relaxed max-w-none">
            @if ($page?->content)
                {!! $page->content !!}
            @else
                <p>This Privacy Policy describes how Royal Shine collects, uses, and protects your personal information when you visit our website or submit an enquiry.</p>
            @endif
        </div>
    </section>
</x-layout>
