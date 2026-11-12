@props([
    'type' => 'gold',
    'href' => null,
    'text' => null,
    'class' => '',
])

@php
    $buttonClass = match ($type) {
        'gold' => 'btn-gold',
        'outline' => 'btn-outline',
        default => 'btn-gold',
    };
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $buttonClass }} {{ $class }}">{{ $text ?? 'Learn More' }}</a>
@else
    <button class="{{ $buttonClass }} {{ $class }}" type="button">{{ $text ?? 'Learn More' }}</button>
@endif
