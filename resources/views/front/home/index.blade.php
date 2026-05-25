@extends('front.layouts.app')

@section('content')

<div class="col-md-12 text-center" style="background-color:white;">
    <div class="ads" style="padding:8px 0; margin:8px 0; background:#FF5252; color:white; text-align:center;">
        <h4 class="text-center text-black" style="font-weight:bolder;">
            व्हाट्सएप पर सुपर फास्ट रिजल्ट देखने के लिए नीचे दिए गए लिंक पर जाएं और चैनल को फॉलो करें।
        </h4>

        <a href="https://whatsapp.com/channel/0029Vb67katLikgE57Pwhj0T">
            <img src="{{ asset('public/Join-WhatsApp.png') }}"
                 width="160px"
                 style="display:block; margin-bottom:10px; margin-left:auto; margin-right:auto;">
        </a>
    </div>
</div>

<div class="drag">
    <h2>
        <span style="margin-top:4px; margin-bottom:4px; font-size:15px; text-align:center; color:#000;">
            A7-SATTAFAST RECORD CHART
        </span>
        <br>
        <a href="/"><span>A7-SATTAFAST LIVE RESULT</span></a>
    </h2>
</div>

<div class="resultchart" style="background-color:#fff;">
    <div class="addb">
        <h3 style="text-align:center; padding:10px; color:red; font-weight:bold;">
            A7-SATTAFAST LIVE RESULT
        </h3>
    </div>
</div>

<div class="container-fluid">
    <div class="border row">

        @forelse($games as $game)

            @php
                $todayResult = $game->todayResult->result ?? null;
                $yesterdayResult = $game->yesterdayResult->result ?? null;
            @endphp

            <div class="gamebox col-md-6 col-sm-6 col-xs-6">
                <font class="boxresult">
                    <a class="text-blacks"
                       href="{{ url('record/' . $game->slug) }}"
                       title="{{ $game->name }}">
                        {{ $game->name }}
                    </a>
                </font>

                <br>

                <a class="text-black"
                   href="{{ url('record/' . $game->slug) }}"
                   title="records">
                    Records
                </a>

                <br>

                <font class="time_result">
                    ( {{ $game->result_time }} )<br>

                    <font class="kal">कल &nbsp;&nbsp; आज</font> <br>

                    <font class="gameboxresult">
                        @if(!empty($yesterdayResult))
                            {{ is_numeric($yesterdayResult) && $yesterdayResult <= 9 ? str_pad($yesterdayResult, 2, '0', STR_PAD_LEFT) : $yesterdayResult }}
                        @else
                            XX
                        @endif
                    </font>

                    <img loading="lazy"
                         src="{{ asset('arrow.gif') }}"
                         width="20"
                         height="20"
                         role="presentation"
                         title="SATTAKING | A7-SATTAFAST | SATTA CHART | A7-SATTAFAST RESULT | A7-SATTAFAST LIVE">
                </font>

                <font class="gameboxresult">
                    @if(!empty($todayResult))
                        {{ is_numeric($todayResult) && $todayResult <= 9 ? str_pad($todayResult, 2, '0', STR_PAD_LEFT) : $todayResult }}
                    @else
                        XX
                    @endif
                </font>
            </div>

        @empty
            <div class="col-md-12 text-center p-3">
                <strong>No game data found.</strong>
            </div>
        @endforelse

    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm">
            <div class="khaiwalbox2-box">
                TOP ADVERTISEMENT SECTION
            </div>
        </div>
    </div>
</div>

<br>

<div class="drag">
    <h2>A7-SATTAFAST RECORD CHART</h2>
    <a href="/">A7-SATTAFAST LIVE RESULT</a>
</div>

<div class="drag">
    <a href="/">A7-SATTAFAST Chart</a>
</div>

{{-- Current Month Dynamic Chart --}}
<div class="table-responsive" style="overflow-x:scroll;">
    <table width="100%" class="month_result_table rtable" border="1" cellspacing="0" cellpadding="0">

        <tr>
            <td style="font-size:14px; white-space:nowrap; background-color:#cc4c1a; color:#fff; text-align:center; padding:6px 8px; text-transform:uppercase;">
                DATE
            </td>

            @foreach($chartGames as $chartGame)
                <td style="font-size:14px; white-space:nowrap; background-color:#cc4c1a; color:#fff; text-align:center; padding:6px 8px; text-transform:uppercase;">
                    {{ $chartGame->name }}
                </td>
            @endforeach
        </tr>

        @foreach($dates as $date)
            @php
                $dateKey = $date->format('Y-m-d');
                $dayResults = $monthlyResults->get($dateKey, collect());
            @endphp

            <tr>
                <td style="font-size:18px; background-color:#3333ff; color:#fff; text-align:center; font-weight:bold;">
                    {{ $date->format('d') }}
                </td>

                @foreach($chartGames as $chartGame)
                    @php
                        $singleResult = collect($dayResults)->firstWhere('game_slug', $chartGame->slug);
                        $resultValue = $singleResult->result ?? null;
                    @endphp

                    <td style="font-size:15px; font-weight:bold; background-color:#fff; padding:6px 2px 7px 2px; text-align:center;">
                        @if(!empty($resultValue))
                            {{ is_numeric($resultValue) && $resultValue <= 9 ? str_pad($resultValue, 2, '0', STR_PAD_LEFT) : $resultValue }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach

    </table>
</div>



{{-- GAME YEAR RECORD CHART SECTION --}}
<div style="background:#000; padding-bottom:25px;">

    @forelse($chartGames as $game)

        <div style="
            background:#f5004f;
            color:#fff;
            text-align:center;
            font-size:26px;
            font-weight:bold;
            padding:14px 5px;
            border-top:3px solid #fff;
            border-bottom:3px solid #fff;
            text-transform:uppercase;
        ">
            SATTA RECORD CHART
        </div>

        <div style="
            background:#fff;
            border:2px solid blue;
            border-radius:18px;
            margin:0 0 30px 0;
            text-align:center;
            padding:10px;
            font-size:24px;
            color:#000;
        ">
            <a href="{{ url('record/' . $game->slug) }}"
               style="color:#000; text-decoration:none;">
                SATTA RECORD CHART {{ $game->name }}
            </a>
        </div>

        @php
            $years = [
                now('Asia/Kolkata')->year,
                now('Asia/Kolkata')->subYear()->year,
                now('Asia/Kolkata')->subYears(2)->year,
            ];
        @endphp

        @foreach($years as $year)
            <div style="
                background:#fff;
                border:2px solid blue;
                border-radius:18px;
                margin:0 0 30px 0;
                text-align:center;
                padding:10px;
                font-size:24px;
                color:#000;
            ">
                <a href="{{ url('record/' . $game->slug . '/' . $year) }}"
                   style="color:#000; text-decoration:none;">
                    SATTA RECORD CHART {{ $year }}
                </a>
            </div>
        @endforeach

    @empty
        <div style="background:#fff; padding:20px; text-align:center;">
            No record chart found.
        </div>
    @endforelse

</div>



<div class="addb" style="padding:15px; text-align:center; background:#e8f5e9; color:#000; font-weight:bold;">
    TODAY ADVICE SECTION
</div>

<div class="content" style="background-color:#000;">
    <div class="accordion" id="a7sattaAccordion">

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                    A7 Satta King — Official Result & Live Updates
                </button>
            </h2>

            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#a7sattaAccordion">
                <div class="accordion-body">
                    Welcome to the most trusted platform for A7 Satta result. We deliver today's declared numbers fast and clearly.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                    What Is A7 Satta King?
                </button>
            </h2>

            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#a7sattaAccordion">
                <div class="accordion-body">
                    A7 Satta King is an online platform for checking satta result and record chart updates.
                </div>
            </div>
        </div>

    </div>
</div>

<h2 class="faq">Frequently Asked Questions</h2>

<div style="padding-top:0.8rem; padding-bottom:0.8rem;">

    <div class="Accordian_tabs___mQ3J">
        <div class="Accordian_tab__t24lo">
            <input type="checkbox" class="Accordian_input__zKw_Y" id="accord1">
            <label class="Accordian_tabLabel__UPw4z" for="accord1">
                What is A7 Satta King?
            </label>
            <h3 class="Accordian_tabContent__b1_ee">
                A7 Satta King is a result and chart information platform.
            </h3>
        </div>
    </div>

    <div class="Accordian_tabs___mQ3J">
        <div class="Accordian_tab__t24lo">
            <input type="checkbox" class="Accordian_input__zKw_Y" id="accord2">
            <label class="Accordian_tabLabel__UPw4z" for="accord2">
                Is this data dynamic?
            </label>
            <h3 class="Accordian_tabContent__b1_ee">
                Yes, this page is now showing data from API.
            </h3>
        </div>
    </div>

</div>

<style>
    h4 {
        text-align: center;
    }

    .faq {
        background: blue;
        padding: 10px;
        color: #fff;
        text-align: center;
    }

    .faq h3 {
        text-align: center;
        color: red;
    }

    .content h4 {
        color: white;
    }
</style>

<div class="addb" style="padding:15px; text-align:center; background:#d1ecf1; color:#000; font-weight:bold;">
    FOOTER ADVERTISEMENT SECTION
</div>

@endsection