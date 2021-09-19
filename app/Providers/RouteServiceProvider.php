<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    public const HOME = '/admin/home';

    public function boot()
    {
        //
        parent::boot();
    }

    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();
        // $this->mapTrainingRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware(['web', 'localeSessionRedirect'])//, 'checkUser'
             ->namespace($this->namespace.'\Admin')
             ->group(base_path('routes/admin/admin.php'));
    }

    protected function mapTrainingRoutes()
    {
        $this->TrainingRoutes('training');
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /** Privates */
    private function TrainingRoutes($route){
        Route::middleware(['web', 'localeSessionRedirect'])
            ->namespace($this->namespace.'\Training')
            ->group(base_path('routes/training/'.$route.'.php'));
    }
}
