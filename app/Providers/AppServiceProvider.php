<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Routing\UrlGenerator;
use App\Events\SubscriptionBillingAdvanced;
use App\Listeners\LogSubscriptionBillingAdvance;
use Laravel\Passport\Passport;

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
    public function boot(UrlGenerator $url): void
    {
        Event::listen(
            SubscriptionBillingAdvanced::class,
            LogSubscriptionBillingAdvance::class,
        );

        // Configure Passport
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        // Force HTTPS in production for assets (Render requirement)
        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }
    }
}
