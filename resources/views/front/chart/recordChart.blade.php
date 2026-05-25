@php
    $years = [
        'DISAWAR' => ['DISAWAR', '2026', '2025', '2024', '2023'],
        'DELHI BAZAR' => ['DELHI BAZAR', '2026', '2025', '2024', '2023'],
        'FARIDABAD' => ['FARIDABAD', '2026', '2025', '2024', '2023'],
        'GHAZIABAD' => ['GHAZIABAD', '2026', '2025', '2024', '2023'],
        'GALI' => ['GALI', '2026', '2025', '2024', '2023'],
    ];
@endphp

@foreach ($years as $game => $yearArr)

    <div class="king-heading">
        <span style="font-weight:bold; text-align:center; margin-top:10px; background-color:#F5004F; border:2px solid #fff; padding:7px; color:#FFF; font-size:26px !important; display:flex; justify-content:space-around;">
            <a href="#" title="SATTA RECORD CHART" style="text-align:center; color:#fff;">
                SATTA RECORD CHART
            </a>
        </span>
    </div>

    @foreach ($yearArr as $index => $year)

        @if($index == 0 || $index == 1)
            <div class="list">
                <a href="#" style="color:black;" title="Time">
                    SATTA RECORD CHART {{ $year }}
                </a>
            </div>
        @else
            <div class="list">
                <a href="#" style="color:black;" title="Time">
                    SATTA RECORD CHART {{ $year }}
                </a>
            </div>
        @endif

        <br>

    @endforeach

@endforeach