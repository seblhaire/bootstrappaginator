<?php

namespace Seblhaire\BootstrapPaginator;

use Illuminate\Support\ServiceProvider;

class BootstrapPaginatorServiceProvider extends ServiceProvider
{
     protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
      $this->publishes([
          __DIR__.'/../config/' => config_path('vendor/seb/bootstrappaginator')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */

    public function register()
    {
      $this->mergeConfigFrom(
          __DIR__.'/../config/bootstrappaginator.php', 'bootstrappaginator'
      );
      $this->app->singleton('BootstrapPaginatorService', function ($app) {
        return new BootstrapPaginatorService();
      });
    }

    public function provides() {
        return [BootstrapPaginatorContract::class];
    }
}
