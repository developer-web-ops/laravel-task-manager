<?php

namespace App\Providers;

use App\Models\Task;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Task::class => TaskPolicy::class,
    ];

    public function boot(): void
    {
        // Token expiry: 1 year for personal access tokens, 2 weeks for regular
        Passport::tokensExpireIn(now()->addYear());
        Passport::refreshTokensExpireIn(now()->addYears(2));
        Passport::personalAccessTokensExpireIn(now()->addYear());
    }
}
