<?php

namespace Cupa\Policies;

use Cupa\Officer;
use Cupa\User;

class OfficerPolicy
{
    protected $globalPerms = ['admin', 'manager', 'editor'];

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    private function isAuthorized(User $user, Officer $officer)
    {
        $roles = $user->roles();

        if ($roles->count() > 0 && in_array($roles->first()->role->name, $this->globalPerms)) {
            return true;
        }

        return $officer->user_id == $user->id;
    }

    public function show(User $user, Officer $officer)
    {
        return true;
    }

    public function create(User $user, Officer $officer)
    {
        return $this->isAuthorized($user, $officer);
    }

    public function edit(User $user, Officer $officer)
    {
        return $this->isAuthorized($user, $officer);
    }

    public function delete(User $user, Officer $officer)
    {
        $roles = $user->roles();

        if ($roles->count() > 0 && in_array($roles->first()->role->name, $this->globalPerms)) {
            return true;
        }
    }
}
