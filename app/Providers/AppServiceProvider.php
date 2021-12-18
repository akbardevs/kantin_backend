<?php

namespace App\Providers;

use App\Models\Information;
use App\Models\Promotion;
use App\Models\Qa;
use App\Models\Transaction;
use App\Models\User;
use App\Observers\InformationObserver;
use App\Observers\PromotionObserver;
use App\Observers\QaObserver;
use App\Observers\TransactionObserver;
use Laravel\Nova\Nova;
use Illuminate\Support\Carbon;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        setlocale(LC_ALL, "id.utf8");
        Carbon::setLocale(config('app.locale'));
        if (env('APP_ENV') == 'production') {
            URL::forceScheme('https');
        }
        Nova::serving(function () {
            User::observe(UserObserver::class);
        });
    }
}
