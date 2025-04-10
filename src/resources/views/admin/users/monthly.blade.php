@extends('layouts.app')

@section('title', $user->name . 'さんの勤怠')

@section('content')
<div class="container">
    <h2>{{ $user->name }} さんの勤怠</h2>

    <div class="d-flex justify-content-between align-items-center my-3">
        <a href="{{ route('admin.users.monthly', ['user' => $user->id, 'month' => $prevMonth]) }}" class="btn btn-outline-secondary">← 前月</a>
        <span>{{ $currentMonth->format('Y年m月') }}</span>
        <a href="{{ route('admin.users.monthly', ['user' => $user->id, 'month' => $nextMonth]) }}" class="btn btn-outline-secondary">翌月 →</a>
    </div>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('m/d(D)') }}</td>
                    <td>{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                    <td>
                        {{ number_format($attendance->breakTimes->sum(function ($break) {
                            return \Carbon\Carbon::parse($break->break_end)->diffInMinutes($break->break_start);
                        }) / 60, 1) }}時間
                    </td>
                    <td>
                        {{ number_format(
                            \Carbon\Carbon::parse($attendance->clock_in)->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_out)) / 60
                            - $attendance->breakTimes->sum(function ($break) {
                            return \Carbon\Carbon::parse($break->break_end)->diffInMinutes($break->break_start);
                            }) / 60
                        , 1) }}時間
                    </td>
                    <td>
                        @php
                            $route = Auth::user()->is_admin
                                ? route('admin.attendance.show', $attendance->id)
                                : route('attendance.show', $attendance->id);
                        @endphp
                        <a class="btn btn-outline-primary btn-sm" href="{{ $route }}">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form method="GET" action="{{ route('admin.users.monthly.export', ['user' => $user->id, 'month' => $currentMonth->format('Y-m')]) }}">
        <button type="submit" class="btn btn-dark mt-4">CSV出力</button>
    </form>
</div>
@endsection
