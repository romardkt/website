<?php

namespace Cupa\Console\Commands;

use DB;
use Log;
use Mail;
use Cupa\Models\User;
use Cupa\Mail\MinorsReport;
use Illuminate\Console\Command;

class CheckMinorAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cupa:check-minor-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks all accounts and disables < 18 year olds and re-activates those who are now 18 or older.';

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
        // remove the log file if it exists
        $logLocation = storage_path().'/logs/'.date('Y-m-d-').'minors.log';
        if (file_exists($logLocation)) {
            unlink($logLocation);
        }

        $this->disableYoungAccounts();

        $this->reenableOldAccounts();

        $this->clearMinorAccounts();

        $this->emailReport();
    }

    private function disableYoungAccounts()
    {
        // get the date for 18 year olds
        $oldEnough = date('Y-m-d', strtotime('-18 year'));

        // find all of the inactive old accounts
        $users = User::where('is_active', '=', false)
          ->whereNull('parent')
          ->where('birthday', '>=', $oldEnough)
          ->where('is_active', '=', 1)
          ->get();


        // define the log path
        $logLocation = storage_path().'/logs/'.date('Y-m-d-').'minors.log';
        foreach ($users as $user) {
            // log the removal
            file_put_contents($logLocation, $user->fullname()." disabled for being less than 18 years old.\n", FILE_APPEND | LOCK_EX);
            $user->is_active = 0;
            $user->reason = 'age is not the minimum required 18 years of age for a CUPA account.';
            $user->save();
        }
    }

    private function reenableOldAccounts()
    {
        // get the date for 18 year olds
        $oldEnough = date('Y-m-d', strtotime('-18 year'));

        // find all of the users with most recent waiver
        $users = User::where('is_active', '=', false)
          ->whereNull('parent')
          ->where('birthday', '<=', $oldEnough)
          ->where('is_active', '=', false)
          ->where('reason', '=', 'age is not the minimum required 18 years of age for a CUPA account.')
          ->get();

        // define the log path
        $logLocation = storage_path().'/logs/'.date('Y-m-d-').'minors.log';
        foreach ($users as $user) {
            // log the re-enable
            file_put_contents($logLocation, $user->fullname()." re-enabled for now being 18 years or older.\n", FILE_APPEND | LOCK_EX);
            $user->is_active = 1;
            $user->reason = null;
            $user->save();
        }
    }

    public function clearMinorAccounts()
    {
        $users = User::where('is_active', '=', false)
          ->whereNotNull('parent')
          ->where('is_active', '=', false)
          ->where('reason', '=', 'age is not the minimum required 18 years of age for a CUPA account.')
          ->get();

        foreach ($users as $user) {
            $user->reason = null;
            $user->save();
        }
    }

    private function emailReport()
    {
        $logLocation = storage_path().'/logs/'.date('Y-m-d-').'minors.log';
        Mail::to('webmaster@cincyultimate.org')
            ->send(new MinorsReport($logLocation));
    }
}
