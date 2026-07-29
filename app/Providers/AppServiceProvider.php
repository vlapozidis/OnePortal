<?php

namespace App\Providers;

use App\Models\LeaveRequest;
use App\Policies\LeaveRequestPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(LeaveRequest::class, LeaveRequestPolicy::class);

        // Register Microsoft Entra ID provider with Socialite
        Socialite::extend('microsoft', function ($app) {
            $config = $app['config']['services.microsoft'];
            return Socialite::buildProvider(
                MicrosoftAzureProvider::class,
                $config
            );
        });

        // Register Brevo's HTTP API as a mail transport (SMTP is blocked on Railway's Trial plan)
        Mail::extend('brevo', function (array $config) {
            return (new BrevoTransportFactory)->create(
                new Dsn('brevo+api', 'default', $config['key'] ?? null)
            );
        });
    }
}
