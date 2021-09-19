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
        //front.education
        view()->composer(FRONT.'.education.layouts.master', EducationComposer::class);

        //front.consulting
        view()->composer(FRONT.'.consulting.layouts.master', ConsultingComposer::class);

        //admin
        view()->composer(ADMIN.'.layouts.aside', AsideComposer::class);
    }
}
