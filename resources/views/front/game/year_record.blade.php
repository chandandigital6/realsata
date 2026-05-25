@extends('front.layouts.app')

@section('content')

<div class="king-heading">
    <span style="font-weight:bold;text-align:center;margin-top:10px;background-color:#F5004F;border:2px solid #fff;padding:7px;color:#FFF;font-size:24px!important;display:flex;justify-content:center;">
        {{ strtoupper($game->name) }} SATTA RECORD CHART {{ $year }}
    </span>
</div>

@php
    $resultsByDate = $results->keyBy('result_date');

    $startDate = \Carbon\Carbon::create($year, 1, 1);
    $endDate = \Carbon\Carbon::create($year, 12, 31);
    $dates = \Carbon\CarbonPeriod::create($startDate, $endDate);
@endphp

<div class="table-responsive" style="overflow-x:scroll;">
    <table width="100%" class="month_result_table rtable" border="1" cellspacing="0" cellpadding="0">

        <tr>
            <td style="font-size:14px;white-space:nowrap;background-color:#cc4c1a;color:#fff;text-align:center;padding:6px 8px;text-transform:uppercase;">
                DATE
            </td>
            <td style="font-size:14px;white-space:nowrap;background-color:#cc4c1a;color:#fff;text-align:center;padding:6px 8px;text-transform:uppercase;">
                {{ strtoupper($game->name) }}
            </td>
        </tr>

        @foreach($dates as $date)
            @php
                $dateKey = $date->format('Y-m-d');
                $record = $resultsByDate->get($dateKey);
                $resultValue = $record->result ?? null;
            @endphp

            <tr>
                <td style="font-size:18px;background-color:#3333ff;color:#fff;text-align:center;font-weight:bold;">
                    {{ $date->format('d-m-Y') }}
                </td>

                <td style="font-size:15px;font-weight:bold;background-color:#fff;padding:6px 2px 7px 2px;text-align:center;">
                    @if(!empty($resultValue))
                        {{ is_numeric($resultValue) && $resultValue <= 9 ? str_pad($resultValue, 2, '0', STR_PAD_LEFT) : $resultValue }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach

    </table>
</div>

<div style="text-align:center;margin:20px 0;">
    <a href="{{ url('/chart') }}" style="font-weight:bold;color:#000;">
        BACK TO CHART
    </a>
</div>

@endsection