<?php

namespace App\Providers;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $listen = [
        'App\Events\PresensiUpdated' => [
            'App\Listeners\PresensiListener',
        ],
    ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
        Paginator::defaultView('vendor.pagination.tailwind');
        View::composer('*', function ($view) {
            $user = Auth::user();
    
            $isOldPassword = false;
            if ($user) {
                $isOldPassword = Hash::check('passwordmahasiswa', $user->password);
            }
    
            $view->with('isOldPassword', $isOldPassword);
        });
    }
}
