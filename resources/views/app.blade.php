<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title inertia>{{ config('app.name', 'Kanojo') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('icons/icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('icons/icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('icons/icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('icons/icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('icons/icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('icons/icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('icons/icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('icons/icon-167x167.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/icon-180x180.png') }}">
    <link rel="apple-touch-icon" sizes="1024x1024" href="{{ asset('icons/icon-1024x1024.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icons/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="228x228" href="{{ asset('icons/coast-228x228.png') }}">
    <link rel="shortcut icon" href="{{ asset('icons/favicon.ico') }}">
    <meta name="msapplication-TileColor" content="#d6d3d1">
    <meta name="msapplication-TileImage" content="{{ asset('icons/icon-144x144.png') }}">
    <meta name="theme-color" content="#6a306d">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead

    <script type="application/ld+json">
        {
            "@context": "https://schema.org/",
            "@type": "WebSite",
            "name": "Kanojo",
            "url": "https://kanojodb.com",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "https://kanojodb.com/search?q={query}",
                "query": "required",
                "query-input": "required name=query"
            }
        }
    </script>
</head>

<body>
    @inertia
</body>

</html>
