@props([
    'name',
    'options' => [],
    'selected' => '',
    'placeholder' => 'Select',
])

@php
    $items = collect($options)
        ->map(fn ($label, $value) => ['value' => (string) $value, 'label' => (string) $label])
        ->values()
        ->all();
@endphp

<div
    x-data="{
        open: false,
        value: @js((string) $selected),
        items: @js($items),
        get currentLabel() {
            const found = this.items.find((item) => item.value === this.value);
            return found ? found.label : @js($placeholder);
        },
        choose(val) {
            this.value = val;
            this.open = false;
            this.$nextTick(() => this.$root.closest('form')?.requestSubmit());
        }
    }"
    @keydown.escape="open = false"
    @click.outside="open = false"
    class="relative"
>
    <input type="hidden" name="{{ $name }}" :value="value">

    <button
        type="button"
        @click="open = !open"
        :aria-expanded="open.toString()"
        class="w-full flex items-center justify-between gap-2 bg-white border border-[#e5e5e5] px-4 py-3 text-sm text-[#1a1a1a] text-left transition-colors hover:border-[#d4a373] focus:outline-none focus:ring-2 focus:ring-[#d4a373]"
    >
        <span class="truncate" x-text="currentLabel"></span>
        <svg class="w-4 h-4 flex-shrink-0 text-[#999] transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition.origin.top
        class="absolute left-0 right-0 z-40 mt-1 max-h-64 overflow-y-auto bg-white border border-[#e5e5e5] shadow-xl"
    >
        <template x-for="item in items" :key="item.value">
            <button
                type="button"
                @click="choose(item.value)"
                class="w-full flex items-center justify-between gap-2 px-4 py-2.5 text-left text-sm transition-colors"
                :class="value === item.value
                    ? 'bg-[#fdfaf5] text-[#d4a373] font-medium'
                    : 'text-[#1a1a1a] hover:bg-[#fdfaf5] hover:text-[#d4a373]'"
            >
                <span x-text="item.label"></span>
                <svg x-show="value === item.value" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </template>
    </div>
</div>
