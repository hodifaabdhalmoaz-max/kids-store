<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->use([
            \App\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        // Web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\XssSanitizer::class,
            \App\Http\Middleware\LoginAttemptProtection::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SetSeoMetadata::class,
        ]);

        // API middleware group
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Rate limiting for different routes
        $middleware->throttleApi('60,1'); // 60 requests per minute for API
        $middleware->throttleWithRedis();

        // Custom middleware aliases
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'auth.admin' => \App\Http\Middleware\AuthAdmin::class,
            'xss.sanitizer' => \App\Http\Middleware\XssSanitizer::class,
            'cache.response' => \App\Http\Middleware\CacheResponseMiddleware::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'rate.limit.strict' => \App\Http\Middleware\StrictRateLimit::class,
            'login.protection' => \App\Http\Middleware\LoginAttemptProtection::class,
            '2fa' => \App\Http\Middleware\TwoFactorAuthentication::class,
        ]);
    })
    ->withProviders([
        // Security Provider
        \App\Providers\SecurityServiceProvider::class,
    ])
    ->withCommands([
        \App\Console\Commands\SecurityAudit::class,
        \App\Console\Commands\SecureBackup::class,
        \App\Console\Commands\OptimizePerformance::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
