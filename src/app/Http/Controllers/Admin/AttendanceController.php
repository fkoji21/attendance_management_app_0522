<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceEditRequest;
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
        $breakTimes = $attendance->breakTimes;

        return view('admin.attendance.show', compact('attendance', 'breakTimes'));
    }

    public function edit(Attendance $attendance)
    {
        $attendance->load('breakTimes');
        return view('admin.attendance.edit', compact('attendance'));
    }

    public function update(AttendanceEditRequest $request, Attendance $attendance)
    {
        // 更新処理（休憩時間の保存も必要に応じて追加）
        $attendance->update([
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'note' => $request->note,
        ]);

        // 休憩時間も1セットだけ更新する例（複数対応なら別処理）
        if ($request->filled('break_start') && $request->filled('break_end')) {
            $break = $attendance->breakTimes()->first();
            if ($break) {
                $break->update([
                    'break_start' => $request->break_start,
                    'break_end' => $request->break_end,
                ]);
            } else {
                $attendance->breakTimes()->create([
                    'break_start' => $request->break_start,
                    'break_end' => $request->break_end,
                ]);
            }
        }

        return redirect()->route('admin.attendance.show', $attendance)->with('message', '勤怠情報を更新しました');
    }

}
