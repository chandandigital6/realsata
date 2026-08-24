@extends('front.layouts.app', ['seo' => $seo ?? null])

@section('content')

    <style>
        .rv-ad-wrap {
            width: 100%;
            margin: 12px auto;
            font-family: Arial, 'Noto Sans Devanagari', sans-serif;
        }

        .rv-ad-box {
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
            color: #111 !important;
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


        .rv-ad-heading {
            display: block !important;
            margin: 4px 0 !important;
            padding: 0 !important;
            color: #111 !important;
            font-size: 16px !important;
            font-weight: 700 !important;
            line-height: 1.45 !important;
            text-align: center !important;
        }

        .rv-ad-img {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
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
            background: linear-gradient(180deg, #ffd900, #d5e70a);
            border: 3px dashed #120f01;
        }

        .rv-middle,
        .rv-middle * {
            color: #000000 !important;
        }

        .rv-middle .rv-ad-img img {
            max-height: 55px;
            max-width: 200px;
        }

        @media(max-width:640px) {
            .rv-ad-wrap {
                margin: 10px auto;
            }

            @media(max-width:640px) {
                .rv-ad-heading {
                    font-size: 14px !important;
                    line-height: 1.4 !important;
                    font-weight: 700 !important;
                }
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

            .rv-ad-img {
                padding: 4px 10px;
                margin-top: 6px;
            }

            .rv-ad-img img {
                max-height: 48px;
                max-width: 175px;
            }
        }
    </style>



    {{-- Top Advertisements --}}
    @if ($topAdvertisements->count())
        @foreach ($topAdvertisements as $advertisement)
            <section class="rv-ad-wrap">
                <a href="{{ $advertisement->link ?: 'javascript:void(0)' }}"
                    @if (!empty($advertisement->link)) target="_blank" @endif style="text-decoration:none;color:inherit;">
                    <div class="rv-ad-box">
                        @if (!empty($advertisement->content))
                            <div>{!! $advertisement->content !!}</div>
                        @endif

                        @if (!empty($advertisement->image))
                            <span class="rv-ad-img">
                                {{-- <img src="{{ asset('storage/' . $advertisement->image) }}"
                                    alt="{{ $advertisement->title ?? 'Advertisement' }}" class="lazy" width="139"
                                    height="48"> --}}

                                <img src="{{ asset('storage/' . $advertisement->image) }}"
                                    alt="{{ $advertisement->title ?? 'Advertisement' }}" class="lazy" width="139"
                                    height="48" loading="eager" decoding="async" fetchpriority="high">
                            </span>
                        @endif
                    </div>
                </a>
            </section>
        @endforeach
    @endif






    {{-- upper live result --}}
    <section class="circlebox">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="liveresult">

                    <div class="datetime">
                        <div id="clockbox"></div>
                    </div>

                    <p class="hintext">हा भाई यही आती हे सबसे पहले खबर रूको और देखो</p>

                    @forelse($liveGames as $game)
                        @php
                            $todayResult = $todayResults[$game->id] ?? null;
                        @endphp

                        <div class="sattaname">
                            <p>{{ strtoupper($game->name) }}</p>
                        </div>

                        <div class="sattaresult">
                            <font>
                                <span>
                                    @if ($todayResult && filled($todayResult->result))
                                        {{ $todayResult->result }}
                                    @else
                                        <p>
                                            <strong class="waitimg">
                                                {{-- <img class="lazy" src="{{ asset('m/d.gif') }}" alt="waiting"
                                                    width="40" height="40"> --}}
                                                <img class="lazy" src="{{ asset('m/d.gif') }}" alt="waiting"
                                                    width="40" height="40" loading="lazy" decoding="async">
                                            </strong>
                                        </p>
                                    @endif
                                </span>
                            </font>
                        </div>
                    @empty
                        <div class="sattaname">
                            <p>No Games Found</p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </section>







    {{-- Middle Advertisement --}}
    <section class="rv-ad-wrap">
        <div class="rv-ad-box rv-middle">
            @if (!empty($middleAdvertisement))
                @if (!empty($middleAdvertisement->content))
                    <div>{!! $middleAdvertisement->content !!}</div>
                @else
                    <span class="rv-ad-heading">
                        व्हाट्सएप पर सुपर फास्ट रिजल्ट देखने के लिए नीचे दिए गए लिंक पर जाएं और चैनल को फॉलो करें।
                    </span>
                @endif

                <a href="{{ $middleAdvertisement->link ?: 'javascript:void(0)' }}"
                    @if (!empty($middleAdvertisement->link)) target="_blank" @endif style="text-decoration:none;">
                    <span class="rv-ad-img">
                        @if (!empty($middleAdvertisement->image))
                            {{-- <img src="{{ asset('storage/' . $middleAdvertisement->image) }}"
                                alt="{{ $middleAdvertisement->title ?? 'Join WhatsApp' }}" class="lazy" width="159"
                                height="55"> --}}
                            <img src="{{ asset('storage/' . $middleAdvertisement->image) }}"
                                alt="{{ $middleAdvertisement->title ?? 'Join WhatsApp' }}" class="lazy" width="159"
                                height="55" loading="lazy" decoding="async">
                        @else
                            {{-- <img src="{{ asset('Join-WhatsApp.png') }}" alt="Join WhatsApp" class="lazy" width="159"
                                height="55"> --}}

                            <img src="{{ asset('Join-WhatsApp.png') }}" alt="Join WhatsApp" class="lazy" width="159"
                                height="55" loading="lazy" decoding="async">
                        @endif
                    </span>
                </a>
            @else
                <span class="rv-ad-heading">
                    व्हाट्सएप पर सुपर फास्ट रिजल्ट देखने के लिए नीचे दिए गए लिंक पर जाएं और चैनल को फॉलो करें।
                </span>

                <a href="https://whatsapp.com/channel/0029Vb67katLikgE57Pwhj0T" style="text-decoration:none;">
                    <span class="rv-ad-img">
                        {{-- <img src="{{ asset('Join-WhatsApp.png') }}" alt="Join WhatsApp" class="lazy" width="159"
                            height="55"> --}}
                        <img src="{{ asset('Join-WhatsApp.png') }}" alt="Join WhatsApp" class="lazy" width="159"
                            height="55" loading="lazy" decoding="async">
                    </span>
                </a>
            @endif
        </div>
    </section>


    {{-- Bottom Advertisement --}}
    @php
        $hasBottomAd =
            !empty($bottomAdvertisement) &&
            (!empty($bottomAdvertisement->content) ||
                !empty($bottomAdvertisement->image) ||
                !empty($bottomAdvertisement->link));
    @endphp

    @if ($hasBottomAd)
        <section class="rv-ad-wrap">
            <a href="{{ $bottomAdvertisement->link ?: 'javascript:void(0)' }}"
                @if (!empty($bottomAdvertisement->link)) target="_blank" @endif style="text-decoration:none;color:inherit;">
                <div class="rv-ad-box">
                    @if (!empty($bottomAdvertisement->content))
                        <div>{!! $bottomAdvertisement->content !!}</div>
                    @endif

                    @if (!empty($bottomAdvertisement->image))
                        <span class="rv-ad-img">
                            {{-- <img src="{{ asset('storage/' . $bottomAdvertisement->image) }}"
                                alt="{{ $bottomAdvertisement->title ?? 'Advertisement' }}" class="lazy" width="138"
                                height="48"> --}}
                            <img src="{{ asset('storage/' . $bottomAdvertisement->image) }}"
                                alt="{{ $bottomAdvertisement->title ?? 'Advertisement' }}" class="lazy" width="138"
                                height="48" loading="lazy" decoding="async">
                        </span>
                    @endif
                </div>
            </a>
        </section>
    @endif


    {{-- Sidebar Advertisement --}}
    @php
        $hasSidebarAd =
            !empty($sidebarAdvertisement) &&
            (!empty($sidebarAdvertisement->content) ||
                !empty($sidebarAdvertisement->image) ||
                !empty($sidebarAdvertisement->link));
    @endphp

    @if ($hasSidebarAd)
        <section class="rv-ad-wrap">
            <a href="{{ $sidebarAdvertisement->link ?: 'javascript:void(0)' }}"
                @if (!empty($sidebarAdvertisement->link)) target="_blank" @endif style="text-decoration:none;color:inherit;">
                <div class="rv-ad-box">
                    @if (!empty($sidebarAdvertisement->content))
                        <div>{!! $sidebarAdvertisement->content !!}</div>
                    @endif

                    @if (!empty($sidebarAdvertisement->image))
                        <span class="rv-ad-img">
                            {{-- <img src="{{ asset('storage/' . $sidebarAdvertisement->image) }}"
                                alt="{{ $sidebarAdvertisement->title ?? 'Advertisement' }}" class="lazy"> --}}
                            <img src="{{ asset('storage/' . $sidebarAdvertisement->image) }}"
                                alt="{{ $sidebarAdvertisement->title ?? 'Advertisement' }}" class="lazy" width="138"
                                height="48" loading="lazy" decoding="async">
                        </span>
                    @endif
                </div>
            </a>
        </section>
    @endif


    <br>


    {{-- today/yesterday table - 17 games per section --}}
    @foreach ($gameSections as $sectionIndex => $sectionGames)
        <section class="tablebox1 {{ $sectionIndex > 0 ? 'mt-4 mb-4' : 'mb-4' }}">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 nopadding">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="forblack">
                                    <tr>
                                        <th class="col-md-4 text-center">सट्टा का नाम</th>
                                        <th class="col-md-4 text-center">कल आया था</th>
                                        <th class="col-md-4 text-center">आज का रिज़ल्ट</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($sectionGames as $game)
                                        @php
                                            $yesterdayResult = $yesterdayResults[$game->id] ?? null;
                                            // $todayResult = $todayResults[$game->id] ?? null;
                                            $todayResult = $todayTableResults[$game->id] ?? null;
                                        @endphp

                                        <tr style="height:36px">
                                            <td class="foryellow">
                                                {{-- <a href="{{ route('game.record', ['slug' => $game->slug ?? ($game->url ?? $game->id)]) }}"
                                               target="_blank"
                                               class="gamenameeach">
                                                {{ strtoupper($game->name) }}
                                            </a> --}}

                                                <a href="{{ route('game.record', ['slug' => $game->slug ?? ($game->url ?? $game->id)]) }}"
                                                    target="_blank" class="gamenameeach !text-[17px]">
                                                    {{ strtoupper($game->name) }}
                                                </a>

                                                <br>

                                                @if (!empty($game->result_time))
                                                    <span class="time">
                                                        {{ \Carbon\Carbon::parse($game->result_time)->format('h:i A') }}
                                                    </span>
                                                @endif

                                                <br>

                                                {{-- <a style="font-size:12px;color:#000000;"
                                               target="_blank"
                                               href="{{ route('game.record', ['slug' => $game->slug ?? ($game->url ?? $game->id)]) }}">
                                                Record Chart
                                            </a> --}}
                                            </td>

                                            <td class="text-center">
                                                @if ($yesterdayResult && filled($yesterdayResult->result))
                                                    {{ str_pad($yesterdayResult->result, 2, '0', STR_PAD_LEFT) }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($todayResult && filled($todayResult->result))
                                                    {{ str_pad($todayResult->result, 2, '0', STR_PAD_LEFT) }}
                                                @else
                                                    <p>
                                                        <strong class="waitimg">
                                                            {{-- <img class="lazy" alt="waiting" src="{{ asset('m/d.gif') }}"
                                                                alt="waiting" class="lazy" width="40" height="40"> --}}
                                                            <img class="lazy" src="{{ asset('m/d.gif') }}"
                                                                alt="waiting" width="40" height="40"
                                                                loading="lazy" decoding="async">
                                                        </strong>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <p class="mt-3">Don't have any data</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if (!$loop->last)
            <div style="height:18px;"></div>
        @endif
    @endforeach







    {{-- gap between result and chart --}}
<div style="height:35px;"></div>

{{-- monthly chart heading --}}
<section class="octoberresultchart" style="padding:10px 8px;background:#f5f5f5;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center font-size-30 forfirtcolor"
                 style="background:#f5004f;color:#fff;border:2px solid #fff;border-radius:8px;padding:10px 6px;font-weight:800;font-size:24px;box-shadow:0 4px 10px rgba(0,0,0,0.18);">
                <span id="date"></span>
            </div>
        </div>
    </div>
</section>

{{-- monthly chart - 17 games per chart section --}}
@foreach ($chartGameSections as $chartIndex => $sectionChartGames)
    <section class="newtable {{ $chartIndex > 0 ? 'mt-4 mb-4' : 'mb-4' }}"
             style="padding:8px;background:#f5f5f5;">
        <div class="container-fluid" style="padding-left:6px;padding-right:6px;">
            <div class="row">
                <div class="col-md-12 nopadding">
                    <div class="table-responsive"
                         style="border:2px solid #222;border-radius:10px;overflow:auto;background:#fff;box-shadow:0 4px 14px rgba(0,0,0,0.15);max-width:100%;">

                        <table class="table table-bordered"
                               style="margin-bottom:0;border-collapse:collapse;width:100%;min-width:900px;background:#fff;">
                            <thead class="p-0">
                                <tr>
                                    <th class="table_chart_section_01 col-md-2 text-center forfirtcolor"
                                        style="background:#ffb300;color:#000;border:1px solid #222;padding:10px 8px;font-size:14px;font-weight:900;position:sticky;left:0;z-index:3;min-width:70px;">
                                        <strong class="fon">Date</strong>
                                    </th>

                                    @foreach ($sectionChartGames as $game)
                                        <th class="table_chart_section_01 col-md-2 text-center forfirtcolor"
                                            style="background:#ffb300;color:#000;border:1px solid #222;padding:10px 8px;font-size:13px;font-weight:900;white-space:nowrap;min-width:95px;">
                                            <strong class="fon">{{ strtoupper($game->name) }}</strong>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody class="colorchange">
                                @foreach ($dates as $date)
                                    @php
                                        $dateKey = $date->format('Y-m-d');
                                        $dayResults = $monthlyResults[$dateKey] ?? collect();
                                        $isToday = $date->format('Y-m-d') === now('Asia/Kolkata')->format('Y-m-d');
                                    @endphp

                                    <tr style="{{ $isToday ? 'background:#fff7d6;' : '' }}">
                                        <td class="text-center forfirtcolor"
                                            style="background:#ffb300;color:#000;border:1px solid #222;padding:9px 6px;font-size:14px;font-weight:900;position:sticky;left:0;z-index:2;min-width:70px;">
                                            {{ $date->format('d') }}
                                        </td>

                                        @foreach ($sectionChartGames as $game)
                                            @php
                                                $result = $dayResults->firstWhere('game_id', $game->id);
                                            @endphp

                                            <td class="text-center"
                                                style="border:1px solid #333;padding:9px 6px;font-size:14px;font-weight:700;color:#000;background:{{ $isToday ? '#fff7d6' : '#fff' }};">
                                                @if ($result && $result->status === 'declared' && filled($result->result))
                                                    <b style="display:inline-block;background:#111;color:#fff;border-radius:4px;padding:2px 7px;min-width:28px;">
                                                        {{ str_pad($result->result, 2, '0', STR_PAD_LEFT) }}
                                                    </b>
                                                @else
                                                    <span style="color:#555;">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- <div style="font-size:12px;color:#555;text-align:center;padding:6px 0 0;font-weight:600;">
                        मोबाइल पर पूरा चार्ट देखने के लिए left-right scroll करें
                    </div> --}}
                </div>
            </div>
        </div>
    </section>

    @if (!$loop->last)
        <div style="height:25px;"></div>
    @endif
@endforeach


  <section>

    <h1
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>Online Khaiwal – Record Charts, and Latest Results Update</strong>
    </h1>
    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        Khaiwal is a widely recognized term associated with a traditional game known in various regions, and in recent times, its online versions have gained significant attention. This guide provides a thorough overview of Khaiwal, including its online presence, various keyword trends, record charts, and popular markets related to it. Readers will also find key information about legal aspects and common queries surrounding Khaiwal. Please note this content is strictly informational and does not promote or endorse gambling activities.
    </p>

    <h2
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>What is Khaiwal?</strong>
    </h2>

    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        Khaiwal is a term linked to an intricate form of betting game, often overlapping in public discourse with "Satta Matka," a classic lottery-style game. While Khaiwal shares many characteristics with satta formats, such as relying on number selection and chance, its origins and cultural footholds differ slightly. It has evolved into various formats including online khaiwal, making it accessible beyond physical locations. The terms satta khaiwal, khaiwal satta king, and satta king khaiwal frequently appear in searches reflecting its popular variants and reputed communities engaged in the game. People often seek Khaiwal to track results and explore the dynamics of online participation.
    </p>

    <h2
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>Is Online Khaiwal Legal in India?</strong>
    </h2>

    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        In India, gambling laws vary by state, and most forms of lottery and betting, including many associated with Khaiwal, fall under regulatory restrictions. Online Khaiwal, like many other online betting platforms, exists in a legal gray area and may be subject to regional bans. Engaging in such games carries risks, including legal consequences and financial loss. Users should prioritize responsible behavior and rely on verified legal information. This content aims solely to inform, without encouraging participation in gambling.
    </p>

    <h2
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>Common Terms Used in Online Khaiwal</strong>
    </h2>

    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        • <strong>Jodi:</strong> A pair of numbers often used to predict outcomes or results in the game.<br>
        • <strong>Haruf:</strong> A letter associated with certain numbers, forming part of the game's terminology.<br>
        • <strong>Ander:</strong> Refers to the 'inside' number or bet segment in Khaiwal.<br>
        • <strong>Bahar:</strong> Denotes the 'outside' number or bet segment complementing Ander.<br>
        • <strong>Munda:</strong> The ‘head’ or starting number in a series significant to game predictions.<br>
        • <strong>Record Chart:</strong> A systematic collection of past results used as a reference to analyze patterns.
    </p>

    <h2
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>What is an Online Khaiwal Record Chart?</strong>
    </h2>

    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        An online Khaiwal record chart is a compiled document that archives daily, monthly, and yearly results from various Khaiwal games. These charts include detailed breakdowns such as mixed charts combining data from several games and single-game charts focusing on specific ones. They serve as historical records that players and enthusiasts refer to for tracking trends. Keywords like online khaiwal all game, online satta khaiwal, online khaiwal satta, and online khaiwal satta king commonly relate to these charts, highlighting their comprehensive nature in offering accessible data across different variants.
    </p>

    <h2
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>Popular Khaiwal Markets</strong>
    </h2>

    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        Several regional markets are notable for active Khaiwal participation and tracking:
        <br><br>
        • <strong>Delhi:</strong> A major city where Khaiwal has a substantial following among enthusiasts.<br>
        • <strong>Gali:</strong> Known for its traditional market systems incorporating various number games.<br>
        • <strong>Desawar:</strong> A popular market famous for its distinctive results in the game.<br>
        • <strong>Ghaziabad:</strong> Another key center with a vibrant community engaging in Khaiwal.<br>
        • <strong>Faridabad:</strong> Recognized for its unique draw timings and local players.<br>
        • <strong>Shri Ganesh:</strong> A market often linked with auspicious betting activities.<br>
        • <strong>Hindustan:</strong> Represents a broad geographic interest encompassing multiple formats.
    </p>

    <h2
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>Frequently Searched Khaiwal Queries</strong>
    </h2>

    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        Many users search using terms like khaiwal online play, online khaiwal whatsapp number, satta khaiwal contact number, rajkot satta online khaiwal, rajkot satta khaiwal contact number, and king online khaiwal. It is important to clarify that this information is often sought by the public but, due to legal restrictions and privacy concerns, official contact or WhatsApp numbers are not publicly available through verified sources. Users are advised to exercise caution and rely only on trustworthy, lawful channels for any inquiries.
    </p>

    <h2
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>Frequently Asked Questions</strong>
    </h2>

    <h3
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>What is Khaiwal?</strong>
    </h3>
    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        Khaiwal is a traditional game related to number betting, linked to but distinct from the broader Satta Matka forms.
    </p>

    <h3
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>What is Online Khaiwal?</strong>
    </h3>
    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        Online Khaiwal refers to digital platforms that simulate or provide access to Khaiwal games through the internet.
    </p>

    <h3
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>Is Online Khaiwal legal in India?</strong>
    </h3>
    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        The legality varies by state, with most forms considered unauthorized under Indian gambling laws.
    </p>

    <h3
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>What is an Online Khaiwal record chart?</strong>
    </h3>
    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        It is a detailed compilation of historical game results used for analysis and reference.
    </p>

    <h3
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>What are the popular Khaiwal markets?</strong>
    </h3>
    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        Delhi, Gali, Desawar, Ghaziabad, Faridabad, Shri Ganesh, and Hindustan are notable markets where Khaiwal is widespread.
    </p>

    <h3
        style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
        <strong>Why do people search for Online Khaiwal?</strong>
    </h3>
    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        Many look for updates, record charts, and contact details out of curiosity or to follow game results online.
    </p>

    <p
        style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
        &nbsp;
    </p>
</section>

    </section>


@endsection

@section('custom-styles')
    <style>
        .advo p {
            line-height: 40px;
        }
    </style>
@endsection



@section('custom-script')
    <script>
        function MYDate() {
            const mydate = new Date();
            const month = mydate.getMonth();
            const year = mydate.getFullYear();

            const marr = [
                "JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE",
                "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"
            ];

            const dateBox = document.getElementById('date');

            if (dateBox) {
                dateBox.innerText = marr[month] + " RESULT CHART " + year;
            }
        }

        MYDate();
    </script>
@endsection
