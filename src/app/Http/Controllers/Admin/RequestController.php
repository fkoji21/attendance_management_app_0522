<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRequest;
use Carbon\Carbon;

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

        return view('admin.requests.index', compact('pendingRequests', 'approvedRequests'));
    }

    public function show(AttendanceRequest $request)
    {
        $request->load(['attendance.user']);

        return view('admin.requests.show', compact('request'));
    }

    public function approve(AttendanceRequest $request)
    {
        if ($request->is_approved) {
            return back()->with('message', 'すでに承認済みです');
        }

        $attendance = $request->attendance;
        $attendance->update([
            'clock_in' => Carbon::parse($attendance->date . ' ' . $request->requested_clock_in),
            'clock_out' => Carbon::parse($attendance->date . ' ' . $request->requested_clock_out),
            'note' => $request->requested_note,
        ]);

        $request->update(['is_approved' => true]);

        return redirect()->route('requests.index')->with('message', '承認が完了しました');
    }
}
