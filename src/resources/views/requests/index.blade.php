@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">修正申請一覧</h2>

    {{-- 承認待ち --}}
    <h4 class="text-danger">承認待ち</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日</th>
                <th>申請理由</th>
                <th>申請日</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pendingRequests as $request)
                <tr>
                    <td><span class="badge bg-warning text-dark">承認待ち</span></td>
                    <td>{{ $request->attendance->user->name }}</td>
                    <td>{{ $request->attendance->date->format('Y/m/d') }}</td>
                    <td>{{ $request->requested_note }}</td>
                    <td>{{ $request->created_at->format('Y/m/d') }}</td>
                    <td><a href="{{ route('requests.show', $request) }}">詳細</a></td>
                </tr>
            @empty
                <tr><td colspan="6">承認待ちの申請はありません。</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- 承認済み --}}
    <h4 class="text-success mt-5">承認済み</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日</th>
                <th>申請理由</th>
                <th>申請日</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($approvedRequests as $request)
                <tr>
                    <td><span class="badge bg-success">承認済み</span></td>
                    <td>{{ $request->attendance->user->name }}</td>
                    <td>{{ $request->attendance->date->format('Y/m/d') }}</td>
                    <td>{{ $request->requested_note }}</td>
                    <td>{{ $request->created_at->format('Y/m/d') }}</td>
                    <td><a href="{{ route('requests.show', $request) }}">詳細</a></td>
                </tr>
            @empty
                <tr><td colspan="6">承認済みの申請はまだありません。</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
