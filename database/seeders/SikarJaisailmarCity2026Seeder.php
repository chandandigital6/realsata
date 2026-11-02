<?php

namespace Database\Seeders;

use App\Models\ChartYear;
use App\Models\Game;
use App\Models\GameResult;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

/**
 * DEMO / FAKE RESULTS ONLY — NOT HISTORICAL OR VERIFIED GAME RESULTS.
 * Seeds January 1 through November 2, 2026 (inclusive).
 */
class SikarJaisailmarCity2026Seeder extends Seeder
{
    public function run(): void
    {
        $games = [
            'sikar-city' => 'Sikar City',
            'jaisailmar-city' => 'Jaisailmar City',
        ];

        $start = CarbonImmutable::create(2026, 1, 1, 0, 0, 0);
        $end = CarbonImmutable::create(2026, 11, 2, 0, 0, 0);

        foreach ($games as $slug => $name) {
            $game = Game::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'result_time' => null,
                    'sort_order' => (Game::max('sort_order') ?? 0) + 1,
                    'is_active' => true,
                ]
            );

            for ($date = $start; $date->lte($end); $date = $date->addDay()) {
                // firstOrCreate does NOT change an existing result on subsequent runs.
                // Random values range from 00 through 99, always as two-character strings.
                GameResult::firstOrCreate(
                    [
                        'game_id' => $game->id,
                        'result_date' => $date->format('Y-m-d'),
                    ],
                    [
                        'result' => sprintf('%02d', random_int(0, 99)),
                        'status' => 'declared',
                    ]
                );
            }

            ChartYear::updateOrCreate(
                ['game_id' => $game->id, 'year' => 2026],
                ['is_active' => true]
            );
        }
    }
}
