@props(['path', 'alt' => '', 'width' => null, 'height' => null, 'class' => '', 'loading' => 'lazy', 'priority' => false, 'sizes' => null])

<?php
$image = app(\App\Services\ImageService::class);

// Get optimized URLs
$avifSrc = $image->url($path, 'avif');
$webpSrc = $image->url($path, 'webp');
$originalSrc = $image->url($path);

// Get dimensions
$dims = $image->dimensions($path);
$finalWidth = $width ?? $dims['width'];
$finalHeight = $height ?? $dims['height'];
$loadingAttr = $priority ? 'eager' : 'lazy';
?>

<picture>
    @if ($avifSrc !== $originalSrc)
        <source srcset="{{ $avifSrc }}" type="image/avif" width="{{ $finalWidth }}" height="{{ $finalHeight }}" {{ $sizes ? 'sizes="' . $sizes . '"' : '' }}>
    @endif
    @if ($webpSrc !== $originalSrc && $webpSrc !== $avifSrc)
        <source srcset="{{ $webpSrc }}" type="image/webp" width="{{ $finalWidth }}" height="{{ $finalHeight }}" {{ $sizes ? 'sizes="' . $sizes . '"' : '' }}>
    @endif
    <img
        src="{{ $originalSrc }}"
        alt="{{ $alt }}"
        width="{{ $finalWidth }}"
        height="{{ $finalHeight }}"
        loading="{{ $loadingAttr }}"
        decoding="async"
        {{ $sizes ? 'sizes="' . $sizes . '"' : '' }}
        class="{{ $class }}"
        @if ($priority)
            fetchpriority="high"
        @endif
    />
</picture>
