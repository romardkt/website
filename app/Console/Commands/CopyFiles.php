<?php

namespace Cupa\Console\Commands;

use Illuminate\Console\Command;

class CopyFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cupa:copy-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copies files from production.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // create directories if they don't exits
        if (!file_exists(public_path().'/upload/')) {
            mkdir(public_path().'/upload/');
        }

        if (!file_exists(public_path().'/data/')) {
            mkdir(public_path().'/data/');
        }

        // rsync uploaded files to data dir
        $this->info('Copying uploaded files from production');
        exec('rsync -rav kcin@cincyultimate.org:/www/cupa/public/upload/* '.public_path().'/upload/.');
        $this->info('Copying all data files from production');
        exec('rsync -rav kcin@cincyultimate.org:/www/cupa/public/data/* '.public_path().'/data/.');
    }
}
