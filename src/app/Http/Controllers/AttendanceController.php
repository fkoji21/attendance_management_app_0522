<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::with('breakTimes')
            ->where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        $status = null;

        if ($attendance) {
            $latestBreak = $attendance->breakTimes->last();

            if ($latestBreak && is_null($latestBreak->break_end)) {
                $status = 'on_break';
            } elseif (is_null($attendance->clock_out)) {
                $status = 'working';
            } else {
                $status = 'finished';
            }
        }

        return view('attendance.index', compact('attendance', 'status'));

    }

    public function start()
    {
        $user = Auth::user();

        // 今日すでに出勤しているかチェック
        $alreadyStarted = Attendance::where('user_id', $user->id)
            ->where('date', Carbon::today()->toDateString())
            ->exists();

        if ($alreadyStarted) {
            return back()->with('message', 'すでに出勤済みです。');
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today()->toDateString(),
            'clock_in' => now(),
        ]);

        return redirect()->route('attendance.index')->with('message', '出勤しました！');
    }

    public function clockOut()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance && is_null($attendance->clock_out)) {
            $attendance->clock_out = now();
            $attendance->save();
            $message = 'お疲れ様でした。';
        } else {
            $message = '退勤済み、または出勤記録がありません。';
        }

        return redirect()->route('attendance.index')->with('message', $message);
    }

    public function startBreak()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('clock_in', today())
            ->first();

        if ($attendance) {
            $attendance->breakTimes()->create([
                'break_start' => now(),
            ]);
        }

        return back()->with('message', '休憩を開始しました');
    }

    public function endBreak()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('clock_in', today())
            ->first();

        if ($attendance) {
            $latestBreak = $attendance->breakTimes()
                ->whereNull('break_end')
                ->latest('break_start')
                ->first();

            if ($latestBreak) {
                $latestBreak->update(['break_end' => now()]);
            }
        }

        return back()->with('message', '休憩を終了しました');
    }

}
