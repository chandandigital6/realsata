@extends('front.layouts.app')

@section('content')

<div class="king-heading">
    <span style="font-weight:bold;text-align:center;margin-top:10px;background-color:#F5004F;border:2px solid #fff;padding:7px;color:#FFF;font-size:26px!important;display:flex;justify-content:center;">
        SATTA RECORD CHART
    </span>
</div>

@forelse($games as $game)

    <div class="king-heading">
        <span style="font-weight:bold;text-align:center;margin-top:10px;background-color:#F5004F;border:2px solid #fff;padding:7px;color:#FFF;font-size:22px!important;display:flex;justify-content:center;">
            <a href="{{ url('record/' . $game->slug) }}" style="color:#fff;text-decoration:none;">
                {{ strtoupper($game->name) }} SATTA RECORD CHART
            </a>
        </span>
    </div>

    @forelse($game->chartYears as $index => $chartYear)
        <div class="list">
            <a href="{{ url('record/' . $game->slug . '/' . $chartYear->year) }}"
               style="color:black;"
               title="{{ $game->name }} {{ $chartYear->year }}">
                {{ strtoupper($game->name) }} SATTA RECORD CHART {{ $chartYear->year }}
            </a>
        </div>
        <br>
    @empty
        @php
            $currentYear = now('Asia/Kolkata')->year;
        @endphp

        <div class="list">
            <a href="{{ url('record/' . $game->slug . '/' . $currentYear) }}"
               style="color:black;">
                {{ strtoupper($game->name) }} SATTA RECORD CHART {{ $currentYear }}
            </a>
        </div>
        <br>
    @endforelse

@empty
    <div style="padding:20px;text-align:center;">
        <strong>No chart games found.</strong>
    </div>
@endforelse

@endsection