<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\RedirectAuthenticatedUsers;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginViewResponse::class, function () {
            return new class implements LoginViewResponse
            {
                public function toResponse($request)
                {
                    return view('auth.login');
                }
            };
        });

        $this->app->singleton(RegisterViewResponse::class, function () {
            return new class implements RegisterViewResponse
            {
                public function toResponse($request)
                {
                    return view('auth.register');
                }
            };
        });

        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse
            {
                public function toResponse($request)
                {
                    return redirect('/attendance');
                }
            };
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // 既存の Fortify 設定のあとに追加
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            function () {
                return new class implements \Laravel\Fortify\Contracts\LoginResponse
            {
                    public function toResponse($request)
                {
                        return redirect()->intended(app(RedirectAuthenticatedUsers::class)());
                    }
                };
            }
        );

        $this->app->singleton(LogoutResponse::class, function () {
            return new class implements LogoutResponse
            {
                public function toResponse($request)
                {
                    return redirect(Auth::user()?->is_admin ? '/admin/login' : '/login');
                }
            };
        });

    }
}
