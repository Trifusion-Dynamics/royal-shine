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

    @if (isset($ogTitle))
        <meta property="og:title" content="{{ $ogTitle }}">
    @endif
    @if (isset($ogDescription))
        <meta property="og:description" content="{{ $ogDescription }}">
    @endif
    @if (isset($ogImage))
        <meta property="og:image" content="{{ $ogImage }}">
    @endif

    <link rel="icon" href="{{ app(\App\Services\ImageService::class)->url(app(\App\Services\SettingsService::class)->get('favicon')) }}">

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
