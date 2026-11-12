<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $settingsService = app(\App\Services\SettingsService::class);
        $siteName = $settingsService->get('site_name', 'Royal Shine');
        $resolvedTitle = isset($seoTitle) && $seoTitle ? $seoTitle . ' - ' . $siteName : $siteName;
        $resolvedDescription = $metaDescription ?? $settingsService->get('site_description', 'Explore jewellery collections and enquire directly with Royal Shine.');
        $resolvedCanonical = $canonicalUrl ?? url()->current();
        $organizationData = [
            '@context' => 'https://schema.org',
            '@type' => 'JewelryStore',
            'name' => $siteName,
            'url' => config('app.url'),
        ];
        $phone = $settingsService->get('phone');
        $address = $settingsService->get('address');
        $socialProfiles = array_values(array_filter([
            $settingsService->get('social_facebook'),
            $settingsService->get('social_instagram'),
            $settingsService->get('social_twitter'),
            $settingsService->get('social_youtube'),
            $settingsService->get('social_pinterest'),
        ]));
        $defaultOgImage = $settingsService->get('default_og_image');
        $ogImage = $ogImage ?? ($defaultOgImage ? app(\App\Services\ImageService::class)->url($defaultOgImage) : null);
        if ($phone) $organizationData['telephone'] = $phone;
        if ($address) $organizationData['address'] = $address;
        if ($socialProfiles) $organizationData['sameAs'] = $socialProfiles;
    @endphp
    <title>{{ $resolvedTitle }}</title>
    <meta name="description" content="{{ $resolvedDescription }}">
    <meta name="robots" content="{{ request()->routeIs('search') ? 'noindex,follow' : 'index,follow' }}">
    <link rel="canonical" href="{{ $resolvedCanonical }}">

    @php
        $ogTitle = $ogTitle ?? ($seoTitle ?? $siteName);
        $ogDescription = $ogDescription ?? $resolvedDescription;
    @endphp
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:url" content="{{ $resolvedCanonical }}">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    @if (isset($ogImage))
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    @if (isset($ogImage))
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif
    <script type="application/ld+json">{!! json_encode($organizationData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
    @if (isset($structuredData))
        <script type="application/ld+json">{!! json_encode($structuredData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
    @endif

    <link rel="icon" href="{{ app(\App\Services\ImageService::class)->url($settingsService->get('favicon')) }}">

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
