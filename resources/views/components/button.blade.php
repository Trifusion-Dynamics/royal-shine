@props([
    'type' => 'gold',
    'href' => null,
    'text' => null,
    'class' => '',
    'size' => 'md',
])

@php
    $base = 'btn font-sans rounded-none transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2';
    $focusRing = 'focus:ring-gold focus:ring-offset-ivory';
    $variant = match ($type) {
        'gold' => 'btn-gold',
        'brown' => 'btn-brown',
        'outline' => 'btn-outline',
        'text' => 'btn-text',
        default => 'btn-gold',
    };
    $sizeClass = match ($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => '',
    };
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $base }} {{ $variant }} {{ $sizeClass }} {{ $focusRing }} {{ $class }}">{{ $text ?? 'Learn More' }}</a>
@else
    <button class="{{ $base }} {{ $variant }} {{ $sizeClass }} {{ $focusRing }} {{ $class }}" type="button">{{ $text ?? 'Learn More' }}</button>
@endif
