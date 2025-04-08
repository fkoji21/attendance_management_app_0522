@extends('layouts.app')

@section('title', '勤怠打刻')

@section('content')
    <div class="container">
        <h1>勤怠打刻画面</h1>
        @php
            $latestBreak = $attendance->breakTimes->last();
        @endphp

        @if ($latestBreak && is_null($latestBreak->break_end))
            <p class="text-danger fw-bold">休憩中です</p>
        @endif

        {{-- 現在の日時 --}}
        <p>現在時刻：<span id="current-time"></span></p>

        {{-- ステータス表示（ダミー） --}}
        <p>現在のステータス：<strong>勤務外</strong></p>

        @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        {{-- 勤怠ボタン群（仮表示） --}}
        <div class="btn-group" role="group">

        {{-- 出勤 --}}
        <form method="POST" action="{{ route('attendance.start') }}" style="display:inline;">
            @csrf
            <button class="btn btn-success">出勤</button>
        </form>

        {{-- 休憩開始 --}}
        <form method="POST" action="{{ route('attendance.break.start') }}" style="display:inline;">
            @csrf
            <button class="btn btn-warning">休憩開始</button>
        </form>

        {{-- 休憩終了 --}}
        <form method="POST" action="{{ route('attendance.break.end') }}" style="display:inline;">
            @csrf
            <button class="btn btn-info">休憩終了</button>
        </form>

        {{-- 退勤（出勤中のみ） --}}
        @if ($status === '出勤中')
        <form method="POST" action="{{ route('attendance.clockOut') }}" style="display:inline;">
            @csrf
            <button class="btn btn-danger">退勤</button>
        </form>
        @endif
    </div>

    </div>

    <script>
        // 現在時刻の更新（JS）
        function updateCurrentTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleString();
        }
        setInterval(updateCurrentTime, 1000);
        updateCurrentTime();
    </script>
@endsection
