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


        // Registering local path of logs
	    app()->config['filesystems.disks.local_log'] = [
		    'driver' => 'local',
		    'root' => storage_path('logs'),
	    ];


	    // Registering cloud path of logs
	    app()->config['filesystems.disks.cloud_log'] = [
		    'driver' => 's3',
		    'key' => env('AWS_LOG_ACCESS_KEY_ID'),
		    'secret' => env('AWS_LOG_SECRET_ACCESS_KEY'),
		    'region' => env('AWS_LOG_DEFAULT_REGION'),
		    'bucket' => env('AWS_LOG_BUCKET'),
	    ];

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
