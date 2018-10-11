<?php
/**
 * Copyright (c) 2016  Andrey Yaresko.
 */

/**
 * Created by PhpStorm.
 * User: igorianru
 * Date: 29.09.16
 * Time: 7:09
 *
 * @author Andrey Yaresko <igorianru@gmail.com>
 */

namespace Igorianru\language;

use Illuminate\Support\ServiceProvider as BaseService;

class ServiceProvider extends BaseService
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'language');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/resources/views' => resource_path('views/vendor/language'),
            ], 'language');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('language', function ($app) {
            return new Language();
        });
        $this->app->alias('language', Language::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['language'];
    }
}