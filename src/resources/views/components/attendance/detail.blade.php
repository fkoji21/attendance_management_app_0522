{{-- resources/views/components/attendance/detail.blade.php --}}
<div class="container py-5">
    <h2 class="mb-4 fw-bold">勤怠詳細</h2>

    <div class="mb-4">
        <p><strong>名前：</strong> {{ $attendance->user->name ?? '不明' }}</p>
        <p><strong>日付：</strong> {{ \Carbon\Carbon::parse($attendance->date)->format('Y年m月d日 (D)') }}</p>

        @if($showForm ?? false)
        <form action="{{ $editRoute }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>出勤時刻</label>
                <input type="time" name="clock_in" class="form-control" value="{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}">
            </div>
            <div class="mb-3">
                <label>退勤時刻</label>
                <input type="time" name="clock_out" class="form-control" value="{{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}">
            </div>
            <div class="mb-3">
                <label>備考</label>
                <textarea name="note" class="form-control">{{ $attendance->note }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"> {{ $submitLabel ?? '修正申請する' }}</button>
        </form>
        @endif
    </div>

    <h5>休憩記録</h5>
    @if ($breakTimes->isEmpty())
        <p>休憩なし</p>
    @else
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>開始</th>
                    <th>終了</th>
                    <th>休憩時間</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($breakTimes as $i => $break)
                    @php
                        $start = \Carbon\Carbon::parse($break->break_start);
                        $end = $break->break_end ? \Carbon\Carbon::parse($break->break_end) : null;
                        $duration = $end ? $end->diffInMinutes($start) : null;
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $start->format('H:i') }}</td>
                        <td>{{ $end ? $end->format('H:i') : '休憩中' }}</td>
                        <td>{{ $duration ? floor($duration / 60) . ':' . str_pad($duration % 60, 2, '0', STR_PAD_LEFT) : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="mt-4">
        <a href="{{ $backRoute }}" class="btn btn-outline-secondary">← 一覧に戻る</a>
    </div>
</div>
