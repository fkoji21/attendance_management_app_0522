<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // 管理者以外の全ユーザー
        $users = User::where('is_admin', false)->get();

        foreach ($users as $user) {
            // 過去10日分の勤怠データを生成
            for ($i = 1; $i <= 10; $i++) {
                $date = Carbon::today()->subDays($i);

                // 出勤・退勤時刻を決定
                $clockIn = $date->copy()->setTime(rand(8, 9), rand(0, 59)); // 8:00〜9:59
                $clockOut = $clockIn->copy()->addHours(8)->addMinutes(rand(0, 30)); // 8〜8.5時間勤務

                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date->toDateString(),
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'note' => '通常勤務',
                ]);

                // ランダムに 0〜2 回休憩を入れる
                $breakCount = rand(0, 2);
                for ($j = 0; $j < $breakCount; $j++) {
                    $breakStart = $clockIn->copy()->addHours(rand(2, 4))->addMinutes(rand(0, 30));
                    $breakEnd = $breakStart->copy()->addMinutes(rand(15, 45));

                    BreakTime::create([
                        'attendance_id' => $attendance->id,
                        'break_start' => $breakStart,
                        'break_end' => $breakEnd,
                    ]);
                }
            }
        }
    }
}
