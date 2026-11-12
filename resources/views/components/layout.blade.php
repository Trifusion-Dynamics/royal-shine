<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (isset($seoTitle))
        <title>{{ $seoTitle }} - {{ app(\App\Services\SettingsService::class)->get('site_name', 'Royal Shine') }}</title>
    @else
        <title>{{ app(\App\Services\SettingsService::class)->get('site_name', 'Royal Shine') }}</title>
    @endif

    @if (isset($metaDescription))
        <meta name="description" content="{{ $metaDescription }}">
    @endif

    @if (isset($canonicalUrl))
        <link rel="canonical" href="{{ $canonicalUrl }}">
    @endif

    @php
        $ogTitle = $ogTitle ?? $seoTitle ?? null;
        $ogDescription = $ogDescription ?? $metaDescription ?? null;
    @endphp
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ app(\App\Services\SettingsService::class)->get('site_name', 'Royal Shine') }}">
    <meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">
    @if ($ogTitle)
        <meta property="og:title" content="{{ $ogTitle }}">
    @endif
    @if ($ogDescription)
        <meta property="og:description" content="{{ $ogDescription }}">
    @endif
    @if (isset($ogImage))
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="{{ app(\App\Services\ImageService::class)->url(app(\App\Services\SettingsService::class)->get('favicon')) }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

    @include('partials.header')

    <main class="min-h-screen">
        {{ $slot }}
    </main>

    @include('partials.footer')

</body>
</html>
