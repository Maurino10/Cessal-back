<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Cessions\Cession;
use App\Models\Cessions\CessionMagistrat;
use App\Policies\Cessions\CessionMagistratPolicy;
use App\Policies\Cessions\CessionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Cession::class => CessionPolicy::class,
        CessionMagistrat::class => CessionMagistratPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
