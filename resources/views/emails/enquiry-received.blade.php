@component('mail::message')
# {{ $enquiry->enquiry_type === 'contact' ? 'New Contact Message' : 'New Jewellery Enquiry' }}

A customer has submitted a jewellery enquiry on Royal Shine.

## Customer Details
- **Name:** {{ $enquiry->name }}
- **Mobile:** {{ $enquiry->mobile }}
- @if ($enquiry->email)
- **Email:** {{ $enquiry->email }}
@endif
- **Enquiry ID:** #{{ $enquiry->id }}
- **Subject:** {{ $enquiry->subject ?? 'Product enquiry' }}
- **Submitted:** {{ $enquiry->created_at->format('d M Y, h:i A') }}

@if ($enquiry->product)
## Product
- **Name:** {{ $enquiry->product->name }}
- **SKU:** {{ $enquiry->product->sku }}
@else
## Product Reference
{{ $enquiry->product_reference ?? 'Not specified' }}
@endif

## Message
{{ $enquiry->message }}

---

[View in Admin Panel]({{ config('app.url') }}/admin/enquiries/{{ $enquiry->id }})

@component('mail::footer', ['url' => config('app.url'), 'app_name' => config('app.name')])
© {{ now()->year }} {{ config('app.name') }}. All rights reserved.
@endcomponent
@endcomponent
