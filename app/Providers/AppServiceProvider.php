<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

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
        FormRequest::macro('validatedExcept', function ($except = []) {
            return Arr::except($this->validated(), $except);
        });

        $this->app->bind('getUserFromDBUsingAuth0', function () {
            if (!auth()->check()) {
                return null;
            }

            return User::firstOrCreate(
                [
                    'auth0_id' => auth()->id()
                ],
                [
                    'email' => auth()->user()->email ?? '',
                    'name' => auth()->user()->name ?? '',
                    'balance' => 0
                ]
            );
        });
    }
}
