@extends('layouts.app')

@section('title', 'ログイン')

@section('content')

    <h1>ログインページ</h1>
    <form method="POST" action="/login">
        @csrf
        <input type="email" name="email" placeholder="メールアドレス"><br>
        <input type="password" name="password" placeholder="パスワード"><br>
        <button type="submit">ログイン</button>
    </form>

@endsection
