<?php

namespace Cupa\Policies;

use Cupa\User;
use Cupa\VolunteerEvent;
use Illuminate\Auth\Access\HandlesAuthorization;

class VolunteerEventPolicy extends CachedPolicy
{
    use HandlesAuthorization;

    protected $globalPerms = ['admin', 'manager', 'volunteer'];

    private function isAuthorized(User $user, VolunteerEvent $volunteerEvent)
    {
        return $this->remember("volunteerEvent-auth-{$user->id}-{$volunteerEvent->id}", function() use ($user, $volunteerEvent) {
            $roles = $user->roles();
            foreach ($roles->get() as $role) {
                if (in_array($role->role->name, $this->globalPerms)) {
                    return true;
                }
            }

            return $volunteerEvent->contacts->contains('user_id', $user->id);
        });
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
        return $this->remember("volunteerEvent-auth-{$user->id}-{$volunteerEvent->id}", function() use ($user, $volunteerEvent) {
            return $user->roles()->first()->name === 'admin';
        });
    }
}
