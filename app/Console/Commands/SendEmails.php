<?php

namespace Cupa\Console\Commands;

use Cupa\EmailList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // fetch all emails needing added
        $emails = EmailList::fetchAllNotEmailed();

        // loop through and add to array
        $users = [];
        foreach ($emails as $email) {
            $users[] = $email->email;
        }

        // send email
        Mail::send('emails.groups_invite', ['users' => $users], function ($m) {
            // send email to the webmaster
            $m->to('webmaster@cincyultimate.org', 'CUPA Webmaster')
              ->subject('[CUPA] Google group additions '.date('Y-m-d'));
        });

        // mark all as emailed
        foreach ($emails as $email) {
            $email->emailed = 1;
            $email->save();
        }
    }
}
