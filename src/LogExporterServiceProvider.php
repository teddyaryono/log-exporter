<?php

namespace Compro\LogExporter;

use Illuminate\Support\ServiceProvider;

class LogExporterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
        	$this->commands([
        		ExportLogs::class,
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
	    // User Notification: Export log in progress
        $this->app->make('Compro\LogExporter\ExportLogs');
    }
}
