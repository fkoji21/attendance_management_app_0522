@extends('layouts.app')

@section('title', '申請詳細')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">申請詳細</h1>

    <table class="table">
        <tr><th>日付</th><td>{{ $request->attendance->date->format('Y年n月j日') }}</td></tr>
        <tr><th>申請時刻（出勤）</th><td>{{ $request->requested_clock_in }}</td></tr>
        <tr><th>申請時刻（退勤）</th><td>{{ $request->requested_clock_out }}</td></tr>
        <tr><th>申請理由</th><td>{{ $request->requested_note }}</td></tr>
        <tr><th>ステータス</th>
            <td>
                @if ($request->is_approved === null)
                    承認待ち
                @elseif ($request->is_approved)
                    承認済み
                @else
                    否認
                @endif
            </td>
        </tr>
    </table>
    @if (!$request->is_approved)
        <form method="POST" action="{{ route('requests.approve', $request) }}">
            @csrf
            <button type="submit" class="btn btn-primary">承認</button>
        </form>
    @else
        <p class="text-muted">承認済み</p>
    @endif
    @if ($request->is_approved === null)
        <p class="text-danger">※承認待ちのため修正はできません。</p>
    @endif
</div>
@endsection
