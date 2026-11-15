<x-layout>
    <section class="py-20 text-center">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-gold text-9xl mb-6">500</div>
            <h1 class="text-4xl md:text-5xl font-serif text-charcoal mb-4 leading-tight">Something Went Wrong</h1>
            <p class="text-lg text-warm-gray mb-8 max-w-lg mx-auto leading-relaxed">
                We&rsquo;re sorry, but something went wrong on our end. Please try again later.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" class="btn btn-gold px-8 py-3">Go to Homepage</a>
            </div>
        </div>
    </section>
</x-layout>
