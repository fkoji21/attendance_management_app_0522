<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)->get();

        return view('admin.users.index', compact('users'));
    }

    public function showMonthlyAttendance(Request $request, $userId)
    {
        $user = \App\Models\User::findOrFail($userId);

        $month = $request->input('month', \Carbon\Carbon::now()->format('Y-m'));
        $startOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $attendances = \App\Models\Attendance::with('breakTimes')
            ->where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date')
            ->get();

        return view('admin.users.monthly', [
            'user' => $user,
            'attendances' => $attendances,
            'currentMonth' => $startOfMonth,
            'prevMonth' => $startOfMonth->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $startOfMonth->copy()->addMonth()->format('Y-m'),
        ]);
    }

    public function exportMonthlyCsv(Request $request, $userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $month = $request->input('month', now()->format('Y-m'));
        $startOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $attendances = \App\Models\Attendance::with('breakTimes')
            ->where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date')
            ->get();

        $csvData = [];
        $csvData[] = ['日付', '出勤', '退勤', '休憩', '合計'];

        foreach ($attendances as $a) {
            $breakMinutes = $a->breakTimes->sum(function ($b) {
                return \Carbon\Carbon::parse($b->break_end)->diffInMinutes($b->break_start);
            });
            $workMinutes = \Carbon\Carbon::parse($a->clock_in)->diffInMinutes(\Carbon\Carbon::parse($a->clock_out)) - $breakMinutes;

            $csvData[] = [
                \Carbon\Carbon::parse($a->date)->format('Y/m/d'),
                optional($a->clock_in)->format('H:i'),
                optional($a->clock_out)->format('H:i'),
                gmdate('H:i', $breakMinutes * 60),
                gmdate('H:i', $workMinutes * 60),
            ];
        }

        $filename = "勤怠一覧_{$user->name}_{$month}.csv";

        $response = Response::streamDownload(function () use ($csvData) {
            $stream = fopen('php://output', 'w');

            //Excel文字化け防止のBOM（UTF-8 with BOM）
            fwrite($stream, "\xEF\xBB\xBF");

            foreach ($csvData as $row) {
                fputcsv($stream, $row);
            }
            fclose($stream);
        }, $filename, ['Content-Type' => 'text/csv']);

        return $response;
    }
}
