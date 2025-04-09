@extends('layouts.app')

@section('title', '修正申請一覧')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">修正申請一覧</h2>

    <h4 class="text-danger">承認待ち</h4>
    @if ($pending->isEmpty())
        <p>現在、承認待ちの申請はありません。</p>
    @else
        <ul class="list-group mb-4">
            @foreach ($pending as $request)
                <li class="list-group-item">
                    日付：{{ $request->attendance->date }} ／ 出勤：{{ $request->requested_clock_in }} ／ 退勤：{{ $request->requested_clock_out }}
                    <br>
                    <strong class="text-warning">承認待ちのため修正はできません。</strong>
                </li>
            @endforeach
        </ul>
    @endif

    <h4 class="text-success">承認済み</h4>
    @if ($approved->isEmpty())
        <p>承認済みの申請はまだありません。</p>
    @else
        <ul class="list-group">
            @foreach ($approved as $request)
                <li class="list-group-item">
                    日付：{{ $request->attendance->date }} ／ 出勤：{{ $request->requested_clock_in }} ／ 退勤：{{ $request->requested_clock_out }}
                    <br>
                    備考：{{ $request->requested_note }}
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
