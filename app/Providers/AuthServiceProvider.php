<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Services\PermissionService;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $permissionService = new PermissionService();

        Gate::define('view_clients_list', function($user, $partnerId) use($permissionService){
            return $permissionService->checkViewClientsListPermission($user, $partnerId);
        });

        Gate::define('view_partners_list', function($user, $companyId) use($permissionService){
            return $permissionService->checkViewPartnersListPermission($user, $companyId);
        });

        Gate::define('view_partner_companies_list', function($user) use($permissionService){
            return $permissionService->checkViewPartnerCompaniesListPermission($user);
        });

        Gate::define('view_events', function($user, $partnerId) use($permissionService){
            return $permissionService->checkViewEventsPermission($user, $partnerId);
        });
        
    }
}
