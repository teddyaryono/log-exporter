<?php

namespace Compro\LogExporter;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
	protected $signature = 'log:export';

    /**
     * The console command description.
     *
     * @var string
     */
	protected $description = 'Export all "storage/logs/*.log" file to S3, except "laravel.log" file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *co
     * @return mixed
     */
    public function handle()
    {

	    // User Notification: Export log in progress
	    $this->line("<fg=red>Export log in progress...</>");


		// Get all local log files, except laravel.log
	    $localLogDisk = Storage::disk('local_log');
	    $localLogFiles = collect($localLogDisk->allFiles());
	    $logFilesToBeExported = $localLogFiles->map(function($item) {
		    $excludeFile = 'laravel.log';
		    return $item == $excludeFile ? null : $item;
	    })
			->filter();


		// Upload all local log files to S3 bucket
	    $cloudDisk = Storage::disk('cloud_log');

	    $logFilesToBeExported->each(function ($item, $key) use ($cloudDisk) {
		    $cloudDisk->put($item, $item);
	    });

	    $cloudLogFiles = collect($cloudDisk->allFiles());

	    $this->line("\n<fg=red>List files in the bucket...</>");
	    $cloudLogFiles->dump();


		// User Notification: Export log done
	    $this->line("\n<fg=red>Export log done...</>");

    }
}
