<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\BreakTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BreakTimeFactory extends Factory
{
    protected $model = BreakTime::class;

    public function definition(): array
    {
        $start = Carbon::now()->subHours(rand(4, 6));
        $end = (clone $start)->addMinutes(rand(15, 60));

        return [
            'attendance_id' => Attendance::inRandomOrder()->first()->id,
            'break_start' => $start,
            'break_end' => $end,
        ];
    }
}
