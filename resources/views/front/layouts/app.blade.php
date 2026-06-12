<!DOCTYPE html>
<html lang="hi-IN">
<head>
    <meta charset="UTF-8">

    @php
        $seo = $seo ?? null;

        $siteName = 'A7-SATTAFAST';

        $metaTitle = $seo->meta_title
            ?? 'A7 Satta King Result Today 2026 | A7 SattaFast Live Result Chart';

        $metaDescription = $seo->meta_description
            ?? 'A7 SattaFast par daily live result, satta king chart, record chart aur yearly result chart fast update ke saath dekhein.';

        $metaKeywords = $seo->meta_keywords
            ?? 'a7 satta, a7 satta king, a7 satta result, a7 satta fast, satta king result, satta chart, gali result, disawar result, faridabad result, ghaziabad result';

        $canonicalUrl = $seo->canonical_url ?? url()->current();

        $ogTitle = $seo->og_title ?? $metaTitle;
        $ogDescription = $seo->og_description ?? $metaDescription;

        $ogImage = !empty($seo?->og_image)
            ? (
                str_starts_with($seo->og_image, 'http')
                    ? $seo->og_image
                    : asset('storage/' . ltrim($seo->og_image, '/'))
            )
            : asset('A1.png');
    @endphp

    <title>{{ $metaTitle }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="google-site-verification" content="QO-2oh9N--tEzzpguKQYG592l_PY34GEfQaX8QTZyig">

    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="author" content="{{ $siteName }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="language" content="Hindi">
    <meta name="theme-color" content="#050505">
    <meta name="referrer" content="strict-origin-when-cross-origin">

    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="shortlink" href="{{ url('/') }}">

    <meta property="og:locale" content="hi_IN">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
    <meta property="og:image:alt" content="{{ $ogTitle }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="twitter:image:alt" content="{{ $ogTitle }}">

    <link rel="icon" type="image/png" href="{{ asset('A1.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('A1.png') }}">

    <link rel="preload"
          href="{{ asset('tamplate/bootstrap/assests1/bootstrap.css') }}"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">

    <link rel="preload"
          href="{{ asset('tamplate/bootstrap/assests1/style.css') }}"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">

    <noscript>
        <link rel="stylesheet" href="{{ asset('tamplate/bootstrap/assests1/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('tamplate/bootstrap/assests1/style.css') }}">
    </noscript>

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        body {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background: #ffffff;
            color: #000000;
            font-family: Arial, 'Noto Sans Devanagari', sans-serif;
            -webkit-text-size-adjust: 100%;
        }

        img,
        video,
        iframe,
        canvas,
        svg {
            max-width: 100%;
        }

        img {
            height: auto;
        }

        a {
            max-width: 100%;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        p {
            margin: 0;
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
        strong,
        b,
        td,
        th,
        li {
            overflow-wrap: anywhere;
        }

        table {
            max-width: 100%;
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

        .table-responsive {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            border: 0;
        }

        .table-responsive table,
        .month_result_table,
        .rtable {
            margin-bottom: 0;
            border-collapse: collapse;
        }

        .month_result_table {
            min-width: 680px;
        }

        .drag,
        .border,
        .resultchart,
        .addb,
        section {
            max-width: 100%;
        }

        .open {
            font-size: 35px;
        }

        .logo {
            height: 70px;
            width: 70px;
            border-radius: 50%;
            padding: 10px;
            object-fit: contain;
        }

        .text-black {
            color: #000000;
            font-size: 13px;
        }

        .text-blacks {
            color: #000000;
            font-size: 15px;
        }

        .time_result,
        .time {
            color: blue;
            font-size: 15px;
            font-weight: bold;
        }

        .kal {
            color: #000000;
            font-size: 17px;
        }

        .gamebox {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #dddddd;
        }

        .gameboxresult {
            font-size: 20px;
            font-weight: 800;
        }

        .rv-ad-wrap {
            width: 100%;
            max-width: 100%;
            margin: 12px auto;
            font-family: Arial, 'Noto Sans Devanagari', sans-serif;
            overflow-x: hidden;
        }

        .rv-ad-box {
            max-width: 100%;
            background: linear-gradient(180deg, #ffd900 0%, #fff8cf 100%);
            border: 3px dashed #e60000;
            border-radius: 16px;
            padding: 12px 10px;
            text-align: center;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .10);
        }

        .rv-ad-box,
        .rv-ad-box * {
            color: #111111 !important;
            font-size: 16px !important;
            font-weight: 700 !important;
            line-height: 1.45 !important;
            word-break: break-word;
        }

        .rv-ad-box h1,
        .rv-ad-box h2,
        .rv-ad-box h3,
        .rv-ad-box h4,
        .rv-ad-box h5,
        .rv-ad-box h6,
        .rv-ad-box p,
        .rv-ad-box div {
            margin: 4px 0 !important;
            font-size: 16px !important;
        }

        .rv-ad-title {
            font-size: 18px !important;
            font-weight: 800 !important;
        }

        .rv-ad-name {
            font-size: 19px !important;
            font-weight: 900 !important;
            color: #c9342d !important;
        }

        .rv-ad-purple {
            color: #9b59b6 !important;
            font-weight: 800 !important;
        }

        .rv-ad-img {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border-radius: 999px;
            padding: 5px 12px;
            margin-top: 8px;
            max-width: 100%;
        }

        .rv-ad-img img {
            width: auto;
            height: auto;
            max-height: 55px;
            max-width: 200px;
            object-fit: contain;
        }

        .rv-middle {
            background: linear-gradient(180deg, #111827, #1f2937);
            border: 3px dashed #ffd900;
        }

        .rv-middle,
        .rv-middle * {
            color: #ffffff !important;
        }

        .rv-middle .rv-ad-img img {
            max-height: 55px;
            max-width: 200px;
        }

        .rv-result-title {
            text-align: center;
            margin: 12px 0 8px;
            font-size: 20px;
            font-weight: 900;
            line-height: 1.35;
        }

        .rv-result-title a {
            text-decoration: none;
            color: #111111;
        }

        .rv-result-title span {
            color: #c9342d;
        }

        .a7-section {
            max-width: 100%;
            overflow-x: hidden;
        }

        .a7-section * {
            max-width: 100%;
        }

        @media (max-width: 767px) {
            .container,
            .container-fluid {
                width: 100%;
                padding-left: 6px;
                padding-right: 6px;
            }

            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            [class*="col-"] {
                padding-left: 0;
                padding-right: 0;
            }

            .open {
                font-size: 26px;
            }

            .logo {
                width: 58px;
                height: 58px;
                padding: 7px;
            }

            .gamebox {
                width: 50%;
                float: left;
                padding: 8px 4px;
                min-height: 118px;
            }

            .text-black {
                font-size: 12px;
            }

            .text-blacks {
                font-size: 13px;
                line-height: 1.25;
                display: inline-block;
            }

            .time_result,
            .time {
                font-size: 13px;
            }

            .kal {
                font-size: 14px;
            }

            .gameboxresult {
                font-size: 18px;
            }

            .month_result_table {
                min-width: 620px;
            }

            .rv-ad-wrap {
                margin: 10px auto;
            }

            .rv-ad-box {
                border-width: 3px;
                border-radius: 14px;
                padding: 10px 8px;
            }

            .rv-ad-box,
            .rv-ad-box * {
                font-size: 14px !important;
                line-height: 1.4 !important;
                font-weight: 700 !important;
            }

            .rv-ad-box h1,
            .rv-ad-box h2,
            .rv-ad-box h3,
            .rv-ad-box h4,
            .rv-ad-box h5,
            .rv-ad-box h6,
            .rv-ad-box p,
            .rv-ad-box div {
                font-size: 14px !important;
            }

            .rv-ad-title {
                font-size: 15px !important;
            }

            .rv-ad-name {
                font-size: 16px !important;
            }

            .rv-ad-img {
                padding: 4px 10px;
                margin-top: 6px;
            }

            .rv-ad-img img {
                max-height: 48px;
                max-width: 175px;
            }

            .rv-result-title {
                font-size: 16px;
            }
        }

        @media (max-width: 420px) {
            .container,
            .container-fluid {
                padding-left: 4px;
                padding-right: 4px;
            }

            .gamebox {
                padding: 7px 3px;
            }

            .text-blacks {
                font-size: 12.5px;
            }

            .month_result_table {
                min-width: 580px;
            }
        }
    </style>

    @yield('custom-styles')
    @stack('styles')

    @if(!empty($seo?->schema_markup))
        {!! $seo->schema_markup !!}
    @else
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "WebSite",
                "name": "{{ $siteName }}",
                "url": "{{ url('/') }}",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "{{ url('/') }}?s={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            }
        </script>

        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Organization",
                "name": "{{ $siteName }}",
                "url": "{{ url('/') }}",
                "logo": "{{ asset('A1.png') }}"
            }
        </script>
    @endif
</head>

<body>

    @include('front.layouts.header')

    <main id="main-content">
        @yield('content')
    </main>

    @include('front.layouts.footer')

    @yield('custom-script')
    @stack('scripts')

</body>
</html>