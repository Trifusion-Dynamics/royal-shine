<x-layout>
    <section class="py-20 text-center">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-[#d4a373] text-9xl mb-6">500</div>
            <h1 class="text-4xl md:text-5xl font-serif text-[#1a1a1a] mb-4">Server Error</h1>
            <p class="text-lg text-[#666] mb-8 max-w-lg mx-auto">
                Something went wrong on our end. Please try again later or contact us for assistance.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" class="btn-gold px-8 py-3">Go to Homepage</a>
                @php
                    $whatsapp = app(\App\Services\SettingsService::class)->get('whatsapp_number');
                @endphp
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi Royal Shine, I encountered an error on your website.') }}"
                       class="btn-outline px-8 py-3 text-center text-white hover:bg-white hover:text-[#d4a373] border-white">
                        Contact on WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </section>
</x-layout>
