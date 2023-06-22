<?php

namespace App\Http;

use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $commands = [
        Commands\VimeoProcessCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('vimeo:process')->everyMinute();
    }
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
     
        require base_path('routes/console.php');
    }

    protected $middleware = [
            // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
                // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'verify.api' => \App\Http\Middleware\VerifyEmail::class,
        'admin' => \App\Http\Middleware\IsAdmin::class,
        'findUser' => \App\Http\Middleware\FindUser::class,
        'findComment' => \App\Http\Middleware\FindComment::class,
        'findVideo' => \App\Http\Middleware\FindVideo::class,
        'findCategory' => \App\Http\Middleware\FindCategory::class,
        'checkVerified' => \App\Http\Middleware\CheckEmailVerified::class,
        'email.exists' => \App\Http\Middleware\CheckEmailExists::class,
        'email.verify' => \App\Http\Middleware\CheckEmailVerify::class,
        'checkAuth' => \App\Http\Middleware\CheckAuth::class,
        'checkUsername' => \App\Http\Middleware\CheckUsername::class,
        'checkReportedThisUser'=> \App\Http\Middleware\checkReportedThisUser::class,
        'userSuspended' => \App\Http\Middleware\CheckUserSuspended::class,
    ];
}
