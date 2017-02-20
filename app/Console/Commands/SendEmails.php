<?php

namespace Cupa\Console\Commands;

use Mail;
use Cupa\Models\EmailList;
use Illuminate\Console\Command;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cupa:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email for google group';

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
            $users[$email->email] = $email->email;
        }

        // chunk users by 10
        $users = array_chunk($users, 10);

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
