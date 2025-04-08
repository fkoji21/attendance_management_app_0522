@extends('layouts.app')

@section('title', '勤怠打刻')

@section('content')
    <div class="container">
        <h1>勤怠打刻画面</h1>

        {{-- 現在の日時 --}}
        <p>現在時刻：<span id="current-time"></span></p>

        {{-- ステータス表示（ダミー） --}}
        <p>現在のステータス：<strong>勤務外</strong></p>

        {{-- 勤怠ボタン群（仮表示） --}}
        <div class="btn-group" role="group">
            <button class="btn btn-success">出勤</button>
            <button class="btn btn-warning">休憩</button>
            <button class="btn btn-info">休憩戻</button>
            <button class="btn btn-danger">退勤</button>
        </div>

        {{-- メッセージ表示枠（退勤時など） --}}
        <div class="mt-3">
            {{-- <p class="alert alert-success">お疲れ様でした！</p> --}}
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
