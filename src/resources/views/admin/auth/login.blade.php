@extends('layouts.app')

@section('content')
<div class="container">
    <h2>管理者ログイン</h2>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>
        <div class="form-group mt-2">
            <label>パスワード</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary mt-3">ログイン</button>
    </form>
</div>
@endsection
