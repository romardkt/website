<?php

namespace Cupa\Policies;

use Cupa\User;
use Cupa\VolunteerEvent;

class VolunteerPolicy
{
    protected $globalPerms = ['admin', 'manager', 'volunteer'];

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    private function isAuthorized(User $user, VolunteerEvent $volunteerEvent)
    {
        $roles = $user->roles();
        if ($roles->count() > 0 && in_array($roles->first()->role->name, $this->globalPerms)) {
            return true;
        }

        return $volunteerEvent->contacts->contains('user_id', $user->id);
    }

    public function show(User $user, VolunteerEvent $volunteerEvent)
    {
        if ($volunteerEvent->is_visible === 0) {
            return $this->isAuthorized($user, $volunteerEvent);
        }

        return true;
    }

    public function create(User $user, VolunteerEvent $volunteerEvent)
    {
        return $this->isAuthorized($user, $volunteerEvent);
    }

    public function edit(User $user, VolunteerEvent $volunteerEvent)
    {
        return $this->isAuthorized($user, $volunteerEvent);
    }

    public function delete(User $user, VolunteerEvent $volunteerEvent)
    {
        return $user->roles()->first()->name === 'admin';
    }
}
