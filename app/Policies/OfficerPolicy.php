<?php

namespace Cupa\Policies;

use Cupa\Models\User;
use Cupa\Models\Officer;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfficerPolicy extends CachedPolicy
{
    use HandlesAuthorization;

    protected $globalPerms = ['admin', 'manager', 'editor'];

    private function isAuthorized(User $user, Officer $officer)
    {
        return $this->remember("officer-auth-{$user->id}-{$officer->id}", function() use ($user, $officer) {
            $roles = $user->roles();
            foreach ($roles->get() as $role) {
                if (in_array($role->role->name, $this->globalPerms)) {
                    return true;
                }
            }

            return $officer->user_id == $user->id;
        });
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
        return $this->remember("officer-delete-{$user->id}-{$officer->id}", function() use ($user, $officer) {
            $roles = $user->roles();

            if ($roles->count() > 0 && in_array($roles->first()->role->name, $this->globalPerms)) {
                return true;
            }
        });
    }
}
