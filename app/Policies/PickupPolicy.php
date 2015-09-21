<?php

namespace Cupa\Policies;

use Cupa\User;
use Cupa\Pickup;

class PickupPolicy
{
    protected $globalPerms = ['admin', 'manager', 'editor'];

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    private function isAuthorized(User $user, Pickup $pickup)
    {
        $roles = $user->roles();
        if ($roles->count() > 0 && in_array($roles->first()->role->name, $this->globalPerms)) {
            return true;
        }

        return $pickup->contacts()->contains('user_id', $user->id);
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