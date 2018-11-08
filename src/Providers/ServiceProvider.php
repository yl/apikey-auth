<?php

/*
 * This file is part of apikey-auth.
 *
 * (c) Leonis <yangliulnn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leonis\ApiKeyAuth\Providers;

use Leonis\ApiKeyAuth\ApiKeyGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__ . '/../../config/config.php');
        $this->publishes([$path => base_path('config/api_key.php')], 'config');
        $this->mergeConfigFrom($path, 'api_key');

        $path = realpath(__DIR__ . '/../../database/migrations');
        $this->publishes([$path => base_path('database/migrations')]);
        $this->loadMigrationsFrom($path);

        $this->extendAuthGuard();
    }

    /**
     * Extend Laravel's Auth.
     *
     * @return void
     */
    protected function extendAuthGuard()
    {
        Auth::extend('api_key', function ($app, $name, array $config) {
            return new ApiKeyGuard(
                Auth::createUserProvider($config['provider']),
                $app['request']
            );
        });
    }
}
