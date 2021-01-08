<?php

namespace App\Providers;

use App\Menu;
use App\Alumno;
use App\Policies\AlumnoPolicy;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Alumno::class => AlumnoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addMinutes(1000));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(15));

        $menus = Menu::all(); 
        $scopes = [];

        foreach ($menus as $menu) {
            $name = strtolower($menu->name);
            $scope = [$name=>''];
            $scopes = array_merge($scopes, $scope);
        }
        //dd($scopes);
        Passport::tokensCan($scopes);
    }
}
