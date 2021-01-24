<?php

namespace KarlValentin\LaravelI18nCheck;

use Illuminate\Support\ServiceProvider;
use KarlValentin\LaravelI18nCheck\Console\Commands\CheckLanguageResources;

/**
 * I18n check service provider.
 *
 * @author Karl Valentin <karl.valentin@kvis.de>
 */
class I18nCheckServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'i18ncheck');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckLanguageResources::class,
            ]);

            $this->publishes(
                [
                    __DIR__.'/../config/config.php' => config_path('i18ncheck.php'),
                ],
                'config'
            );
        }
    }
}
