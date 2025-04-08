@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4">会員登録</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- お名前 --}}
        <div class="mb-3">
            <label for="name" class="form-label">お名前</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                   name="name" value="{{ old('name') }}" required autofocus>

            @error('name')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        {{-- メールアドレス --}}
        <div class="mb-3">
            <label for="email" class="form-label">メールアドレス</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required>

            @error('email')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        {{-- パスワード --}}
        <div class="mb-3">
            <label for="password" class="form-label">パスワード</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" required>

            @error('password')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        {{-- 登録ボタン --}}
        <div class="text-center">
            <button type="submit" class="btn btn-primary">登録</button>
        </div>
    </form>
</div>
@endsection
