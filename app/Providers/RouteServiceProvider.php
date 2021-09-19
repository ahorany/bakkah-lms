<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/admin/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
        $this->mapEducationRoutes();
        // $this->mapEPayRoutes();
        // $this->mapStaticRoutes();
        // $this->mapUserRoutes();
        // $this->mapUserFromExcelRoutes();

        // $this->mapAdminRoutes();
        // $this->mapServiceRoutes();

        // $this->mapTrainingRoutes();
        // $this->mapEvaluationRoutes();
        // $this->mapCertificatesRoutes();
        // $this->mapXeroRoutes();

        // $this->mapTestingRoutes();
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    protected function mapEducationRoutes()
    {
        Route::middleware('web')
            //  ->namespace($this->namespace.'\Front\Education')
             ->group(base_path('routes/education/education.php'));
    }

    protected function mapEPayRoutes()
    {
        Route::middleware('web')
             ->group(base_path('routes/education/epay.php'));
    }

    protected function mapStaticRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace.'\Front\Education')
             ->group(base_path('routes/education/static.php'));
    }

    protected function mapUserRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace.'\Front\Education')
             ->group(base_path('routes/education/user.php'));
    }

    protected function mapUserFromExcelRoutes()
    {
        Route::middleware(['web'])
             ->namespace($this->namespace.'\Front\Education')
             ->group(base_path('routes/education/userfromexcel.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware(['web', 'localeSessionRedirect', 'checkUser'])
             ->namespace($this->namespace.'\Admin')
             ->group(base_path('routes/admin/admin.php'));
    }

    protected function mapServiceRoutes()
    {
        Route::middleware(['web', 'localeSessionRedirect'])
             ->group(base_path('routes/admin/service.php'));
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

    protected function mapXeroRoutes()
    {
        Route::namespace($this->namespace.'\Xero')
            ->group(base_path('routes/api/xero.php'));
    }

    protected function mapTestingRoutes()
    {
        Route::middleware('web')
             ->group(base_path('routes/testing/testing.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
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
