@extends('layouts.app')

@section('title', '申請詳細')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">申請詳細</h1>

    <table class="table table-bordered">
        <tr>
            <th>ユーザー名</th>
            <td>{{ $request->attendance->user->name }}</td>
        </tr>
        <tr>
            <th>日付</th>
            <td>{{ \Carbon\Carbon::parse($request->attendance->date)->format('Y年n月j日') }}</td>
        </tr>
        <tr>
            <th>申請時刻（出勤）</th>
            <td>{{ $request->requested_clock_in }}</td>
        </tr>
        <tr>
            <th>申請時刻（退勤）</th>
            <td>{{ $request->requested_clock_out }}</td>
        </tr>
        <tr>
            <th>申請理由</th>
            <td>{{ $request->requested_note }}</td>
        </tr>
        <tr>
            <th>ステータス</th>
            <td>
                @if ($request->is_approved)
                    <span class="badge bg-success">承認済み</span>
                @else
                    <span class="badge bg-warning text-dark">承認待ち</span>
                @endif
            </td>
        </tr>
    </table>

    @if (!$request->is_approved)
        <form method="POST" action="{{ route('requests.approve', $request->id) }}">
            @csrf
            <button type="submit" class="btn btn-primary">承認する</button>
        </form>
        <p class="text-danger mt-3">※承認後は元に戻せません。</p>
    @else
        <p class="text-muted">この申請はすでに承認済みです。</p>
    @endif
</div>
@endsection
