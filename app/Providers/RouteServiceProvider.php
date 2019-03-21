<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;

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

    public function boot()
    {
        if (!$this->app->runningInConsole() && !$this->app->environment('production')) {
            DB::listen(function ($query) {
                $args = $query->bindings;
                $pdo = DB::getPdo();
                dump(preg_replace_callback('/\?/', function () use (&$args, $pdo) {
                    return $pdo->quote(array_shift($args));
                }, $query->sql));
            });
        }

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
}
