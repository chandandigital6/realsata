@extends('front.layouts.app')

@section('content')
    <section class="advertisement">
        <a href="https://api.whatsapp.com/send/?phone=919896916793&text&type=phone_number&app_absent=0" target="_blank"
            style="text-decoration:none;color:inherit;">

            <div style="background:#f2aa00; border:1px solid #000;">

                <div
                    style="background:#efbd3d; border-top:5px dotted #000; border-bottom:5px dotted #000; padding:8px 15px 12px; text-align:center;">
                    <p style="margin:0 0 10px; font-size:16px; color:#000;">
                        नमस्कार साथियों
                    </p>

                    <p style="margin:0 0 32px; font-size:15px; color:#000;">
                        सीधा कंपनी खाईवाल के पास गेम प्ले करे बिंदास
                        <b>1001%</b> पेमेंट की गारंटी के साथ आपका अपना भाई
                    </p>

                    <p style="margin:0 0 2px; font-size:16px; font-weight:700; color:#000;">
                        S.K Bhai
                    </p>

                    <span style="display:inline-block; background:#fff; padding:0 8px;">
                        <img src="{{ asset('whatsapp-img.png') }}" alt="Whatsapp" style="height:65px; max-width:220px;">
                    </span>
                </div>
            </div>

        </a>
    </section>

    <section class="circlebox">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="liveresult">

                    <div class="datetime">
                        <div id="clockbox"></div>
                    </div>

                    <p class="hintext">हा भाई यही आती हे सबसे पहले खबर रूको और देखो</p>

                    @forelse($games as $game)
                        <div class="sattaname">
                            <p>{{ strtoupper($game->name) }}</p>
                        </div>

                        <div class="sattaresult">
                            <font>
                                <span>
                                    @if ($game->todayResult && $game->todayResult->status === 'declared' && $game->todayResult->result)
                                        {{ $game->todayResult->result }}
                                    @else
                                        <p>
                                            <strong class="waitimg">
                                                <img class="lazy" src="/m/d.gif" alt="waiting">
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

    <section class="sattadividerr">
        <div class="container">
            <div class="col-md-12 text-center">
                <h4 class="text-center text-white">
                    व्हाट्सएप पर सुपर फास्ट रिजल्ट देखने के लिए नीचे दिए गए लिंक पर जाएं और चैनल को फॉलो करें।
                </h4>

                <a href="https://whatsapp.com/channel/0029Vb67katLikgE57Pwhj0T">
                    <h4 style="color:blue">
                        <img src="/Join-WhatsApp.png" width="180px" alt="Join WhatsApp">
                    </h4>
                </a>
            </div>
        </div>
    </section>

    <section class="top-advo">
        <div class="row p-0">
            <div class="col-md-12">
                <a href="https://api.whatsapp.com/send/?phone=919896916793&text&type=phone_number&app_absent=0"
                    target="_blank" style="text-decoration:none;color:inherit;">

                    <div class="card top_card" style="background:#f2aa00; border:5px dotted #000; border-radius:0;">
                        <div class="card-body text-center" style="padding:5px 15px 12px;">

                            <h5 style="font-weight:700; margin:0;">
                                सीधे सट्टा कंपनी का No 1 खाईवाल
                            </h5>

                            <h5 style="font-weight:700; color:#c0392b; margin:2px 0 12px;">
                                ✰✰ ABHISHEK Bhai KHAIWAL ✰✰
                            </h5>

                            <p style="font-weight:700; line-height:1.45; margin:0;">
                                🎯 पालिका बाजार..1:20pm<br>
                                🎯 प्रयागराज........2:00pm<br>
                                🎯 दिल्लीबाजार ...3:00pm<br>
                                🎯 दिल्ली दरबार....3:30pm<br>
                                🎯 श्री गणेश........4:30 Pm<br>
                                🎯 रूप नगर..........5:10pm<br>
                                🎯 फरीदाबाद.......5:50 pm<br>
                                🎯 फतेहपुर..........7:10 pm<br>
                                🎯 गाज़ियाबाद......8:50 pm<br>
                                🎯 नोएडानाइट....10:00 pm<br>
                                🎯 गली..............11:15pm<br>
                                🎯 दिसावर ...........3:00 am
                            </p>

                            <p style="font-weight:700; line-height:1.45; margin:4px 0;">
                                जोड़ी रेट<br>
                                जोड़ी रेट 10-------960<br>
                                हरूफ रेट 100-----960
                            </p>

                            <h5 style="font-weight:700; color:#c0392b; margin:4px 0;">
                                ✰✰ ABHISHEK Bhai KHAIWAL ✰✰
                            </h5>

                            <p style="font-weight:700; color:#6c2cff; margin:0 0 5px;">
                                Game Play करने के लिये नीचे लिंक पर क्लिक करे
                            </p>

                            <span style="display:inline-block; background:#fff; padding:0 8px;">
                                <img src="{{ asset('whatsapp-img.png') }}" alt="Whatsapp"
                                    style="height:50px; max-width:180px;">
                            </span>

                            <p style="font-weight:700; margin:2px 0 0;">
                                Click to chat
                            </p>

                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section class="contact-advo">
        <div class="row p-0">
            <div class="col-md-12">
                <a href="https://api.whatsapp.com/send/?phone=919896916793&text&type=phone_number&app_absent=0"
                    target="_blank" style="text-decoration:none;color:inherit;">

                    <div class="card" style="background:#f2aa00; border:5px dotted #000; border-radius:0;">
                        <div class="card-body text-center" style="padding:12px 15px;">

                            <p style="font-size:18px; font-weight:700; margin:0 0 12px;">
                                नमस्कार साथियों
                            </p>

                            <p style="font-size:18px; font-weight:700; margin:0 0 12px;">
                                अपनी गेम का रिजल्ट हमारी web साइट पर लगाने के लिए संपर्क करें !
                            </p>

                            <p style="font-size:18px; font-weight:700; margin:0 0 18px;">
                                किसी भी भाई को किसी तरह की कोई दिक्कत या परेशानी हो तो हमसे whatsapp पर संपर्क करे
                            </p>

                            <h3 style="font-weight:800; margin:0 0 8px;">
                                ARUN BHAI
                            </h3>

                            <span style="display:inline-block; background:#fff; padding:0 8px;">
                                <img src="{{ asset('whatsapp-img.png') }}" alt="Whatsapp"
                                    style="height:50px; max-width:180px;">
                            </span>

                            <p style="font-size:17px; font-weight:800; margin:10px 0 0;">
                                NOTE: इस नंबर पर लिंक गेम नही मिलता गेम खेलने वाले भाई कॉल या मैसेज न करें !
                            </p>

                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section class="tablebox1">
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

                                @forelse($games as $game)
                                    <tr style="height:36px">
                                        <td class="foryellow">
                                            <span class="gamenameeach">
                                                {{ strtoupper($game->name) }}
                                            </span>
                                            <br>

                                            <span class="time">
                                                {{ $game->result_time ? \Carbon\Carbon::parse($game->result_time)->format('h:i A') : '-' }}
                                            </span>
                                            <br>

                                            <a style="font-size:12px;color:#000000;"
                                                href="{{ route('game.record', $game->slug) }}">
                                                Record Chart
                                            </a>
                                        </td>

                                        <td>
                                            @if ($game->yesterdayResult && $game->yesterdayResult->result)
                                                {{ $game->yesterdayResult->result }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if ($game->todayResult && $game->todayResult->status === 'declared' && $game->todayResult->result)
                                                {{ $game->todayResult->result }}
                                            @else
                                                <p>
                                                    <strong class="waitimg">
                                                        <img class="lazy" alt="waiting" src="/m/d.gif">
                                                    </strong>
                                                </p>
                                            @endif
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="3" class="text-center">
                                            No games found.
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

    <br>

    <section class="octoberresultchart">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 id="date"></h2>
                </div>
            </div>
        </div>
    </section>

    <section class="newtable">
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

                                    @foreach ($chartGames as $game)
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

                                        @foreach ($chartGames as $game)
                                            @php
                                                $result = $dayResults->firstWhere('game_id', $game->id);
                                            @endphp

                                            <td class="text-center">
                                                @if ($result && $result->status === 'declared' && $result->result)
                                                    {{ $result->result }}
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
