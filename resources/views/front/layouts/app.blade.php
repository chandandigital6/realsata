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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">

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
        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, 'Noto Sans Devanagari', sans-serif;
            background: #fff;
            color: #000;
        }

        #app {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            min-height: 100vh;
        }

        main {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        img,
        video,
        iframe,
        table {
            max-width: 100%;
        }

        img {
            height: auto;
        }

        a {
            word-break: break-word;
        }

        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        div,
        span,
        td,
        th {
            overflow-wrap: anywhere;
        }

        .container,
        .container-fluid {
            max-width: 100%;
        }

        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
        }

        [class*="col-"] {
            padding-left: 8px;
            padding-right: 8px;
        }

        .nopadding {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .table-responsive {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border: 0;
        }

        .table-responsive table {
            margin-bottom: 0;
            white-space: normal;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
            word-break: break-word;
        }

        .waitimg img {
            max-width: 42px;
            height: auto;
        }

        .footer_white {
            background: #000;
            color: #fff;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        .footer_h4 {
            text-align: center;
            padding: 10px;
            color: #fff;
        }

        .mobile-safe-section {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        .content-heading {
            display: block;
            width: 100%;
            padding: 12px 16px;
            text-align: center;
            font-size: clamp(22px, 6vw, 36px);
            font-weight: 700;
            color: #000;
            line-height: 1.35;
            background-color: #FFAB00;
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            margin: 20px 0;
        }

        .content-para {
            padding-left: 16px;
            padding-right: 16px;
            color: #000;
            font-size: 15px;
            font-weight: 500;
            letter-spacing: 0.2px;
            line-height: 1.7;
            max-width: 100%;
        }

        @media (max-width: 767px) {
            main {
                padding-top: 6px;
                padding-bottom: 6px;
            }

            .container,
            .container-fluid {
                padding-left: 6px;
                padding-right: 6px;
            }

            [class*="col-"] {
                padding-left: 0;
                padding-right: 0;
            }

            .table {
                font-size: 13px;
            }

            .table th,
            .table td {
                padding: 6px 4px !important;
            }

            .tablebox1 .table {
                min-width: 360px;
            }

            .newtable .table {
                min-width: 720px;
            }

            .gamenameeach {
                font-size: 13px !important;
                line-height: 1.25;
                display: inline-block;
                max-width: 100%;
            }

            .time {
                font-size: 11px !important;
            }

            .content-heading {
                padding: 10px 8px;
                font-size: 22px;
                line-height: 1.3;
                margin: 14px 0;
            }

            .content-para {
                padding-left: 10px;
                padding-right: 10px;
                font-size: 14px;
                line-height: 1.65;
            }

            .circlebox,
            .liveresult,
            .sattaname,
            .sattaresult,
            .octoberresultchart,
            .tablebox1,
            .newtable {
                width: 100%;
                max-width: 100%;
                overflow-x: hidden;
            }

            .sattaname p,
            .sattaresult,
            .hintext {
                max-width: 100%;
                overflow-wrap: anywhere;
            }
        }

        @media (max-width: 420px) {
            .table {
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 5px 3px !important;
            }

            .content-heading {
                font-size: 20px;
            }

            .content-para {
                font-size: 13.5px;
            }
        }
    </style>

    @yield('custom-styles')

    @if(!empty($seo?->schema_markup))
        {!! $seo->schema_markup !!}
    @endif
</head>

<body>
<div id="app">

    @include('front.layouts.nav')

    <main>
        @yield('content')
    </main>

    @include('front.layouts.footer')

</div>

@yield('custom-script')

</body>
</html>