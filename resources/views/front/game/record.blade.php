@extends('front.layouts.app',[
    'seo' => $seo,
])

@section('content')

<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>{{ strtoupper($game->name) }} RECORD CHART {{ $year }}</h1>
            </div>
        </div>
    </div>
</section>

<section class="newtable">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 nopadding">
                <div class="table-responsive">

                    @php
                        $months = [
                            1  => 'January',   2  => 'February', 3  => 'March',
                            4  => 'April',     5  => 'May',      6  => 'June',
                            7  => 'July',      8  => 'August',   9  => 'September',
                            10 => 'October',   11 => 'November', 12 => 'December',
                        ];

                        // Build lookup: day => month => result
                        $grid = [];
                        foreach ($results as $result) {
                            $day   = (int) $result->result_date->format('d');
                            $month = (int) $result->result_date->format('m');
                            $grid[$day][$month] = ($result->status === 'declared' && $result->result)
                                ? $result->result
                                : '-';
                        }
                    @endphp

                    <table class="table table-bordered record-chart-table">
                        <thead>
                            <tr>
                                <th class="text-center year-col">{{ $year }}</th>
                                @foreach($months as $num => $name)
                                    <th class="text-center month-col">{{ $name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @for($day = 1; $day <= 31; $day++)
                                <tr>
                                    <td class="text-center day-cell">
                                        {{ str_pad($day, 2, '0', STR_PAD_LEFT) }}
                                    </td>
                                    @foreach($months as $num => $name)
                                        @php
                                            // Check if this day/month combo is valid (e.g., Feb 30 doesn't exist)
                                            $daysInMonth = \Carbon\Carbon::create($year, $num, 1)->daysInMonth;
                                            $cellValue   = '-';
                                            if ($day <= $daysInMonth) {
                                                $cellValue = $grid[$day][$num] ?? '-';
                                            }
                                        @endphp
                                        <td class="text-center result-cell {{ $cellValue !== '-' ? 'has-result' : '' }}">
                                            {{ $cellValue }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>

                </div>

                @if(isset($contentBlocks) && $contentBlocks->count())
                    <div class="mt-4">
                        @foreach($contentBlocks as $block)
                            <div class="content-block">
                                @if($block->title)
                                    <h2>{{ $block->title }}</h2>
                                @endif
                                <div class="prose">
                                    {!! $block->content !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="text-center" style="margin:15px 0;">
                    <a href="{{ route('chart') }}" class="btn btn-primary">
                        Back To Chart
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ===== CSS ===== --}}
<style>
    /* ---- Table wrapper ---- */
    .record-chart-table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    /* ---- Header row ---- */
    .record-chart-table thead tr {
        background-color: #ff6600;   /* orange header like in image */
    }

    .record-chart-table thead th {
        background-color: #ff6600;
        color: #fff;
        font-weight: 700;
        padding: 8px 6px;
        border: 2px solid #d45500;
        white-space: nowrap;
    }

    /* Year/date column header - slightly darker */
    .record-chart-table thead th.year-col {
        background-color: #e05500;
        min-width: 52px;
    }

    /* ---- Day column (left side) ---- */
    .record-chart-table tbody .day-cell {
        background-color: #e05500;
        color: #fff;
        font-weight: 700;
        border: 2px solid #c44a00;
        padding: 6px 4px;
        min-width: 52px;
    }

    /* ---- Result cells ---- */
    .record-chart-table tbody .result-cell {
        background-color: #ffffff;
        color: #222;
        border: 1px solid #ddd;
        padding: 6px 4px;
        min-width: 60px;
        font-weight: 600;
    }

    /* Highlight cells that have actual results */
    .record-chart-table tbody .result-cell.has-result {
        color: #111;
        font-weight: 700;
    }

    /* Alternate row tint */
    .record-chart-table tbody tr:nth-child(even) .result-cell {
        background-color: #fff8f0;
    }

    /* ---- Content blocks ---- */
    .content-block {
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 1px 4px rgba(0,0,0,.1);
        padding: 16px;
        margin-bottom: 16px;
    }

    .content-block h2 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    /* ---- Responsive: shrink font on mobile ---- */
    @media (max-width: 768px) {
        .record-chart-table {
            font-size: 11px;
        }
        .record-chart-table thead th,
        .record-chart-table tbody td {
            padding: 4px 2px;
            min-width: 36px;
        }
    }
</style>

@endsection