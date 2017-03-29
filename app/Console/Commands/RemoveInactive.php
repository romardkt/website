<?php

namespace Cupa\Console\Commands;

use DB;
use Log;
use Mail;
use Cupa\Models\User;
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
        // remove the log file to start from scratch
        $logLocation = storage_path().'/logs/'.date('Y-m-d-').'inactives.log';
        if (file_exists($logLocation)) {
            unlink($logLocation);
        }

        $this->removeNonActivatedAccounts();

        $this->removeOldAccounts();

        $this->emailReport();
    }

    private function removeNonActivatedAccounts()
    {
        // get the date for one year ago
        $oneMonth = date('Y-m-d', strtotime('-1 month'));

        // find all of the inactive old accounts
        $users = User::where('is_active', '=', false)
          ->whereNull('parent')
          ->whereNull('activated_at')
          ->where('created_at', '<=', $oneMonth)
          ->get();

        // define the log path
        $logLocation = storage_path().'/logs/'.date('Y-m-d-').'inactives.log';
        foreach ($users as $user) {
            // log the removal
            file_put_contents($logLocation, $user->fullname()." removed for not being activated.\n", FILE_APPEND | LOCK_EX);
            $user->delete();
        }
    }

    private function removeOldAccounts()
    {
        // get the date for two years ago
        $twoYear = date('Y', strtotime('-2 year'));

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
                    if ($latestWaiver && $latestWaiver->year > $twoYear) {
                        $hasChildWaiver = true;
                        break;
                    }
                }
            }

            if ($user->year < $twoYear && !$hasChildWaiver) {
                // log the removal
                file_put_contents($logLocation, $user->first_name.' '.$user->last_name." disabled for not being active for 2 years.\n", FILE_APPEND | LOCK_EX);
                $userObject = User::find($user->id);
                $userObject->is_active = 0;
                $userObject->reason = 'has been disabled due to inactivity.';
                $userObject->save();
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
