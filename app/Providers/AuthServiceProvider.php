<?php

namespace App\Providers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Review;
use App\Policies\AdPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Setting the application-specific policies.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Ad::class => AdPolicy::class,
        Category::class => CategoryPolicy::class,
        Review::class => ReviewPolicy::class,
    ];


    /**
     * Registration of authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
