<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Fortify;

class AuthenticatedSessionController extends Controller
{
    public function create(): LoginViewResponse
    {
        return App::make(LoginViewResponse::class);
    }

    public function store(LoginRequest $request): LoginResponse
    {
        app(EnsureLoginIsNotThrottled::class)($request);

        $user = app(AttemptToAuthenticate::class)($request);

        if (!$user) {
            throw ValidationException::withMessages([
                Fortify::username() => __('auth.failed'), // langファイルで制御
            ]);
        }

        return app(PrepareAuthenticatedSession::class)($request, $user);
    }

    public function destroy(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return app(\Laravel\Fortify\Contracts\LogoutResponse::class)->toResponse($request);
    }
}
