@extends('front.layouts.app', ['seo' => $seo ?? null])

@section('content')

<style>
    .rv-ad-wrap{
        width:100%;
        margin:12px auto;
        font-family:Arial,'Noto Sans Devanagari',sans-serif;
    }

    .rv-ad-box{
        background:linear-gradient(180deg,#ffd900 0%,#fff8cf 100%);
        border:3px dashed #e60000;
        border-radius:16px;
        padding:12px 10px;
        text-align:center;
        overflow:hidden;
        box-shadow:0 4px 12px rgba(0,0,0,.10);
    }

    .rv-ad-box,
    .rv-ad-box *{
        color:#111!important;
        font-size:16px!important;
        font-weight:700!important;
        line-height:1.45!important;
        word-break:break-word;
    }

    .rv-ad-box h1,
    .rv-ad-box h2,
    .rv-ad-box h3,
    .rv-ad-box h4,
    .rv-ad-box h5,
    .rv-ad-box h6,
    .rv-ad-box p,
    .rv-ad-box div{
        margin:4px 0!important;
        font-size:16px!important;
    }

    .rv-ad-img{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        background:#fff;
        border-radius:999px;
        padding:5px 12px;
        margin-top:8px;
        max-width:100%;
    }

    .rv-ad-img img{
        width:auto;
        height:auto;
        max-height:55px;
        max-width:200px;
        object-fit:contain;
    }

    .rv-middle{
        background:linear-gradient(180deg,#ffd900,#d5e70a);
        border:3px dashed #ffd900;
    }

    .rv-middle,
    .rv-middle *{
        color:#000000!important;
    }

    .rv-middle .rv-ad-img img{
        max-height:55px;
        max-width:200px;
    }

    @media(max-width:640px){
        .rv-ad-wrap{
            margin:10px auto;
        }

        .rv-ad-box{
            border-width:3px;
            border-radius:14px;
            padding:10px 8px;
        }

        .rv-ad-box,
        .rv-ad-box *{
            font-size:14px!important;
            line-height:1.4!important;
            font-weight:700!important;
        }

        .rv-ad-box h1,
        .rv-ad-box h2,
        .rv-ad-box h3,
        .rv-ad-box h4,
        .rv-ad-box h5,
        .rv-ad-box h6,
        .rv-ad-box p,
        .rv-ad-box div{
            font-size:14px!important;
        }

        .rv-ad-img{
            padding:4px 10px;
            margin-top:6px;
        }

        .rv-ad-img img{
            max-height:48px;
            max-width:175px;
        }
    }
</style>



  {{-- Top Advertisements --}}
@if ($topAdvertisements->count())
    @foreach ($topAdvertisements as $advertisement)
        <section class="rv-ad-wrap">
            <a href="{{ $advertisement->link ?: 'javascript:void(0)' }}"
               @if (!empty($advertisement->link)) target="_blank" @endif
               style="text-decoration:none;color:inherit;">
                <div class="rv-ad-box">
                    @if (!empty($advertisement->content))
                        <div>{!! $advertisement->content !!}</div>
                    @endif

                    @if (!empty($advertisement->image))
                        <span class="rv-ad-img">
                            <img src="{{ asset('storage/' . $advertisement->image) }}"
                                 alt="{{ $advertisement->title ?? 'Advertisement' }}">
                        </span>
                    @endif
                </div>
            </a>
        </section>
    @endforeach
@endif



    {{-- upper live result --}}



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
                                            <img class="lazy"
                                                 src="{{ asset('m/d.gif') }}"
                                                 alt="waiting">
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
                <h4>व्हाट्सएप पर सुपर फास्ट रिजल्ट देखने के लिए नीचे दिए गए लिंक पर जाएं और चैनल को फॉलो करें।</h4>
            @endif

            <a href="{{ $middleAdvertisement->link ?: 'javascript:void(0)' }}"
               @if (!empty($middleAdvertisement->link)) target="_blank" @endif
               style="text-decoration:none;">
                <span class="rv-ad-img">
                    @if (!empty($middleAdvertisement->image))
                        <img src="{{ asset('storage/' . $middleAdvertisement->image) }}"
                             alt="{{ $middleAdvertisement->title ?? 'Join WhatsApp' }}">
                    @else
                        <img src="{{ asset('Join-WhatsApp.png') }}" alt="Join WhatsApp">
                    @endif
                </span>
            </a>
        @else
            <h4>व्हाट्सएप पर सुपर फास्ट रिजल्ट देखने के लिए नीचे दिए गए लिंक पर जाएं और चैनल को फॉलो करें।</h4>

            <a href="https://whatsapp.com/channel/0029Vb67katLikgE57Pwhj0T" style="text-decoration:none;">
                <span class="rv-ad-img">
                    <img src="{{ asset('Join-WhatsApp.png') }}" alt="Join WhatsApp">
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
           @if (!empty($bottomAdvertisement->link)) target="_blank" @endif
           style="text-decoration:none;color:inherit;">
            <div class="rv-ad-box">
                @if (!empty($bottomAdvertisement->content))
                    <div>{!! $bottomAdvertisement->content !!}</div>
                @endif

                @if (!empty($bottomAdvertisement->image))
                    <span class="rv-ad-img">
                        <img src="{{ asset('storage/' . $bottomAdvertisement->image) }}"
                             alt="{{ $bottomAdvertisement->title ?? 'Advertisement' }}">
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
           @if (!empty($sidebarAdvertisement->link)) target="_blank" @endif
           style="text-decoration:none;color:inherit;">
            <div class="rv-ad-box">
                @if (!empty($sidebarAdvertisement->content))
                    <div>{!! $sidebarAdvertisement->content !!}</div>
                @endif

                @if (!empty($sidebarAdvertisement->image))
                    <span class="rv-ad-img">
                        <img src="{{ asset('storage/' . $sidebarAdvertisement->image) }}"
                             alt="{{ $sidebarAdvertisement->title ?? 'Advertisement' }}">
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
                                        $todayResult = $todayResults[$game->id] ?? null;
                                    @endphp

                                    <tr style="height:36px">
                                        <td class="foryellow">
                                            <a href="{{ route('game.record', ['slug' => $game->slug ?? ($game->url ?? $game->id)]) }}"
                                               target="_blank"
                                               class="gamenameeach">
                                                {{ strtoupper($game->name) }}
                                            </a>

                                            <br>

                                            @if (!empty($game->result_time))
                                                <span class="time">
                                                    {{ \Carbon\Carbon::parse($game->result_time)->format('h:i A') }}
                                                </span>
                                            @endif

                                            <br>

                                            <a style="font-size:12px;color:#000000;"
                                               target="_blank"
                                               href="{{ route('game.record', ['slug' => $game->slug ?? ($game->url ?? $game->id)]) }}">
                                                Record Chart
                                            </a>
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
                                                        <img class="lazy" alt="waiting" src="{{ asset('m/d.gif') }}">
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
<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 id="date"></h2>
            </div>
        </div>
    </div>
</section>


{{-- monthly chart - 17 games per chart section --}}
@foreach ($chartGameSections as $chartIndex => $sectionChartGames)
    <section class="newtable {{ $chartIndex > 0 ? 'mt-4 mb-4' : 'mb-4' }}">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 nopadding">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="p-0">
                                <tr>
                                    <th class="table_chart_section_01 col-md-2 text-center forfirtcolor">
                                        <strong class="fon">Date</strong>
                                    </th>

                                    @foreach ($sectionChartGames as $game)
                                        <th class="table_chart_section_01 col-md-2 text-center forfirtcolor">
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
                                    @endphp

                                    <tr>
                                        <td class="text-center forfirtcolor">
                                            {{ $date->format('d') }}
                                        </td>

                                        @foreach ($sectionChartGames as $game)
                                            @php
                                                $result = $dayResults->firstWhere('game_id', $game->id);
                                            @endphp

                                            <td class="text-center">
                                                @if ($result && $result->status === 'declared' && filled($result->result))
                                                    <b>{{ str_pad($result->result, 2, '0', STR_PAD_LEFT) }}</b>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
            <strong>Real Satta King – Trusted Satta King Real & Online Results Platform</strong>
        </h1>
        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Welcome to Real Satta – India's Trusted Result Information Hub.
        </p>
        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>If you're someone who tracks Satta King results daily, you already know the frustration — jumping
                between
                five different websites, staring at slow-loading pages, and still not getting the result on time. Half the
                time,
                the number is wrong. The other half, the page hasn't even updated yet. It's exhausting.<br>
                That's exactly the problem Real Satta was built to solve.<br>
                This platform exists for one reason: to give you accurate, fast, and easy-to-read Satta King results for all
                major markets — every single day, without fail. Whether it's the early morning Disawar result or the
                late-night
                Gali update, the numbers go live on our site the moment they're declared. No delays, no excuses, no wrong
                numbers.<br>We cover everything from the big four — Gali, Disawar, Faridabad, and Ghaziabad — to regional
                markets like Prayagraj, Delhi Bazaar, Shri Ganesh, Noida Night, Roop Nagar, Palika Bazar, and dozens more.
                If
                there's a result to be tracked, Real Satta Bazar tracks it.<br>
                No clutter. No fake claims. Just the information you actually came for.
        </p>

        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Today's Real Satta King Live Result – 2026</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Results are updated throughout the day as each game is officially declared. You'll find today's live
                results
                alongside yesterday's numbers, so you can compare at a glance. We refresh the page the moment results are
                out —
                no manual refresh needed on your end.
                <br>
                We also cover Sadar Bazar, Gwalior, Agra, Prayagraj, Rampur, Alwar, Dehradun City, Chhattisgarh, Aligarh
                Night,
                Dwarka, Jeevan Shree, MeghaCity, and several more regional markets. Scroll down for the full result table.

        </p>

        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Popular Satta King Games — All You Need to Know</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            India has dozens of Satta markets running every single day, spread across different cities and time zones. But a
            handful of games have built a following that runs into the millions. These are the markets people have been
            tracking
            for years, and these are the ones we cover most closely on Real Satta Bazar.
            <br>
            Here's a game-by-game breakdown of what each one is and when results are declared:
            </strong>
        </p>


        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Gali Satta King Result</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Out of every Satta King game that runs in India, Gali Satta King is arguably the most talked about. It's
                been running for decades and has a massive following across Delhi, Uttar Pradesh, and Haryana. The result
                comes
                in late at night — around 11:50 PM — which means it's usually the last major update of the day for most
                people.
                <br>
                Because of the timing, the Gali result tends to carry a lot of anticipation. People who've been tracking
                results
                all day are usually still awake and watching for this one. We make sure the number is live on Real Satta the
                moment it's officially out.
                <br>
                Result Time: 11:50 PM daily

        </p>

        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Disawar Satta King Result</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Disawar is the grand old name of the Satta King world. Ask anyone who's been following these markets for
                years, and Disawar will be the first name they mention. The result comes in the very early hours of the
                morning
                — 2:00 AM — making it technically the first result of each new day.
                <br>
                The Disawar Satta King chart market has a long history and a deeply loyal following. Many regular trackers
                set
                an alarm specifically for this result. On Real Satta, we post it the moment it's declared so you don't have
                to
                wait around.
                <br>
                Result Time: 2:00 AM daily
            </strong>
        </p>

        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Faridabad Satta King Result</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Faridabad is one of the key evening games, declaring its result around 6:20 PM. It's particularly
                popular
                among followers in Haryana and the Delhi-NCR belt. The timing works out well for most people — it's after
                work
                hours, before dinner, and the result is usually out right when you'd naturally check your phone.
                <br>
                On Real Satta, Faridabad chart Result updates are among the most-viewed results of the evening alongside
                Ghaziabad.
                <br>
                Result Time: 6:20 PM daily
            </strong>
        </p>

        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Ghaziabad Satta King Result</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Ghaziabad runs a little later in the evening, with results coming in around 9:30 PM. It's widely
                followed
                across Uttar Pradesh, Delhi, and the surrounding areas. The 9:30 PM timing makes it a prime-time result —
                most
                people are home by then, and this is often the result they're waiting for before calling it a night.
                <br>
                Real Satta posts Ghaziabad results immediately, so you're never left guessing.
                <br>
                Result Time: 9:30 PM daily

            </strong>
        </p>



        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Delhi Bazaar Satta King Result</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Delhi Bazaar Result is an afternoon game, with results declared around 2:50 PM. It's a big one for
                followers
                based in Delhi and the NCR region. The afternoon timing gives it a mid-day significance — many people check
                this
                one during lunch or right after.
                <br>
                It's one of the first major afternoon markets to declare, which also makes it an important reference point
                for
                the rest of the day's results.
                <br>
                Result Time: 2:50 PM daily
            </strong>
        </p>

        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Shri Ganesh Satta King Result</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Shri Ganesh has built a steady following over the years, particularly in UP and Delhi. Results come in
                the
                late afternoon around 4:10 PM, filling the gap between the early afternoon markets and the big evening
                games. If
                you're tracking multiple games through the day, Shri Ganesh is usually the one that keeps things interesting
                between Delhi Bazaar and Faridabad.
                <br>
                Result Time: 4:10 PM daily
            </strong>
        </p>

        <h2
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Why So Many People Come Back to Real Satta Every Day</strong>
        </h2>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>There are dozens of websites showing Satta King results. So what's the actual reason people keep
                choosing
                Real Satta Bazar? It comes down to a few things that most other platforms consistently fail to get right.

            </strong>
        </p>

        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Results Go Live the Moment They're Declared</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Speed matters here. Nobody wants to check three websites and still not find the result. On Real Satta,
                results go live immediately after they're officially declared — day or night. The 2 AM Disawar result, the
                11:50
                PM Gali update, the 9:30 PM Ghaziabad number — all of them land on this site faster than almost anywhere
                else.

            </strong>
        </p>


        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Every Major Game Covered Under One Roof</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>You shouldn't have to visit five different pages to follow five different markets. Real Satta covers all
                the
                major games — Gali, Disawar, Faridabad, Ghaziabad, Delhi Bazaar, Delhi Darbar, Shri Ganesh, Noida Night,
                Roop
                Nagar, Palika Bazar, Fatehpur, Prayagraj, and many more regional markets — all on one page, in one clean
                result
                table.

            </strong>
        </p>
        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Clean Layout — No Clutter, No Pop-Ups</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>A lot of Satta result websites are packed with ads, blinking banners, random WhatsApp links, and pop-ups
                that hijack your screen. Real Satta keeps things clean. The layout is simple, the results are clearly
                visible,
                and the site loads fast on mobile — which is how most people visit.

            </strong>
        </p>
        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>We Don't Make False Claims</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>This is important. Real Satta does not sell "leak numbers," "fix jodi," or "guaranteed results." Any
                website
                that does is running a scam, plain and simple. If someone is asking you to pay for a sure-shot number, don't
                do
                it. There are no guaranteed results in these markets — anyone who says otherwise is lying to your face.
                <br>
                Real Satta exists purely to show you results as they are officially declared. Nothing more, nothing less.

            </strong>
        </p>
        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Trusted by Regular Followers Across North India</strong>
        </h3>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Real Satta Bazar has built its reputation over years of consistent, accurate, and on-time result
                posting.
                Our regular visitors come from Delhi, UP, Haryana, Rajasthan, Punjab, and Uttarakhand. The trust didn't come
                from advertising — it came from simply showing up every single day and delivering the right number at the
                right
                time.
            </strong>
        </p>
        <h2
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>About Real Satta Bazar — What We Actually Do</strong>
        </h2>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Real Satta Bazar is a result information portal built specifically for Satta King market tracking. We
                are
                not a gaming company. We don't run any markets. We don't take bets or wagers, and we have no affiliation
                with
                any Satta operator, company, or khaiwal.
                <br>
                What we do is straightforward: we collect publicly available result information for all major Satta King
                markets
                and display it on this site in a clean, organized, and timely manner. That's the whole job. We do it every
                day,
                without missing a beat.
                <br>
                The platform was built because people were fed up with unreliable, slow, and inaccurate result sites. Real
                Satta
                set out to do one thing well — post the right number at the right time — and that singular focus is what has
                kept people coming back year after year.
                <br>
                Today we cover 25+ markets from across North India, with result timings spread throughout the day starting
                from
                2 AM (Disawar) right through to midnight (Gali). Whether you're following one game or ten, everything you
                need
                is in one place, updated in real time, completely free to access.
                <br>
                If you ever have questions or want a specific market added to our tracking list, you can reach out through
                the
                Contact page.
            </strong>
        </p>

        <h2
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Frequently Asked Questions</strong>
        </h2>
        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>What is Real Satta King?</strong>
        </h3>
        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Real Satta King is the commonly used term for the Satta King result ecosystem — the markets, the
                declared
                numbers, and the websites that track and display them. Real Satta (realsatta.in) is an informational website
                that posts daily Satta King results for all major Real Satta Bazar markets as soon as they are officially
                declared. We are strictly an information platform — we do not run or facilitate any games.
            </strong>
        </p>
        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>How do I check today's Satta King live result?</strong>
        </h3>
        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Simply scroll up on this page to the Live Results section. Results are updated throughout the day as
                each
                game declares its number. The table shows both yesterday's result and today's live update side by side.
                Bookmark
                this page for quick daily access.
            </strong>
        </p>
        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>What time does the Gali Satta King result come?</strong>
        </h3>
        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Gali result is typically declared around 11:50 PM. Disawar comes at 2:00 AM, Faridabad at 6:20 PM, and
                Ghaziabad at 9:30 PM. These timings are generally consistent but may occasionally shift by a few minutes
                depending on the declaration.
            </strong>
        </p>
        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Is Real Satta a gambling or betting website?</strong>
        </h3>
        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>No. Real Satta does not host, run, or facilitate any gambling or betting activity of any kind. We are a
                results information website only. All result data we display is publicly available information. Accessing or
                using this website for anything other than informational reference is entirely the visitor's own
                responsibility.
            </strong>
        </p>
        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Can looking at past results help predict future numbers?</strong>
        </h3>
        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>No — and this is worth being very clear about. Satta King results are chance-based. Past numbers do not
                influence future ones in any way. If any website or person claims to offer "sure shot" results or "fix
                numbers"
                in exchange for money, that is a scam. Do not pay anyone for result predictions under any circumstances.
            </strong>
        </p>
        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Which games does Real Satta cover?</strong>
        </h3>
        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>We cover 25+ markets including Gali, Disawar, Faridabad, Ghaziabad, Delhi Bazaar, Delhi Darbar, Shri
                Ganesh,
                Palika Bazar, Noida Night, Roop Nagar, Prayagraj, Sadar Bazar, Gwalior, Agra, Rampur, Alwar, Fatehpur,
                Dehradun
                City, Aligarh Night, Dwarka, Chhattisgarh, Jeevan Shree, MeghaCity, and several other regional markets from
                across North India.
            </strong>
        </p>

        <h3
            style="display: block; width: 100%; padding: 12px 20px; text-align: center; font-size: 2.25rem; font-weight: 700; color: rgb(0, 0, 0); line-height: 1.7; background-color: #FFAB00; border-top: 2px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); margin: 20px 0px;">
            <strong>Why are some results showing "waiting" or "updating"?</strong>
        </h3>
        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            <strong>Each game has a fixed result time. Until that time arrives and the result is officially declared, the
                status
                will show as "Updating." As soon as the number is out, it goes live on the page automatically. If a result
                is
                taking longer than usual, it simply means the official declaration has been slightly delayed — we post it
                the
                moment it's available.
            </strong>
        </p>

        <p
            style="padding-left: 20px; padding-right: 20px; color: black; font-size: 15px; font-weight: 500; letter-spacing: 0.2px;">
            &nbsp;</p>
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
