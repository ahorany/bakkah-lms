<?php

namespace App\Providers;

use App\Http\View\Composers\ConsultingComposer;
use App\Http\View\Composers\EducationComposer;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\AsideComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.sidebar', AsideComposer::class);
    }
}
