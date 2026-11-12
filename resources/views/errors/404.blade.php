<x-layout :seoTitle="'Page Not Found'">
    <section class="max-w-3xl mx-auto py-24 px-4 text-center">
        <div class="text-[#d4a373] text-8xl font-serif mb-6">404</div>
        <h1 class="text-3xl md:text-4xl font-serif text-[#1a1a1a] mb-4">Page Not Found</h1>
        <p class="text-[#666] text-lg mb-8 max-w-md mx-auto">
            The page you are looking for could not be found. It may have been moved or no longer exists.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" class="btn-gold px-8 py-3">Back to Home</a>
            <a href="{{ route('jewellery.index') }}" class="btn-outline px-8 py-3">Browse Jewellery</a>
        </div>
    </section>
</x-layout>
