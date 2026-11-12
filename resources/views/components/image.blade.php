@props(['path', 'alt' => '', 'width' => null, 'height' => null, 'class' => '', 'loading' => 'lazy', 'priority' => false])

<?php
$image = app(\App\Services\ImageService::class);
$src = $image->url($path);
$dims = $image->dimensions($path);
$finalWidth = $width ?? $dims['width'];
$finalHeight = $height ?? $dims['height'];
$loadingAttr = $priority ? 'eager' : 'lazy';
?>

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    width="{{ $finalWidth }}"
    height="{{ $finalHeight }}"
    loading="{{ $loadingAttr }}"
    decoding="async"
    class="{{ $class }}"
    @if ($priority)
        fetchpriority="high"
    @endif
/>
