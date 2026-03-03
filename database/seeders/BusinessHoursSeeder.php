<?php

namespace Database\Seeders;

use App\Models\BusinessHour;
use Illuminate\Database\Seeder;

class BusinessHoursSeeder extends Seeder
{
    public function run(): void
    {
        // 0=Zondag, 1=Maandag, ..., 6=Zaterdag
        $hours = [
            ['day_of_week' => 0, 'open_time' => '12:00', 'close_time' => '21:00', 'is_open' => true],  // Zondag
            ['day_of_week' => 1, 'open_time' => '11:00', 'close_time' => '22:00', 'is_open' => true],  // Maandag
            ['day_of_week' => 2, 'open_time' => '11:00', 'close_time' => '22:00', 'is_open' => true],  // Dinsdag
            ['day_of_week' => 3, 'open_time' => '11:00', 'close_time' => '22:00', 'is_open' => true],  // Woensdag
            ['day_of_week' => 4, 'open_time' => '11:00', 'close_time' => '22:00', 'is_open' => true],  // Donderdag
            ['day_of_week' => 5, 'open_time' => '11:00', 'close_time' => '23:00', 'is_open' => true],  // Vrijdag
            ['day_of_week' => 6, 'open_time' => '11:00', 'close_time' => '23:00', 'is_open' => true],  // Zaterdag
        ];

        foreach ($hours as $hour) {
            BusinessHour::updateOrCreate(
                ['day_of_week' => $hour['day_of_week']],
                $hour
            );
        }
    }
}
