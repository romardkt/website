<?php

namespace Cupa\Console\Commands;

use DB;
use Log;
use Mail;
use Cupa\User;
use Cupa\Mail\InactiveReport;
use Illuminate\Console\Command;

class RemoveInactive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cupa:remove-inactives';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all accounts that are 1 year or older and inactive';

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
        $this->removeNonActivatedAccounts();

        $this->removeOldAccounts();

        $this->emailReport();
    }

    private function removeNonActivatedAccounts()
    {
        // get the date for one year ago
        $oneYear = date('Y-m-d', strtotime('-1 year'));

        // find all of the inactive old accounts
        $users = User::where('is_active', '=', false)
          ->whereNull('activated_at')
          ->where('created_at', '<=', $oneYear)
          ->get();

        // define the log path
        $logLocation = storage_path().'/logs/'.date('Y-m-d-').'inactives.log';
        foreach ($users as $user) {
            // log the removal
            file_put_contents($logLocation, $user->fullname()." removed for not being active.\n", FILE_APPEND | LOCK_EX);
            $user->delete();
        }
    }

    private function removeOldAccounts()
    {
        // get the date for twelve years ago
        $twelveYear = date('Y', strtotime('-12 year'));

        // find all of the users with most recent waiver
        $users = DB::table('users')
          ->select('users.id', 'users.first_name', 'users.last_name', DB::raw('MAX(user_waivers.year) AS year'))
          ->join('user_waivers', 'user_waivers.user_id', '=', 'users.id')
          ->whereNull('parent')
          ->groupBy('user_id')
          ->get();

        // define the log path
        $logLocation = storage_path().'/logs/'.date('Y-m-d-').'inactives.log';
        foreach ($users as $user) {
            $userObject = User::find($user->id);
            $hasChildWaiver = false;

            if ($userObject->children->count()) {
                // check all chilren
                foreach ($userObject->children as $child) {
                    $latestWaiver = $child->fetchLatestWaiver();
                    if ($latestWaiver && $latestWaiver->year > $twelveYear) {
                        $hasChildWaiver = true;
                        break;
                    }
                }
            }

            if ($user->year < $twelveYear && !$hasChildWaiver) {
                // log the removal
                file_put_contents($logLocation, $user->first_name.' '.$user->last_name." removed for not being active for 12 years.\n", FILE_APPEND | LOCK_EX);
                $userObject = User::find($user->id);
                $userObject->delete();
            }
        }
    }

    private function emailReport()
    {
        $logLocation = storage_path().'/logs/'.date('Y-m-d-').'inactives.log';
        Mail::to('webmaster@cincyultimate.org')
            ->send(new InactiveReport($logLocation));
    }
}
