{{-- resources/views/attendance/show.blade.php --}}
@extends('layouts.app')

@section('title', '勤怠詳細')

@section('content')
    @include('components.attendance.detail', [
        'attendance' => $attendance,
        'breakTimes' => $breakTimes,
        'showForm' => true,
        'editRoute' => route('attendance.request.edit', $attendance->id),
        'backRoute' => route('attendance.monthly')
    ])
@endsection
