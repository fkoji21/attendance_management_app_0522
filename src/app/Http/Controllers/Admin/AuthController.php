<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            // 管理者かどうかを確認
            if (!Auth::user()->is_admin) {
                Auth::logout();
                return back()->withErrors(['email' => '管理者権限がありません']);
            }

            return redirect()->intended('/requests');
        }

        return back()->withErrors(['email' => '認証に失敗しました']);
    }
}
