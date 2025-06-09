<?php

namespace App\Providers;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\ProgramStudi;

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
            $isProfileCompleted = true;
            // $isDosen = $user && $user->role === 'dosen';
            // $isAdmin = $user && $user->role === 'admin';
            $isAdmin = request()->routeIs('admin.*');
            $isDosen = request()->routeIs('dosen.*');

            if ($user) {
                $isOldPassword = Hash::check('passwordmahasiswa', $user->password);
                $isProfileCompleted = $user->is_profile_complete == 1;
            }

            $view->with('isDosen', $isDosen);
            $view->with('isAdmin', $isAdmin);
            $view->with('isOldPassword', $isOldPassword);
            $view->with('isProfileCompleted', $isProfileCompleted);
            $view->with('programStudi', ProgramStudi::all());
        });
    }
}
