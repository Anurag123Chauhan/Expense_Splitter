<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\ExpenseShare;
use App\Models\Group;
use App\Policies\ExpensePolicy;
use App\Policies\ExpenseSharePolicy;
use App\Policies\GroupPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Group::class => GroupPolicy::class,
        Expense::class => ExpensePolicy::class,
        ExpenseShare::class => ExpenseSharePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
} 