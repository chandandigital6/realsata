<div class="drag text-center">
    <span style="margin-top:4px; margin-bottom:4px; color:#000; font-size:15px; text-align:center; display:block;">
        A7-SATTAFAST | SATTA RESULT | SATTA CHART
    </span>

    <br>

    <a href="/">
        <img loading="lazy"
             src="{{ url('A1.png') }}"
             class="logo"
             alt="A7 SattaFast - Satta King Fast Result Site 2026">
    </a>

    <br>
</div>

<div style="background-color:#000; margin:2px; padding:10px; border-radius:10px; text-align:center;">

    <h1 style="color:yellow; font-size:20px; font-weight:bold;">
        A7 Satta King Result Today – A7 Satta Fast Live Update
    </h1>

    <p style="color:#FFF;">
        हाँ भाई सबसे पहले खबर यहीं आती है, रिफ्रेश रिजल्ट करो और देखो
    </p>

    <div class="datetime">

        <div style="color:white; font-weight:bold;" id="clockbox">
            {{ now('Asia/Kolkata')->format('d F Y | h:i A') }}
        </div>

      @php
    $now = \Carbon\Carbon::now('Asia/Kolkata');
    $allHeaderGames = collect($headerGames ?? []);

    /*
    |--------------------------------------------------------------------------
    | 1. Recently declared result games
    | show_minutes empty/0/null ho to 10 minutes
    |--------------------------------------------------------------------------
    */
    $declaredGames = $allHeaderGames
        ->filter(function ($game) use ($now) {

            if (
                empty($game->todayResult)
                || ($game->todayResult->status ?? null) !== 'declared'
                || empty($game->todayResult->result)
                || empty($game->todayResult->updated_at)
            ) {
                return false;
            }

            $showMinutes = (int) ($game->todayResult->show_minutes ?? 0);

            if ($showMinutes <= 0) {
                $showMinutes = 10;
            }

            $expireTime = \Carbon\Carbon::parse($game->todayResult->updated_at, 'Asia/Kolkata')
                ->addMinutes($showMinutes);

            return $now->lessThanOrEqualTo($expireTime);
        })
        ->sortByDesc(function ($game) {
            return \Carbon\Carbon::parse($game->todayResult->updated_at, 'Asia/Kolkata')->timestamp;
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | 2. Normal games by result time
    | Declared top games ko dobara repeat nahi karna
    |--------------------------------------------------------------------------
    */
    $normalGames = $allHeaderGames
        ->reject(function ($game) use ($declaredGames) {
            return $declaredGames->contains('slug', $game->slug);
        })
        ->filter(function ($game) use ($now) {
            if (empty($game->result_time)) {
                return false;
            }

            try {
                $gameDateTime = \Carbon\Carbon::parse(
                    $now->format('Y-m-d') . ' ' . trim($game->result_time),
                    'Asia/Kolkata'
                );

                return $gameDateTime->greaterThanOrEqualTo($now);
            } catch (\Exception $e) {
                return false;
            }
        })
        ->sortBy(function ($game) use ($now) {
            return \Carbon\Carbon::parse(
                $now->format('Y-m-d') . ' ' . trim($game->result_time),
                'Asia/Kolkata'
            )->timestamp;
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | 3. Final display
    | Pehle declared result top me, fir upcoming games
    | Total only 4
    |--------------------------------------------------------------------------
    */
    $liveGames = $declaredGames
        ->concat($normalGames)
        ->take(4)
        ->values();

    $nextGame = $normalGames->first();
@endphp

        <div class="livegame">
            <p style="margin:0; text-transform:uppercase;">
                NEXT GAME : {{ $nextGame ? strtoupper($nextGame->name) : 'NA' }}
            </p>
        </div>

        <div class="open" style="text-align:center; justify-content:center; display:flex;">
            <div class="loading"></div>
        </div>

        @forelse($liveGames as $game)

            <div class="gname">
                <div class="livegame">
                    <p style="margin:0; text-transform:uppercase;">
                        {{ strtoupper($game->name) }}
                    </p>
                </div>
            </div>

            <div class="open">
                @if(
                    !empty($game->todayResult)
                    && ($game->todayResult->status ?? null) === 'declared'
                    && !empty($game->todayResult->result)
                )
                    <p style="margin:0; text-transform:uppercase;">
                        {{ is_numeric($game->todayResult->result) && $game->todayResult->result <= 9
                            ? str_pad($game->todayResult->result, 2, '0', STR_PAD_LEFT)
                            : $game->todayResult->result }}
                    </p>
                @else
                    <p style="margin:0;">
                        <strong class="waitimg">
                            <img class="lazy"
                                 src="{{ asset('tamplate/admin/upimages/d.gif') }}"
                                 alt="waiting"
                                 style="display:inline-block;"
                                 width="40"
                                 height="40">
                        </strong>
                    </p>
                @endif
            </div>

        @empty
            <div class="gname">
                <div class="livegame">
                    <p style="margin:0; text-transform:uppercase;">No Games Found</p>
                </div>
            </div>
        @endforelse

    </div>
</div>

<div class="addb" style="background:#fff; padding:20px; margin-top:10px; text-align:center; border:2px solid #000;">
    <h3>ADVERTISEMENT SECTION</h3>
</div>

<style>
    .loading {
        border-radius: 50%;
        background-color: rgba(0,255,255,0);
        width: 25px;
        height: 25px;
        border: 20px solid rgba(255,0,0,0.325);
        border-top: 20px solid rgb(255,0,0);
        animation: rotate 2s infinite;
    }

    @keyframes rotate {
        100% {
            rotate: 360deg;
        }
    }
</style>