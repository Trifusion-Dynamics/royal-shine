<x-layout>
    <section class="max-w-3xl mx-auto py-12">
        <h1 class="text-3xl md:text-4xl font-serif text-[#1a1a1a] mb-8">Send an Enquiry</h1>

        @if (session('success'))
            <div class="bg-[#f0fdf4] border border-[#86efac] text-[#166534] px-6 py-4 rounded-none mb-8">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('enquiry.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

            @if ($product)
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="product_reference" value="{{ $product->sku }}">
                <div class="bg-[#fdfaf5] border border-[#e5e5e5] p-4 rounded-none mb-4">
                    <p class="text-sm text-[#666] font-medium">Enquiry for: <span class="font-medium text-[#1a1a1a]">{{ $product->name }}</span> (SKU: {{ $product->sku }})</p>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('name') border-red-500 @enderror"
                        @error('name') aria-invalid="true" @enderror>
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="mobile" value="{{ old('mobile') }}"
                        class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('mobile') border-red-500 @enderror"
                        @error('mobile') aria-invalid="true" @enderror>
                    @error('mobile') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('email') border-red-500 @enderror"
                        @error('email') aria-invalid="true" @enderror>
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Message <span class="text-red-500">*</span></label>
                    <textarea name="message" rows="4" class="w-full px-4 py-3 border border-[#e5e5e5] rounded-none text-sm focus:outline-none focus:ring-2 focus:ring-[#d4a373] @error('message') border-red-500 @enderror"
                        @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit" class="btn-gold w-full sm:w-auto px-8 py-3">Submit Enquiry</button>
        </form>
    </section>
</x-layout>
