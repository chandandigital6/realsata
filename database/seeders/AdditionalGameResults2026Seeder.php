<?php

namespace Database\Seeders;

use App\Models\ChartYear;
use App\Models\Game;
use App\Models\GameResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class AdditionalGameResults2026Seeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $year = 2026;

            $games = [
                'goa-night' => [
                    'name' => 'Goa Night',
                    'table' => <<<'DATA'
1 28 63 94 15 72
2 81 17 36 89 24
3 54 90 68 42 57
4 13 45 21 76 99
5 66 32 87 58 10
6 95 74 49 31 84
7 40 11 73 97 63
8 79 56 18 24 46
9 22 85 91 67 35
10 61 29 55 80 16
11 97 43 30 12 88
12 14 69 82 54 27
13 58 96 41 23 75
14 33 20 64 90 51
15 86 78 15 37 92
16 47 52 99 61 18
17 25 84 57 44 70
18 91 38 12 83 59
19 35 65 76 28 41
20 72 19 53 95 66
21 10 98 27 49 81
22 63 57 88 16 34
23 89 24 45 71 93
24 31 79 60 52 14
25 50 13 97 38 68
26 74 41 22 86 55
27 18 92 71 65 29
28 83 - 34 19 96
29 56 - 80 73 47
30 21 - 11 - 62
31 - - - - -
DATA,
                ],

                'meghacity' => [
                    'name' => 'Meghacity',
                    'table' => <<<'DATA'
1 73 41 28 95 16
2 24 88 67 32 79
3 91 15 54 83 42
4 36 70 12 58 97
5 65 29 89 17 34
6 18 94 46 71 53
7 82 37 63 24 90
8 49 56 20 87 61
9 77 13 98 45 28
10 31 85 35 69 74
11 96 44 72 10 52
12 57 68 19 94 81
13 14 91 84 36 25
14 69 22 51 78 88
15 43 76 16 29 64
16 87 32 93 55 11
17 26 59 47 82 38
18 71 97 24 13 85
19 54 18 79 66 43
20 92 63 31 48 96
21 35 80 58 91 17
22 61 27 74 22 69
23 19 53 86 37 40
24 84 12 45 73 98
25 47 65 90 26 57
26 10 39 62 84 72
27 58 74 13 41 30
28 95 - 56 97 63
29 22 - 81 14 49
30 66 - 38 - 92
31 - - - - -
DATA,
                ],

                'amritpur' => [
                    'name' => 'Amritpur',
                    'table' => <<<'DATA'
1 46 81 23 67 94
2 72 19 58 34 15
3 11 93 76 82 49
4 89 42 17 56 71
5 64 28 91 13 37
6 25 74 45 98 60
7 97 36 62 21 84
8 53 88 14 79 26
9 18 51 83 47 95
10 75 12 39 65 42
11 30 97 54 16 73
12 86 44 20 91 58
13 41 69 98 35 11
14 67 25 71 84 52
15 92 57 46 29 88
16 14 83 65 72 31
17 59 38 27 94 76
18 80 95 53 10 68
19 37 16 89 63 24
20 99 61 32 55 81
21 22 48 74 87 13
22 63 90 11 40 56
23 78 34 96 18 69
24 54 77 43 92 20
25 29 14 87 31 97
26 85 66 50 78 45
27 33 52 19 64 70
28 91 - 82 26 38
29 47 - 36 59 93
30 12 - 68 - 57
31 - - - - -
DATA,
                ],

                'mirjapur' => [
                    'name' => 'Mirjapur',
                    'table' => <<<'DATA'
1 57 84 31 69 25
2 13 42 95 18 73
3 88 27 56 91 40
4 34 76 12 53 87
5 61 19 83 28 94
6 22 97 45 74 16
7 79 38 68 11 59
8 46 63 20 85 32
9 91 15 77 36 81
10 29 54 14 98 47
11 75 89 62 43 10
12 18 33 96 57 72
13 84 70 39 26 51
14 41 12 88 79 65
15 96 58 23 34 90
16 52 81 71 66 13
17 24 45 17 92 76
18 67 99 84 15 38
19 30 21 59 80 44
20 93 64 41 27 98
21 11 86 52 61 35
22 74 49 90 14 68
23 58 17 33 95 82
24 26 72 69 48 19
25 87 35 11 56 63
26 43 92 78 22 97
27 65 53 24 89 41
28 19 - 85 37 54
29 82 - 46 73 12
30 36 - 94 - 79
31 - - - - -
DATA,
                ],

                'rampur' => [
                    'name' => 'Rampur',
                    'table' => <<<'DATA'
1 38 91 47 16 82
2 74 25 63 89 34
3 12 68 95 41 57
4 83 39 21 76 10
5 56 84 72 33 98
6 97 17 44 65 29
7 23 52 86 14 71
8 69 78 31 92 48
9 45 11 59 27 83
10 88 63 15 54 20
11 34 96 81 73 66
12 79 42 28 18 91
13 16 87 67 46 35
14 92 30 53 84 12
15 51 74 99 39 77
16 27 18 36 95 43
17 85 61 70 22 58
18 40 93 49 68 14
19 73 57 24 81 90
20 19 33 88 56 47
21 64 80 13 97 25
22 31 46 75 42 69
23 98 27 62 11 84
24 54 72 17 87 38
25 66 15 91 30 53
26 11 89 40 79 96
27 77 54 58 24 61
28 43 - 84 63 18
29 90 - 26 52 74
30 58 - 97 - 31
31 - - - - -
DATA,
                ],
            ];

            foreach ($games as $slug => $gameData) {
                $game = Game::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $gameData['name'] ?? Str::title(str_replace('-', ' ', $slug)),
                        'result_time' => null,
                        'sort_order' => (Game::max('sort_order') ?? 0) + 1,
                        'is_active' => true,
                    ]
                );

                ChartYear::updateOrCreate(
                    [
                        'game_id' => $game->id,
                        'year' => $year,
                    ],
                    ['is_active' => true]
                );

                GameResult::where('game_id', $game->id)
                    ->whereBetween('result_date', [
                        "{$year}-01-01",
                        "{$year}-05-31",
                    ])
                    ->delete();

                $insertRows = $this->parseTable(
                    gameName: $gameData['name'],
                    gameId: $game->id,
                    year: $year,
                    table: $gameData['table']
                );

                foreach (array_chunk($insertRows, 500) as $chunk) {
                    GameResult::insert($chunk);
                }
            }
        });
    }

    private function parseTable(
        string $gameName,
        int $gameId,
        int $year,
        string $table
    ): array {
        $rows = [];
        $timestamp = now();
        $lines = preg_split('/\R/', trim($table));

        foreach ($lines as $lineNumber => $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $parts = preg_split('/\s+/', $line);

            if (count($parts) !== 6) {
                throw new RuntimeException(
                    sprintf(
                        '%s: invalid row at line %d. Expected DAY + 5 months, got: %s',
                        $gameName,
                        $lineNumber + 1,
                        $line
                    )
                );
            }

            $day = (int) array_shift($parts);

            foreach ($parts as $monthIndex => $rawResult) {
                $month = $monthIndex + 1;
                $rawResult = trim((string) $rawResult);

                if ($rawResult === '' || $rawResult === '-') {
                    continue;
                }

                if (!checkdate($month, $day, $year)) {
                    continue;
                }

                if (!preg_match('/^\d{1,2}$/', $rawResult)) {
                    throw new RuntimeException(
                        sprintf(
                            '%s: invalid result "%s" for %04d-%02d-%02d',
                            $gameName,
                            $rawResult,
                            $year,
                            $month,
                            $day
                        )
                    );
                }

                $resultNumber = (int) $rawResult;

                if ($resultNumber < 0 || $resultNumber > 99) {
                    throw new RuntimeException(
                        sprintf(
                            '%s: result out of range "%s" for %04d-%02d-%02d',
                            $gameName,
                            $rawResult,
                            $year,
                            $month,
                            $day
                        )
                    );
                }

                $rows[] = [
                    'game_id' => $gameId,
                    'result_date' => sprintf('%04d-%02d-%02d', $year, $month, $day),
                    'result' => str_pad((string) $resultNumber, 2, '0', STR_PAD_LEFT),
                    'status' => 'declared',
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }
        }

        return $rows;
    }
}