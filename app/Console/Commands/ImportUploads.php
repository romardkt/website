<?php

namespace Cupa\Console\Commands;

use Cupa\File;
use Illuminate\Console\Command;

class ImportUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cupa:import-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all files from uploads into system';

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
        $path = public_path().'/upload';
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        foreach (scandir($path) as $file) {
            if ($file[0] == '.') {
                continue;
            }

            $filePath = $path.'/'.$file;
            $md5 = md5_file($filePath);
            if (!File::isUnique($md5)) {
                $this->info('Already imported '.$file);
                continue;
            }

            File::create([
                'name' => $file,
                'location' => '/upload/'.$file,
                'md5' => $md5,
                'size' => filesize($filePath),
                'mime' => finfo_file($finfo, $filePath),
            ]);
            $this->info('Imported file '.$file);
        }
    }
}
