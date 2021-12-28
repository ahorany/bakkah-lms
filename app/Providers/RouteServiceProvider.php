<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

     const HOME = '/user/home';

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapEducationRoutes();
        $this->mapAdminRoutes();
        $this->mapTrainingRoutes();
        $this->mapFrontRoutes();

    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    protected function mapFrontRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/front.php'));
    }

    protected function mapEducationRoutes()
    {
        Route::middleware('web')
            //  ->namespace($this->namespace.'\Front\Education')
             ->group(base_path('routes/education/education.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware(['web', 'localeSessionRedirect']) ///////
             ->namespace($this->namespace.'\Admin')
             ->group(base_path('routes/admin/admin.php'));
    }

    protected function mapTrainingRoutes()
    {
        $this->TrainingRoutes('training');
    }

    protected function mapEvaluationRoutes()
    {
        $this->TrainingRoutes('evaluation');
    }

    protected function mapCertificatesRoutes()
    {
        $this->TrainingRoutes('certificate');
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
