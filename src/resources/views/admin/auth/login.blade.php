@extends('layouts.app')

@section('title', '管理者ログイン')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="p-4" style="width: 100%; max-width: 500px;">
        <h2 class="text-center mb-4">管理者ログイン</h2>

        @if ($errors->any())
            <div class="alert alert-danger text-center">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">パスワード</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="d-grid">
                <button class="btn btn-dark">ログイン</button>
            </div>
        </form>
    </div>
</div>
@endsection
