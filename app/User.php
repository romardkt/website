<?php

namespace Cupa;

use Carbon\Carbon;
use Datetime;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'birthday',
        'gender',
        'password',
        'activation_code',
        'reason',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function parentObj()
    {
        return $this->belongsTo('Cupa\User', 'parent');
    }

    public function roles()
    {
        return $this->hasMany('Cupa\UserRole');
    }

    public function profile()
    {
        return $this->hasOne('Cupa\UserProfile');
    }

    public function contacts()
    {
        return $this->hasMany('Cupa\UserContact');
    }

    public function volunteer()
    {
        return $this->hasOne('Cupa\Volunteer');
    }

    public function children()
    {
        return $this->hasMany('Cupa\User', 'parent');
    }

    public function fullname()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function slug()
    {
        return str_replace(' ', '-', strtolower($this->fullname()));
    }

    public function isVolunteer()
    {
        return (isset($this->volunteer()->first()->involvement)) ? true : false;
    }

    public function hasWaiver($year = null)
    {
        return UserWaiver::hasWaiver($this->id, $year);
    }

    public function profileComplete()
    {
        return $this->profile->isComplete();
    }

    public function coachingRequirements($year)
    {
        return $this->hasOne('Cupa\UserRequirement', 'user_id')
            ->where('year', '=', $year)->first();
    }

    public static function typeahead($filter, $ids = false)
    {
        $filter = urldecode($filter);

        if (empty($filter)) {
            return [];
        }

        $results = static::orderBy('last_name')
            ->orderBy('first_name');

        if ($ids) {
            $filter = explode(',', $filter);
            $results->whereIn('id', $filter);
        } else {
            $results->where(DB::raw("CONCAT_WS(' ', first_name, last_name)"), 'LIKE', '%'.$filter.'%');
        }

        $data = [];
        foreach ($results->get() as $row) {
            $data[] = [
                'id' => $row['id'],
                'text' => $row['first_name'].' '.$row['last_name'],
            ];
        }

        return $data;
    }

    public static function generateCode($column, $length = 25)
    {
        $code = str_random($length);
        while (!static::isUnique($column, $code)) {
            $code = str_random($length);
        }

        return $code;
    }

    public static function isUnique($column, $value)
    {
        $result = static::where($column, '=', $value)->first();
        if (!$result) {
            return true;
        }

        return false;
    }

    public static function checkForDuplicate($data)
    {
        $result = static::where('first_name', 'LIKE', $data['first_name'])
            ->where('last_name', 'LIKE', $data['last_name'])
            ->where('birthday', '=', (new Carbon($data['birthday']))->format('Y-m-d'))
            ->first();

        if (isset($result->id)) {
            return $result;
        }

        return;
    }

    public static function fetchAllDuplicates()
    {
        $duplicates = [];
        foreach (static::orderBy('created_at')->get() as $user) {
            $duplicates[$user->fullname()][] = $user;
        }

        foreach ($duplicates as $key => $duplicate) {
            $parents = [];
            foreach ($duplicate as $i => $dup) {
                if ($dup->parent === null) {
                    $parents[] = $dup->id;
                }
            }

            // check for minor same as parent
            foreach ($duplicate as $i => $dup) {
                if ($dup->parent !== null  && in_array($dup->parent, $parents)) {
                    unset($duplicates[$key][$i]);
                }

                $userIds[] = $dup->id;
            }

            // remove non duplicates
            if (count($duplicates[$key]) < 2) {
                unset($duplicates[$key]);
            }
        }

        return $duplicates;
    }

    public function combineDuplicates()
    {
        try {
            DB::beginTransaction();

            $duplicates = [];
            $results = self::where('id', '<>', $this->id)
                           ->where(DB::raw("CONCAT_WS(' ', first_name, last_name)"), '=', $this->fullname())
                           ->get();

            // update user_ids in these tables
            $tables = ['league_members', 'officers', 'pickup_contacts', 'posts', 'team_members', 'tournament_members', 'volunteer_event_contacts', 'volunteers'];

            $log = new Logger('merge-log');
            $log->pushHandler(new StreamHandler(storage_path().'/logs/'.str_replace(' ', '_', $this->fullname()).'-merge.log'));
            $log->addInfo('Merging duplicate users into '.$this->fullname().' #'.$this->id);

            $log->addInfo('Merging User Columns');
            $log->addInfo('=============================================================');
            // merge all tables with the user_id column
            foreach ($results as $duplicate) {
                foreach ($tables as $table) {
                    if ($table == 'volunteers') {
                        if (!$this->isVolunteer()) {
                            $sql = "UPDATE `{$table}` SET `user_id` = {$this->id} WHERE `user_id` = {$duplicate->id}";
                            DB::update("UPDATE `{$table}` SET `user_id` = ? WHERE `user_id` = ?", [$this->id, $duplicate->id]);
                        }
                    } elseif ($table != 'posts') {
                        $sql = "UPDATE `{$table}` SET `user_id` = {$this->id} WHERE `user_id` = {$duplicate->id}";
                        DB::update("UPDATE `{$table}` SET `user_id` = ? WHERE `user_id` = ?", [$this->id, $duplicate->id]);
                    } else {
                        $sql = "UPDATE `{$table}` SET `posted_by` = {$this->id} WHERE `posted_by` = {$duplicate->id}";
                        DB::update("UPDATE `{$table}` SET `posted_by` = ? WHERE `posted_by` = ?", [$this->id, $duplicate->id]);
                    }
                    $log->addInfo($sql);
                }

                $log->addInfo('Merging User Contacts');
                $log->addInfo('=============================================================');
                // concat user_contacts
                foreach ($duplicate->contacts as $contact) {
                    if (UserContact::hasContact($this->id, $contact->name, $contact->phone)) {
                        $log->addInfo('Ignoring contact '.$contact->name.' ('.$contact->phone.')');
                    } else {
                        $log->addInfo('Adding contact '.$contact->name.' ('.$contact->phone.')');
                        UserContact::create([
                            'user_id' => $this->id,
                            'name' => $contact->name,
                            'phone' => $contact->phone,
                        ]);
                    }
                }

                $log->addInfo('Merging User Profile');
                $log->addInfo('=============================================================');
                $needSave = false;
                $userProfile = $this->profile;
                // concat user_profiles
                foreach ($duplicate->profile->toArray() as $key => $value) {
                    if (empty($key) || in_array($key, ['id', 'user_id', 'created_at', 'updated_at'])) {
                        continue;
                    }

                    $log->addInfo('Key: `'.$key.'`');
                    $log->addInfo('Value: `'.$userProfile->$key.'`');
                    $log->addInfo('Isset: '.isset($userProfile->$key));
                    $log->addInfo('Empty: '.empty($userProfile->$key));

                    if (!isset($userProfile->$key) || empty($userProfile->$key)) {
                        $needSave = true;
                        $log->addInfo('Updating Profile '.$key.': `'.$userProfile->$key.'` => `'.$value.'`');
                        $userProfile->$key = $value;
                    } else {
                        $log->addInfo('Ignoring Profile '.$key.': `'.$userProfile->$key.'` => `'.$value.'`');
                    }
                }

                if ($needSave) {
                    $userProfile->save();
                }

                $log->addInfo('Merging User Waivers');
                $log->addInfo('=============================================================');
                // concat user_waivers
                foreach (UserWaiver::fetchAllWaivers($duplicate->id) as $waiver) {
                    if (!UserWaiver::hasWaiver($this->id, $waiver->year)) {
                        $log->addInfo('Signing waiver for '.$waiver->year);
                        UserWaiver::signWaiver($this->id, $waiver->year);
                    } else {
                        $log->addInfo('Ignoring waiver for '.$waiver->year);
                    }
                }

                // remove the user and all data with user
                $duplicate->delete();
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();

            return $e->getMessage();
        }
    }

    public function hasSignedUpForVolunteerEvent($eventId)
    {
        return VolunteerEvent::isMember($eventId, $this->volunteer()->first()->id);
    }

    public function fetchAllIds()
    {
        $ids = [];
        $result = self::where('id', '=', $this->id)
            ->orWhere('parent', '=', $this->id);

        foreach ($result->get() as $user) {
            $ids[] = $user->id;
        }

        return $ids;
    }

    public function fetchAllLeagues()
    {
        return LeagueMember::fetchAllLeagues($this->fetchAllIds(), true);
    }

    public function isLeagueMember($leagueId)
    {
        return LeagueMember::isMember($leagueId, $this->id);
    }

    public static function fetchRegistrantsForRadio($name)
    {
        $user = Auth::user();
        $registrants = [$user->fullname() => ['name' => $name, 'value' => $user->id]];
        foreach (static::fetchMinors($user->id) as $minor) {
            $registrants[$minor->fullname()] = ['name' => $name, 'value' => $minor->id];
        }

        return $registrants;
    }

    public static function fetchMinors($userId)
    {
        return static::where('parent', '=', $userId)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    public static function fetchAllPlayers($leagueId, $userId)
    {
        $userIds = [$userId];
        foreach (self::find($userId)->children as $child) {
            $userIds[] = $child->id;
        }

        return LeagueMember::fetchAllMembersFromUser($leagueId, $userIds);
    }

    public function getAge()
    {
        return (new DateTime($this->birthday))->diff(new DateTime('now'))->y;
    }

    public function signWaiver($year)
    {
        return UserWaiver::signWaiver($this->id, $year);
    }

    public static function fetchBy($column, $value)
    {
        return static::where($column, '=', $value)->first();
    }

    public function fetchPasswordResetCode()
    {
        if ($this->reset_password_code === null) {
            $this->reset_password_code = self::generateCode('reset_password_code');
            $this->save();
        }

        return $this->reset_password_code;
    }

    public static function fetchBySlug($slug)
    {
        $fullname = str_replace('-', ' ', $slug);
        $user = static::where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $fullname)->first();

        return $user;
    }
}
