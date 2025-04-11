<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    /**
     * 管理者用 日次勤怠一覧画面
     */
    public function daily(Request $request)
    {
        // クエリパラメータ ?date=2025-04-11 があれば使う。なければ今日。
        $date = $request->input('date', Carbon::today()->toDateString());

        // 指定日の勤怠一覧を取得（ユーザーと休憩時間を取得済にしておく）
        $attendances = Attendance::with(['user', 'breakTimes'])
            ->whereDate('date', $date)
            ->orderBy('user_id') // 任意（名前順にしたいならuser.nameのjoinが必要）
            ->get();

        return view('admin.attendance.daily', [
            'attendances' => $attendances,
            'date' => $date,
            'prevDate' => Carbon::parse($date)->subDay()->toDateString(),
            'nextDate' => Carbon::parse($date)->addDay()->toDateString(),
        ]);
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('user', 'breakTimes');
        return view('admin.attendance.show', compact('attendance'));
    }

}
