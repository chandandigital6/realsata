{{-- @extends('front.layouts.app', ['seo' => $seo ?? null])

@section('content')

<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>SATTA CHART</h1>
            </div>
        </div>
    </div>
</section>

<section class="tabel3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 nopadding">
                <div class="table-responsive">

                    <table class="table table-bordered">
                        <tbody>

                            @forelse($games as $game)

                                <tr>
                                    <td class="forfirtcolor">
                                        <strong>{{ strtoupper($game->name) }}</strong>
                                    </td>

                                    @forelse($game->chartYears as $chartYear)
                                        <td>
                                            <strong>
                                                <a href="{{ route('game.year-record', [$game->slug, $chartYear->year]) }}"
                                                   style="color:black;">
                                                    {{ $chartYear->year }}
                                                </a>
                                            </strong>
                                        </td>
                                    @empty
                                        <td>
                                            <strong>
                                                <a href="{{ route('game.year-record', [$game->slug, now()->year]) }}"
                                                   style="color:black;">
                                                    {{ now()->year }}
                                                </a>
                                            </strong>
                                        </td>
                                    @endforelse
                                </tr>

                            @empty

                                <tr>
                                    <td class="text-center">
                                        No chart found.
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

@endsection --}}



















@extends('front.layouts.app', ['seo' => $seo ?? null])

@section('content')

<section class="octoberresultchart" style="padding:12px 8px;background:#f5f5f5;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 style="
                    margin:0;
                    background:#f5004f;
                    color:#fff;
                    border:2px solid #fff;
                    border-radius:10px;
                    padding:10px 8px;
                    font-size:28px;
                    font-weight:900;
                    text-transform:uppercase;
                    box-shadow:0 4px 12px rgba(0,0,0,0.20);
                ">
                    SATTA CHART
                </h1>
            </div>
        </div>
    </div>
</section>

<section class="tabel3" style="padding:12px 6px;background:#f5f5f5;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 nopadding">

                <div style="
                    border:3px solid #111;
                    border-radius:10px;
                    overflow:hidden;
                    background:#fff;
                    box-shadow:0 4px 14px rgba(0,0,0,0.18);
                ">
                    <div class="table-responsive" style="margin:0;">
                        <table class="table table-bordered" style="
                            margin-bottom:0;
                            width:100%;
                            border-collapse:collapse;
                            background:#fff;
                        ">
                            <tbody>

                                @forelse($games as $game)

                                    <tr>
                                        <td class="forfirtcolor" style="
                                            border:1px solid #222;
                                            background:#ffb300;
                                            padding:10px 8px;
                                            text-align:center;
                                            width:65%;
                                        ">
                                            <strong>
                                                <a href="{{ route('game.record', ['slug' => $game->slug]) }}"
                                                   style="
                                                        color:#000;
                                                        text-decoration:none;
                                                        font-size:15px;
                                                        font-weight:900;
                                                        display:block;
                                                        text-transform:uppercase;
                                                   ">
                                                    {{ strtoupper($game->name) }}
                                                </a>
                                            </strong>
                                        </td>

                                        <td style="
                                            border:1px solid #222;
                                            background:#fff;
                                            padding:10px 8px;
                                            text-align:center;
                                            width:35%;
                                        ">
                                            <strong>
                                                <a href="{{ route('game.record', ['slug' => $game->slug]) }}"
                                                   style="
                                                        color:#fff;
                                                        text-decoration:none;
                                                        font-size:15px;
                                                        font-weight:900;
                                                        display:inline-block;
                                                        background:#111;
                                                        padding:4px 16px;
                                                        border-radius:5px;
                                                   ">
                                                    2026
                                                </a>
                                            </strong>
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td class="text-center" style="
                                            border:1px solid #222;
                                            padding:14px;
                                            background:#fff;
                                            color:#000;
                                            font-weight:800;
                                        ">
                                            No chart found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
    .tabel3 table tr:hover td {
        background: #fff7d6 !important;
    }

    .tabel3 table tr:hover td:first-child {
        background: #ffc928 !important;
    }

    .tabel3 table tr:hover td:last-child a {
        background: #f5004f !important;
        color: #fff !important;
    }

    @media (max-width: 600px) {
        .octoberresultchart h1 {
            font-size: 22px !important;
            padding: 9px 6px !important;
        }

        .tabel3 table td {
            padding: 8px 5px !important;
        }

        .tabel3 table td a {
            font-size: 13px !important;
        }

        .tabel3 table td:last-child a {
            padding: 4px 12px !important;
        }
    }
</style>

@endsection