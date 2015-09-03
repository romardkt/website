<?php

namespace Cupa;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Carbon\Carbon;
use DB;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
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

    public function roles()
    {
        return $this->hasMany('Cupa\UserRole');
    }

    public function profile()
    {
        return $this->hasOne('Cupa\UserProfile');
    }

    public function fullname()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function hasWaiver($year = null)
    {
        return UserWaiver::hasWaiver($this->id, $year);
    }

    public function profileComplete()
    {
        return $this->profile->isComplete();
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
}
