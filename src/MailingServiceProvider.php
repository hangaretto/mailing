<?php

namespace Magnetar\Mailing;

use Illuminate\Support\ServiceProvider;

class MailingServiceProvider extends ServiceProvider
{
    //protected $commands = [
    //    'Magnetar\Tariffs\Commands\TariffExpired',
    //];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/mailing.php' => config_path('magnetar/mailing.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/migrations' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Magnetar\Mailing\Controllers\MailingController');
        //$this->commands($this->commands);
    }
}
