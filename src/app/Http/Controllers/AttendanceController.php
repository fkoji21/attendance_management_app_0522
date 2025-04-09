<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
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

        $status = 'none';

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

    public function monthly(Request $request)
    {
        $user = Auth::user();

        // クエリパラメータで月指定（例：?month=2025-04）、なければ今月
        $month = $request->query('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // 勤怠データをその月の範囲で取得
        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'asc')
            ->get();

        return view('attendance.list', [
            'attendances' => $attendances,
            'currentMonth' => $startOfMonth,
            'prevMonth' => $startOfMonth->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $startOfMonth->copy()->addMonth()->format('Y-m'),
        ]);
    }

    public function show(Attendance $attendance)
    {
        // 自分以外の勤怠は見れないように制限
        if ($attendance->user_id !== Auth::id()) {
            abort(403);
        }

        $attendance->load('user', 'breakTimes');
        $breakTimes = $attendance->breakTimes;

        return view('attendance.show', compact('attendance', 'breakTimes'));
    }

}
