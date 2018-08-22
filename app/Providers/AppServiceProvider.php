<?php

namespace App\Providers;

use App\Field;
use App\Observers\FieldObserver;
use App\Observers\PersonObserver;
use App\Person;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Field::observe(FieldObserver::class);
        Person::observe(PersonObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
