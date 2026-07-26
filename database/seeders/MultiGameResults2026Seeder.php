<?php

namespace Database\Seeders;

use App\Models\ChartYear;
use App\Models\Game;
use App\Models\GameResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class MultiGameResults2026Seeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $year = 2026;

            /*
            |--------------------------------------------------------------------------
            | Format: DAY JAN FEB MAR APR MAY
            | Use "-" for missing / undeclared result.
            |--------------------------------------------------------------------------
            */
            $games = [
                'dehradun-city' => [
                    'name' => 'Dehradun City',
                    'table' => <<<'DATA'
1 34 96 87 65 95
2 35 42 87 78 18
3 63 96 68 31 88
4 68 87 99 24 69
5 50 84 70 17 71
6 38 67 65 74 60
7 67 42 42 67 97
8 12 9 79 80 28
9 65 94 95 9 51
10 17 89 47 67 81
11 43 19 64 51 79
12 77 94 94 42 54
13 55 17 4 8 96
14 88 12 82 63 21
15 12 16 0 68 60
16 69 91 90 7 81
17 57 7 28 32 54
18 89 36 34 17 38
19 49 59 45 91 68
20 91 99 14 46 41
21 88 41 18 38 47
22 7 89 73 78 69
23 38 69 96 1 58
24 7 45 36 29 7
25 74 56 49 11 43
26 89 81 44 28 83
27 41 14 17 68 81
28 96 - 18 39 96
29 99 - 68 97 68
30 40 - 7 - 51
31 - - - - -
DATA,
                ],

                'aligarh-night' => [
                    'name' => 'Aligarh Night',
                    'table' => <<<'DATA'
1 41 76 28 93 54
2 87 19 65 32 71
3 24 98 43 57 16
4 69 35 81 20 84
5 13 52 94 76 48
6 90 67 11 45 39
7 58 24 72 88 63
8 36 81 19 54 27
9 95 43 60 17 82
10 22 74 86 69 31
11 71 16 34 92 56
12 49 89 53 26 97
13 84 37 78 11 42
14 17 62 25 83 90
15 65 94 47 38 14
16 28 51 99 71 75
17 76 13 58 24 46
18 54 87 32 95 68
19 91 40 74 52 19
20 35 28 16 80 85
21 62 79 91 44 33
22 18 55 39 67 72
23 83 92 64 15 58
24 47 21 82 98 11
25 30 68 27 53 89
26 97 46 73 36 24
27 52 84 15 79 61
28 14 - 88 41 96
29 79 - 54 22 37
30 66 - 31 - 70
31 - - - - -
DATA,
                ],

                'pratapgarh-city' => [
                    'name' => 'Pratapgarh City',
                    'table' => <<<'DATA'
1 58 13 84 27 91
2 74 65 39 80 22
3 16 92 57 41 73
4 83 24 11 69 48
5 37 79 62 95 14
6 91 51 28 36 85
7 45 87 70 18 33
8 29 40 96 53 67
9 64 31 15 72 49
10 12 76 58 90 27
11 88 17 43 21 94
12 53 98 34 65 10
13 27 44 81 39 76
14 95 60 25 84 52
15 41 22 73 56 18
16 69 86 49 12 97
17 30 54 92 77 35
18 84 15 37 28 61
19 57 99 68 45 20
20 18 71 53 96 83
21 76 38 24 14 58
22 63 82 89 51 41
23 26 11 46 73 99
24 90 57 19 32 66
25 34 94 75 88 13
26 81 29 61 47 70
27 52 63 97 25 44
28 19 - 42 91 86
29 67 - 16 58 31
30 93 - 85 - 74
31 - - - - -
DATA,
                ],

                'allenabad' => [
                    'name' => 'Allenabad',
                    'table' => <<<'DATA'
1 42 77 18 63 95
2 89 26 71 14 37
3 53 91 46 82 20
4 17 38 94 55 68
5 75 12 27 96 41
6 31 84 65 29 73
7 98 43 10 87 52
8 24 69 88 35 16
9 61 57 32 74 90
10 13 95 79 21 48
11 87 30 56 64 19
12 45 82 11 97 76
13 70 18 39 42 84
14 36 63 92 15 58
15 94 47 25 89 33
16 28 76 60 53 12
17 66 14 81 38 97
18 51 88 43 70 24
19 22 54 17 91 65
20 83 97 58 26 49
21 39 20 75 84 11
22 90 72 34 61 86
23 15 49 67 30 54
24 64 81 23 98 72
25 47 35 99 16 27
26 71 66 50 79 93
27 10 58 84 44 31
28 96 - 29 67 80
29 58 - 73 19 46
30 34 - 15 - 88
31 - - - - -
DATA,
                ],

                'chatisgarh' => [
                    'name' => 'Chatisgarh',
                    'table' => <<<'DATA'
1 67 14 83 29 56
2 25 91 48 77 18
3 84 36 95 12 63
4 59 72 21 68 47
5 13 88 54 31 92
6 76 45 69 85 24
7 32 97 10 43 71
8 90 28 87 16 38
9 44 63 35 99 52
10 81 15 73 58 27
11 22 84 49 90 65
12 95 31 17 74 40
13 56 79 62 20 83
14 11 53 98 46 14
15 73 25 41 88 69
16 38 92 84 33 57
17 60 19 27 71 96
18 89 67 55 13 42
19 14 50 90 82 31
20 97 43 16 65 78
21 35 86 71 24 59
22 68 12 39 97 15
23 29 75 64 41 88
24 51 34 22 56 73
25 80 99 58 17 49
26 47 61 93 70 26
27 18 40 11 94 81
28 92 - 76 38 54
29 63 - 45 28 97
30 26 - 89 - 32
31 - - - - -
DATA,
                ],

                'elanabad' => [
                    'name' => 'Elanabad',
                    'table' => <<<'DATA'
1 62 17 84 39 51
2 29 95 46 72 18
3 73 41 12 87 64
4 15 68 99 24 35
5 90 53 27 81 76
6 38 22 71 43 97
7 54 86 19 66 28
8 11 74 63 14 82
9 97 35 58 91 47
10 45 61 83 32 13
11 24 89 34 56 79
12 78 16 92 25 60
13 31 57 49 88 21
14 84 93 15 67 55
15 19 28 76 40 98
16 66 81 23 75 44
17 52 47 90 11 69
18 87 39 14 58 26
19 13 72 61 94 83
20 95 24 37 19 52
21 41 65 85 78 16
22 58 12 54 31 91
23 76 98 29 62 34
24 22 43 68 17 87
25 64 59 11 96 73
26 37 84 45 53 20
27 81 26 97 89 65
28 49 - 32 46 12
29 18 - 74 27 93
30 56 - 88 - 41
31 - - - - -
DATA,
                ],

                'jeevan-shree' => [
                    'name' => 'Jeevan Shree',
                    'table' => <<<'DATA'
1 54 87 19 72 41
2 93 25 64 18 90
3 16 78 52 83 37
4 81 44 95 26 68
5 39 92 11 57 24
6 70 13 88 34 75
7 22 65 47 91 56
8 97 38 73 15 82
9 48 99 20 69 31
10 14 51 84 43 96
11 86 29 36 78 17
12 61 74 92 10 58
13 27 18 55 87 42
14 90 83 14 62 79
15 45 31 68 24 53
16 72 56 97 89 12
17 19 40 26 51 84
18 63 95 80 37 21
19 35 66 43 98 74
20 88 12 59 16 65
21 24 81 33 70 49
22 76 58 91 44 13
23 52 21 17 85 94
24 99 37 75 28 60
25 41 89 62 53 27
26 68 47 13 96 86
27 15 72 58 39 50
28 84 - 29 64 18
29 57 - 96 12 73
30 32 - 41 - 97
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
                    [
                        'is_active' => true,
                    ]
                );

                // Only replace January to May 2026 data for this game.
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

    /**
     * Parse rows in this format:
     * DAY JAN FEB MAR APR MAY
     */
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

                // Ignore unavailable / undeclared values.
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