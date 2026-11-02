<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameResult;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class GameResultApiController extends Controller
{
    public function index(Request $request)
{
    $date = $request->date ?? Carbon::today('Asia/Kolkata')->format('Y-m-d');

    $games = Game::with([
            'results' => function ($q) use ($date) {
                $q->whereDate('result_date', $date);
            }
        ])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $data = $games->map(function ($game) {
        $todayResult = $game->results->first();

        return [
            'id'          => $game->id,
            'name'        => $game->name,
            'slug'        => $game->slug,
            'result_time' => $game->result_time,
            'sort_order'  => $game->sort_order,
            'is_active'   => (bool) $game->is_active,

            'result' => [
                'id' => $todayResult?->id,

                'result_date' => $todayResult?->result_date
                    ? Carbon::parse($todayResult->result_date)->format('Y-m-d')
                    : null,

                // IMPORTANT: yaha result hide/null nahi karna hai
                'result' => $todayResult?->result,

                'status' => $todayResult?->status ?? 'waiting',

                'show_minutes' => !empty($todayResult?->show_minutes)
                    ? (int) $todayResult->show_minutes
                    : 10,

                'updated_at' => $todayResult?->updated_at
                    ? Carbon::parse($todayResult->updated_at)
                        ->timezone('Asia/Kolkata')
                        ->format('Y-m-d H:i:s')
                    : null,
            ],
        ];
    })->values();

    return response()->json([
        'success' => true,
        'date'    => $date,
        'games'   => $data,
    ]);
}

    public function live()
    {
        $now = CarbonImmutable::now(self::TIMEZONE);
        return response()->json([
            'success' => true,
            'date' => $now->toDateString(),
            'time' => $now->format('H:i:s'),
            'timezone' => self::TIMEZONE,
            'games' => $this->selectLive(now: $now),
        ])->header('Cache-Control', 'no-store, private');
    }

    public function homeLiveResults(Request $request)
    {
        return $this->live();
    }

 public function chartGames()
    {
        $games = Game::query()
            ->where('is_active', true)
            ->with(['chartYears' => function ($query) {
                $query->where('is_active', true)->orderByDesc('year');
            }])
            ->orderBy('sort_order')
            ->get()
            ->map(function ($game) {
                return [
                    'id'          => $game->id,
                    'name'        => $game->name,
                    'slug'        => $game->slug,
                    'result_time' => $game->result_time,
                    'sort_order'  => $game->sort_order,
                    'chartYears'  => $game->chartYears->map(function ($year) {
                        return [
                            'year' => $year->year,
                        ];
                    })->values(),
                ];
            })->values();

        return response()->json([
            'success' => true,
            'games' => $games,
        ]);
    }

    public function gameYearRecord(string $slug, int $year)
    {
        $game = Game::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $results = GameResult::where('game_id', $game->id)
            ->whereYear('result_date', $year)
            ->orderBy('result_date')
            ->get()
            ->map(function ($result) {
                return [
                    'result_date' => $result->result_date
                        ? Carbon::parse($result->result_date)->format('Y-m-d')
                        : null,
                    'result' => $result->result,
                    'status' => $result->status,
                ];
            })->values();

        return response()->json([
            'success' => true,
            'game' => [
                'id'          => $game->id,
                'name'        => $game->name,
                'slug'        => $game->slug,
                'result_time' => $game->result_time,
            ],
            'year'    => $year,
            'results' => $results,
        ]);
    }






    private const TIMEZONE = 'Asia/Kolkata';
    private const DEFAULT_SHOW_MINUTES = 15;

    private function games(): Collection
    {
        return Game::query()->where('is_active', true)
            ->orderBy('sort_order')->orderBy('id')->get();
    }

    private function dateOf($value): string
    {
        return CarbonImmutable::parse($value)->toDateString();
    }

    private function results(Collection $games, string $from, string $to): Collection
    {
        return GameResult::query()->whereIn('game_id', $games->pluck('id'))
            ->whereDate('result_date', '>=', $from)
            ->whereDate('result_date', '<=', $to)
            ->orderByDesc('updated_at')->orderByDesc('id')->get()
            ->unique(fn ($row) => $row->game_id . ':' . $this->dateOf($row->result_date))
            ->values();
    }

    private function declared($row): bool
    {
        return $row !== null && $row->status === 'declared' && filled($row->result);
    }

    private function minutes($row): int
    {
        $minutes = (int) ($row?->show_minutes ?? self::DEFAULT_SHOW_MINUTES);
        return $minutes > 0 ? $minutes : self::DEFAULT_SHOW_MINUTES;
    }

    private function scheduledAt(string $date, ?string $time): ?CarbonImmutable
    {
        $time = strtoupper(trim((string) $time));
        $time = preg_replace('/\s*(AM|PM)$/', ' $1', $time);
        foreach (['H:i:s', 'H:i', 'h:i A', 'g:i A', 'h:i:s A', 'g:i:s A'] as $format) {
            try {
                $value = $date . ' ' . $time;
                $pattern = 'Y-m-d ' . $format;
                if (CarbonImmutable::canBeCreatedFromFormat($value, $pattern)) {
                    return CarbonImmutable::createFromFormat('!' . $pattern, $value, self::TIMEZONE);
                }
            } catch (\InvalidArgumentException $exception) {
                // Try the next supported time format.
            }
        }
        if ($time !== '') {
            logger()->warning('Invalid game result_time', ['time' => $time]);
        }
        return null;
    }

    private function payload($game, $row, bool $visible): array
    {
        return [
            'id' => $game->id,
            'name' => $game->name,
            'slug' => $game->slug,
            'result_time' => $game->result_time,
            'sort_order' => $game->sort_order,
            'is_active' => (bool) $game->is_active,
            'result' => [
                'id' => $visible ? $row?->id : null,
                'result_date' => $row ? $this->dateOf($row->result_date) : null,
                'result' => $visible ? $row?->result : null,
                'status' => $visible ? 'declared' : 'waiting',
                'show_minutes' => $this->minutes($row),
                'updated_at' => $visible && $row?->updated_at
                    ? $row->updated_at->copy()->timezone(self::TIMEZONE)->format('Y-m-d H:i:s')
                    : null,
                'is_live' => $visible,
            ],
        ];
    }

    private function selectLive(
        ?Collection $games = null,
        ?Collection $results = null,
        ?CarbonImmutable $now = null
    ): Collection {
        $now = ($now ?? CarbonImmutable::now(self::TIMEZONE))->timezone(self::TIMEZONE);
        $games ??= $this->games();
        $results ??= $this->results($games, $now->subDay()->toDateString(), $now->toDateString());
        $gamesById = $games->keyBy('id');
        $indexed = $results->keyBy(fn ($row) => $row->game_id . ':' . $this->dateOf($row->result_date));
        $items = collect();

        // Declared results are independent of the scheduled waiting window.
        // A new update starts its own finite live duration and wins priority.
        foreach ($results as $row) {
            $game = $gamesById->get($row->game_id);
            if (!$game || !$this->declared($row) || !$row->updated_at) {
                continue;
            }
            $date = $this->dateOf($row->result_date);
            if (!in_array($date, [$now->toDateString(), $now->subDay()->toDateString()], true)) {
                continue;
            }
            $updatedAt = CarbonImmutable::instance($row->updated_at)->timezone(self::TIMEZONE);
            if ($updatedAt->gt($now) || $now->gte($updatedAt->addMinutes($this->minutes($row)))) {
                continue;
            }
            $scheduledAt = $this->scheduledAt($date, $game->result_time);
            $item = $this->payload($game, $row, true);
            $item['scheduled_at'] = $scheduledAt?->toIso8601String();
            $item['display_time'] = $scheduledAt?->format('h:i A') ?? '';
            $item['state'] = 'declared';
            $item['_priority'] = 0;
            $item['_order'] = -$updatedAt->timestamp;
            $item['_result_id'] = -$row->id;
            $items->push($item);
        }

        foreach ($games as $game) {
            foreach ([0, 1] as $offset) {
                $date = $now->addDays($offset)->toDateString();
                $scheduledAt = $this->scheduledAt($date, $game->result_time);
                if (!$scheduledAt) {
                    continue;
                }
                $row = $indexed->get($game->id . ':' . $date);
                // A declared cycle must not reappear as WAIT after live expiry.
                if ($this->declared($row)) {
                    continue;
                }
                if ($now->gte($scheduledAt->addMinutes($this->minutes($row)))) {
                    continue;
                }
                $current = $now->gte($scheduledAt->subMinutes(5));
                $item = $this->payload($game, $row, false);
                $item['result']['result_date'] = $date;
                $item['scheduled_at'] = $scheduledAt->toIso8601String();
                $item['display_time'] = $scheduledAt->format('h:i A');
                $item['state'] = $current ? 'waiting' : 'upcoming';
                $item['_priority'] = $current ? 1 : 2;
                $item['_order'] = $scheduledAt->timestamp;
                $item['_result_id'] = 0;
                $items->push($item);
            }
        }

        return $items->sortBy([
            ['_priority', 'asc'], ['_order', 'asc'], ['_result_id', 'asc'], ['id', 'asc'],
        ])->unique('id')->take(4)->values()->map(function ($item) {
            unset($item['_priority'], $item['_order'], $item['_result_id']);
            return $item;
        });
    }
}
