<!DOCTYPE html>
<html lang="hi-IN">
<head>
    <meta charset="UTF-8">

    @php
        $seo = $seo ?? null;

        $defaultTitle = 'Real Satta King | Satta King Online Results Today';
        $defaultDescription = 'Check Real Satta King results, charts, and daily updates.';
        $defaultKeywords = 'real satta king, satta king, satta result, satta chart';
        $defaultCanonical = url()->current();
    @endphp

    <title>{{ $seo->meta_title ?? $defaultTitle }}</title>

    <meta name="description" content="{{ $seo->meta_description ?? $defaultDescription }}">
    <meta name="keywords" content="{{ $seo->meta_keywords ?? $defaultKeywords }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="robots" content="index, follow">

    <link rel="canonical" href="{{ $seo->canonical_url ?? $defaultCanonical }}">
    <link rel="shortlink" href="{{ url('/') }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seo->og_title ?? $seo->meta_title ?? $defaultTitle }}">
    <meta property="og:description" content="{{ $seo->og_description ?? $seo->meta_description ?? $defaultDescription }}">
    <meta property="og:url" content="{{ $seo->canonical_url ?? $defaultCanonical }}">

    @if(!empty($seo?->og_image))
        <meta property="og:image" content="{{ asset('storage/'.$seo->og_image) }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo->og_title ?? $seo->meta_title ?? $defaultTitle }}">
    <meta name="twitter:description" content="{{ $seo->og_description ?? $seo->meta_description ?? $defaultDescription }}">

    @if(!empty($seo?->og_image))
        <meta name="twitter:image" content="{{ asset('storage/'.$seo->og_image) }}">
    @endif

    <link rel="icon" type="image/png" href="/m/favicon-96x96.png" sizes="96x96">
    <link rel="manifest" href="/m/site.webmanifest">

    <link rel="stylesheet" href="/tamplate/css/bootstrap.min.css">
    <link href="/tamplate/css/bootstrap-theme.css" type="text/css" rel="stylesheet">

    <style>
        .footer_white {
            background: black;
            color: white;
        }

        .footer_h4 {
            text-align: center;
            padding: 10px;
            color: white;
        }

        body {
            overflow-x: hidden;
        }
    </style>

    
    @if(!empty($seo?->schema_markup))
    {!! $seo->schema_markup !!}
@endif

    
</head>

<body>
<div id="app">

    @include('front.layouts.nav')

    <main class="py-4">
        @yield('content')
    </main>

    @include('front.layouts.footer')

</div>
</body>
</html>