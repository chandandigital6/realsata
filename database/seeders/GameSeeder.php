<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Game;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $games = [
            ['Disawar', '02:00:00'],
            ['Palika Bazar', '13:00:00'],
            ['Sadar Bazar', '13:30:00'],
            ['Prayagraj', '14:00:00'],
            ['Gwalior', '14:20:00'],
            ['Delhi Bazaar', '14:50:00'],
            ['Delhi Darbar', '15:40:00'],
            ['Shri Ganesh', '16:10:00'],
            ['Roop Nagar', '17:00:00'],
            ['Agra', '17:30:00'],
            ['Faridabad', '18:20:00'],
            ['Fatehpur', '19:10:00'],
            ['Alwar', '19:30:00'],
            ['Ghaziabad', '21:30:00'],
            ['Noida Night', '22:00:00'],
            ['Dwarka', '22:20:00'],
            ['Gali', '23:50:00'],
        ];

        foreach ($games as $index => [$name, $time]) {
            Game::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'result_time' => $time,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
