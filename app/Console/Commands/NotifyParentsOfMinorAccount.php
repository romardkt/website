<?php

namespace Cupa\Console\Commands;

use Mail;
use Cupa\User;
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
        $minors = User::whereNotNull('parent')->get();

        $parentsToNotify = [];

        // build the array of parents to notify
        foreach ($minors as $minor) {
            if ($minor->getAge() >= 18) {
                if (isset($parentsToNotify[$minor->parent])) {
                    $parentsToNotify[$minor->parent]['minors'][] = $minor;
                } else {
                    $parentsToNotify[$minor->parent] = [
                        'parent' => $minor->parentObj,
                        'minors' => [$minor],
                    ];
                }
            }
        }

        // send the emails
        foreach ($parentsToNotify as $data) {
            $this->info("Sending email to {$data['parent']->email}");
            Mail::to('webmaster@cincyultimate.org')
                ->send(new ConvertMinor($data));
        }
    }
}
