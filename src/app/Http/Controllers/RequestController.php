<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $pending = AttendanceRequest::whereHas('attendance', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('is_approved', false)->with('attendance')->get();

        $approved = AttendanceRequest::whereHas('attendance', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('is_approved', true)->with('attendance')->get();

        return view('attendance.requests', compact('pending', 'approved'));
    }

    public function show(AttendanceRequest $request)
    {
        $request->load(['attendance.breakTimes']);

        return view('attendance.show', [
            'attendance' => $request->attendance,
            'breakTimes' => $request->attendance->breakTimes,
            'showForm' => false, // 修正フォームなし
            'backRoute' => route('requests.index'),
            'request' => $request, // 承認済みチェックに使う
        ]);
    }
}
