<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $pendingRequests = AttendanceRequest::with(['attendance.user'])
            ->where('is_approved', false)
            ->orderByDesc('created_at')
            ->get();

        $approvedRequests = AttendanceRequest::with(['attendance.user'])
            ->where('is_approved', true)
            ->orderByDesc('created_at')
            ->get();

        return view('requests.index', compact('pendingRequests', 'approvedRequests'));
    }

    public function show(AttendanceRequest $request)
    {
        // 自分以外の修正申請は見れないように制限
        if ($request->attendance->user_id !== Auth::id()) {
            abort(403);
        }

        // Eager Load を明示
        $request->load('attendance');

        return view('requests.show', compact('request'));
    }

    public function approve(AttendanceRequest $request)
    {
        // すでに承認済みの場合は何もしない
        if ($request->is_approved) {
            return back()->with('message', 'すでに承認済みです');
        }

        // 勤怠情報を更新
        $attendance = $request->attendance;
        $attendance->update([
            'clock_in' => $request->requested_clock_in,
            'clock_out' => $request->requested_clock_out,
            'note' => $request->requested_note,
        ]);

        // 承認済みに変更
        $request->update(['is_approved' => true]);

        return redirect()->route('requests.index')->with('message', '承認が完了しました');
    }

}
