<?php

namespace Fomvasss\FieldFile;

use Fomvasss\FieldFile\Managers\BaseFileManager;
use Fomvasss\FieldFile\Managers\DocumentFileManager;
use Fomvasss\FieldFile\Managers\ImageFileManager;
use Illuminate\Support\ServiceProvider;

class FieldFileServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/field-file.php' => config_path('field-file.php'),
        ], 'field-file-config');

        if (config('field-file.routes.use', true)) {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        }

        if (! class_exists('CreateFieldFileTables')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_field-file_tables.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_create_field-file_tables.php",
            ], 'field-file-migrations');
        }

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'field-file-translations');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/field-file'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\RemoveOldFiles::class
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/field-file.php', 'field-file-config');

        $this->app->singleton(ImageFileManager::class);
        $this->app->singleton(DocumentFileManager::class);
        $this->app->singleton(BaseFileManager::class);
    }
}
