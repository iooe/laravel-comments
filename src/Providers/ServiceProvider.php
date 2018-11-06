<?php

namespace tizis\laraComments\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{

    public function boot()
    {
        /**
         * Publishers
         */
        $this->publishes([
            __DIR__ . '/../../config/comments.php' => config_path('comments.php'),
        ], 'config');


        $this->publishes([
            __DIR__ . '/../../resources/views/' . config('comments.ui') => resource_path('views/vendor/comments'),
        ], 'views');

        /**
        /**
         * Load some stuff
         */
        $this->loadMigrationsFrom(__DIR__ . '/../../migrations');

        $this->loadRoutesFrom(__DIR__ . '/../routes.php');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views/' . config('comments.ui'), 'comments');

        /**
         * Blade components
         */
        Blade::component('comments::components.comments', 'comments');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/comments.php',
            'comments'
        );
    }
}