<div class="space-y-4">
    @forelse ($enquiries as $enquiry)
        <article class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <p class="font-medium">{{ $enquiry->subject ?: $enquiry->product?->name ?: 'General enquiry' }}</p>
                <span class="text-sm text-gray-500">{{ $enquiry->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $enquiry->message }}</p>
            <div class="mt-2 flex gap-3 text-xs text-gray-500">
                <span>{{ str($enquiry->enquiry_type)->replace('_', ' ')->title() }}</span>
                <span>{{ str($enquiry->status->value)->replace('_', ' ')->title() }}</span>
                @if ($enquiry->product_reference)
                    <span>Ref: {{ $enquiry->product_reference }}</span>
                @endif
            </div>
            @if ($enquiry->internal_notes)
                <div class="mt-3 border-t border-gray-200 pt-3 dark:border-gray-700">
                    <p class="text-xs font-semibold uppercase text-gray-500">Internal notes</p>
                    <p class="mt-1 whitespace-pre-line text-sm">{{ $enquiry->internal_notes }}</p>
                </div>
            @endif
        </article>
    @empty
        <p class="text-sm text-gray-500">No enquiries found for this customer.</p>
    @endforelse
</div>
