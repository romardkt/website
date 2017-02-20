<?php

namespace Cupa\Console\Commands;

use App;
use Mail;
use Cupa\Models\User;
use Cupa\Mail\ConvertMinor;
use Illuminate\Console\Command;

class NotifyParentsOfMinorAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cupa:notify-parents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify parents of minor accounts that are now 18 years of age.';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $persistFile = storage_path().'/app/minor-notifications.txt';

        // check for file and notify if file is not present.
        if (!file_exists($persistFile)) {
            if (!$this->confirm('Are you sure, there is no file to check notifications?')) {
                $this->warn('User aborted');
                exit(1);
            }
            file_put_contents($persistFile, json_encode(['notification-version' => '1.0']));
        }

        $currentData = json_decode(file_get_contents($persistFile), true);

        // check for file and notify if file is empty.
        $minors = User::whereNotNull('parent')->get();

        $parentsToNotify = [];

        // build the array of parents to notify
        foreach ($minors as $minor) {
            if ($minor->getAge() >= 18 && !isset($currentData[$minor->id])) {
                if (isset($parentsToNotify[$minor->parent])) {
                    $parentsToNotify[$minor->parent]['minors'][] = $minor;
                } else {
                    $parentsToNotify[$minor->parent] = [
                        'parent' => $minor->parentObj,
                        'minors' => [$minor],
                    ];
                }

                $currentData[$minor->id] = true;
            }
        }

        // send the emails
        foreach ($parentsToNotify as $data) {
            $this->info("Sending email to {$data['parent']->email}");
            if (App::environment() == 'prod') {
                Mail::to($data['parent']->email)
                    ->bcc('webmaster@cincyultimate.org')
                    ->send(new ConvertMinor($data));
            } else {
                Mail::to('webmaster@cincyultimate.org')
                    ->send(new ConvertMinor($data));
            }
        }

        if (count($parentsToNotify) < 1) {
            $this->info('There are no parents to notify at this time.');
        }

        file_put_contents($persistFile, json_encode($currentData));
    }
}
