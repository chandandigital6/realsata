<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\ContentBlock;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\SeoPage;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Carbon\CarbonPeriod;

class FrontController extends Controller
{
    public function home()
    {
        return $this->renderHome('front.home.index', 'home');
    }

    public function home_2()
    {
        return $this->renderHome('front.home.index_2', 'home_2');
    }

    public function home_3()
    {
        return $this->renderHome('front.home.index_3', 'home_3');
    }

    private function renderHome(string $view, string $pageKey)
    {
        $now = CarbonImmutable::now(self::TIMEZONE);
        $today = $now->toDateString();
        $yesterday = $now->subDay()->toDateString();
        $games = $this->games();
        $gameSections = $games->chunk(18);
        $chartGameSections = $games->chunk(18);
        $recentResults = $this->results($games, $yesterday, $today);
        $declared = $recentResults->filter(fn ($row) => $this->declared($row));
        $todayTableResults = $declared
            ->filter(fn ($row) => $this->dateOf($row->result_date) === $today)
            ->keyBy('game_id');
        $yesterdayResults = $declared
            ->filter(fn ($row) => $this->dateOf($row->result_date) === $yesterday)
            ->keyBy('game_id');
        $liveItems = $this->selectLive($games, $recentResults, $now);

        // Compatibility for other existing Blade sections.
        $recentById = $recentResults->keyBy('id');
        $todayResults = $liveItems
            ->filter(fn ($item) => $item['result']['is_live'])
            ->mapWithKeys(fn ($item) => [
                $item['id'] => $recentById->get($item['result']['id']),
            ])->filter();
        $liveIds = $liveItems->pluck('id');
        $gamesById = $games->keyBy('id');
        $liveGames = $liveIds->map(fn ($id) => $gamesById->get($id))->filter()->values();

        $startDate = $now->startOfMonth();
        $endDate = $now->endOfMonth();
        $dates = CarbonPeriod::create($startDate, $endDate);
        $monthlyResults = GameResult::query()
            ->whereIn('game_id', $games->pluck('id'))
            ->whereDate('result_date', '>=', $startDate->toDateString())
            ->whereDate('result_date', '<=', $endDate->toDateString())
            ->where('status', 'declared')->orderByDesc('id')->get()
            ->unique(fn ($row) => $row->game_id . ':' . $this->dateOf($row->result_date))
            ->groupBy(fn ($row) => $this->dateOf($row->result_date));
        $seo = SeoPage::where('page_key', $pageKey)->first();
        $allAdvertisements = Advertisement::query()->where('is_active', true)
            ->whereIn('position', ['top', 'middle', 'bottom', 'sidebar'])
            ->latest()->get()->groupBy('position');
        $advertisements = $allAdvertisements->get('top', collect());
        $topAdvertisements = $advertisements;
        $middleAdvertisement = $allAdvertisements->get('middle', collect())->first();
        $bottomAdvertisement = $allAdvertisements->get('bottom', collect())->first();
        $sidebarAdvertisement = $allAdvertisements->get('sidebar', collect())->first();

        return view($view, compact(
            'games', 'gameSections', 'chartGameSections', 'today', 'yesterday',
            'todayResults', 'todayTableResults', 'yesterdayResults', 'liveItems', 'liveGames',
            'dates', 'monthlyResults', 'seo', 'advertisements', 'topAdvertisements',
            'middleAdvertisement', 'bottomAdvertisement', 'sidebarAdvertisement'
        ));
    }

    public function chart()
    {
        $games = Game::query()
            ->where('is_active', true)
            ->with([
                'chartYears' => function ($query) {
                    $query->where('is_active', true)
                        ->orderByDesc('year');
                }
            ])
            ->orderBy('sort_order')
            ->get();

        $seo = SeoPage::where('page_key', 'chart')->first();

        return view('front.chart.index', compact('games', 'seo'));
    }








    public function gameRecord(string $slug)
    {
        $game = Game::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $year = now('Asia/Kolkata')->year;

        $results = GameResult::where('game_id', $game->id)
            ->whereYear('result_date', $year)
            ->orderBy('result_date')
            ->get();

        $seo = SeoPage::where('game_id', $game->id)
            ->whereNull('year')
            ->first();

        if (!$seo) {
            $seo = SeoPage::where('page_key', 'game-record')->first();
        }

        $canonicalUrl = route('game.record', $game->slug);

        if ($seo) {
            $seo = clone $seo;
            $seo->canonical_url = $canonicalUrl;

            $replace = [
                '{game}' => $game->name,
                '{slug}' => $game->slug,
                '{year}' => $year,
            ];

            $seo->meta_title = $seo->meta_title
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_title)
                : null;

            $seo->meta_description = $seo->meta_description
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_description)
                : null;

            $seo->meta_keywords = $seo->meta_keywords
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_keywords)
                : null;

            $seo->og_title = $seo->og_title
                ? str_replace(array_keys($replace), array_values($replace), $seo->og_title)
                : null;

            $seo->og_description = $seo->og_description
                ? str_replace(array_keys($replace), array_values($replace), $seo->og_description)
                : null;
        } else {
            $seo = (object) [
                'meta_title'       => "{$game->name} Record Chart",
                'meta_description' => "{$game->name} record chart, old result and complete satta chart.",
                'meta_keywords'    => "{$game->name} record, {$game->name} chart",
                'canonical_url'    => $canonicalUrl,
                'og_title'         => null,
                'og_description'   => null,
                'og_image'         => null,
                'schema_markup'    => null,
            ];
        }

        $contentBlocks = ContentBlock::where('game_id', $game->id)
            ->whereNull('year')
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        return view('front.game.record', compact(
            'game',
            'results',
            'year',
            'seo',
            'contentBlocks'
        ));
    }


    public function yearRecord(string $slug, int $year)
    {
        $game = Game::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $results = GameResult::where('game_id', $game->id)
            ->whereYear('result_date', $year)
            ->orderBy('result_date')
            ->get();

        $seo = SeoPage::where('game_id', $game->id)
            ->where('year', $year)
            ->first();

        if (!$seo) {
            $seo = SeoPage::where('page_key', 'year-record')->first();
        }

        $canonicalUrl = route('game.year-record', [$game->slug, $year]);

        if ($seo) {
            $seo = clone $seo;
            $seo->canonical_url = $canonicalUrl;

            $replace = [
                '{game}' => $game->name,
                '{slug}' => $game->slug,
                '{year}' => $year,
            ];

            $seo->meta_title = $seo->meta_title
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_title)
                : null;

            $seo->meta_description = $seo->meta_description
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_description)
                : null;

            $seo->meta_keywords = $seo->meta_keywords
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_keywords)
                : null;

            $seo->og_title = $seo->og_title
                ? str_replace(array_keys($replace), array_values($replace), $seo->og_title)
                : null;

            $seo->og_description = $seo->og_description
                ? str_replace(array_keys($replace), array_values($replace), $seo->og_description)
                : null;
        } else {
            $seo = (object) [
                'meta_title'       => "{$game->name} {$year} Record Chart",
                'meta_description' => "{$game->name} {$year} record chart, old result and complete satta chart.",
                'meta_keywords'    => "{$game->name} {$year} record, {$game->name} {$year} chart",
                'canonical_url'    => $canonicalUrl,
                'og_title'         => null,
                'og_description'   => null,
                'og_image'         => null,
                'schema_markup'    => null,
            ];
        }

        $contentBlocks = ContentBlock::where('game_id', $game->id)
            ->where('year', $year)
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        return view('front.game.year_record', compact(
            'game',
            'results',
            'year',
            'seo',
            'contentBlocks'
        ));
    }




    public function contactUs()
    {
        $seo = SeoPage::where('page_key', 'contact-us')->first();
        return view('front.contact-us.index', compact('seo'));
    }

    public function privacyPolicy()
    {
        $seo = SeoPage::where('page_key', 'privacy-policy')->first();
        return view('front.privacy-policy.index', compact('seo'));
    }

    public function termsConditions()
    {
        $seo = SeoPage::where('page_key', 'terms-conditions')->first();
        return view('front.terms-conditions.index', compact('seo'));
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
            ->orderByDesc('id')->get()
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
        $indexed = $results->keyBy(fn ($row) => $row->game_id . ':' . $this->dateOf($row->result_date));
        $items = collect();

        foreach ($games as $game) {
            foreach ([-1, 0, 1] as $offset) {
                $date = $now->addDays($offset)->toDateString();
                $scheduledAt = $this->scheduledAt($date, $game->result_time);
                if (!$scheduledAt) {
                    continue;
                }
                $row = $indexed->get($game->id . ':' . $date);
                $expiresAt = $scheduledAt->addMinutes($this->minutes($row));
                if ($now->gte($expiresAt)) {
                    continue;
                }
                $current = $now->gte($scheduledAt->subMinutes(5));
                $visible = $now->gte($scheduledAt) && $this->declared($row);
                $item = $this->payload($game, $row, $visible);
                $item['result']['result_date'] = $date;
                $item['scheduled_at'] = $scheduledAt->toIso8601String();
                $item['display_time'] = $scheduledAt->format('h:i A');
                $item['state'] = $visible ? 'declared' : ($current ? 'waiting' : 'upcoming');
                $item['_priority'] = $current ? 0 : 1;
                $item['_timestamp'] = $scheduledAt->timestamp;
                $items->push($item);
            }
        }

        return $items->sortBy([['_priority', 'asc'], ['_timestamp', 'asc'], ['id', 'asc']])
            ->unique('id')->take(4)->values()->map(function ($item) {
                unset($item['_priority'], $item['_timestamp']);
                return $item;
            });
    }
}
