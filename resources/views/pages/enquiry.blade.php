<x-layout>
    <section class="max-w-3xl mx-auto py-12">
        <h1 class="text-3xl md:text-4xl font-serif text-charcoal mb-8">Send an Enquiry</h1>

        @if (session('success'))
            <div class="bg-gold/5 border border-gold/20 text-brown px-6 py-4 rounded-md mb-8">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('enquiry.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

            @if ($product)
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="product_reference" value="{{ $product->sku }}">
                <div class="border border-beige bg-white p-5 rounded-md mb-4">
                    <p class="text-sm text-warm-gray font-medium">Enquiry for: <span class="font-medium text-charcoal">{{ $product->name }}</span> (SKU: {{ $product->sku }})</p>
                </div>
            @endif

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
                    <label class="block text-sm font-medium text-charcoal mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 @error('email') border-brown @enderror">
                    @error('email') <p class="mt-1 text-xs text-brown">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-charcoal mb-1.5">Message <span class="text-gold">*</span></label>
                    <textarea name="message" rows="4" class="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 @error('message') border-brown @enderror">{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1 text-xs text-brown">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-gold w-full sm:w-auto px-8 py-3">Submit Enquiry</button>
        </form>
    </section>
</x-layout>
