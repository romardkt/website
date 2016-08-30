<?php

namespace Cupa\Policies;

use Cupa\User;
use Cupa\Pickup;
use Illuminate\Auth\Access\HandlesAuthorization;

class PickupPolicy extends CachedPolicy
{
    use HandlesAuthorization;

    protected $globalPerms = ['admin', 'manager', 'editor'];

    private function isAuthorized(User $user, Pickup $pickup)
    {
        return $this->remember("pickup-auth-{$user->id}-{$pickup->id}", function() use ($user, $pickup) {
            $roles = $user->roles();
            foreach ($roles->get() as $role) {
                if (in_array($role->role->name, $this->globalPerms)) {
                    return true;
                }
            }

            return $pickup->contacts->contains('user_id', $user->id);
        });
    }

    public function show(User $user, Pickup $pickup)
    {
        if ($pickup->is_visible === 0) {
            return $this->isAuthorized($user, $pickup);
        }

        return true;
    }

    public function create(User $user, Pickup $pickup)
    {
        return $this->isAuthorized($user, $pickup);
    }

    public function edit(User $user, Pickup $pickup)
    {
        return $this->isAuthorized($user, $pickup);
    }

    public function delete(User $user, Pickup $pickup)
    {
        return $this->isAuthorized($user, $pickup);
    }
}
