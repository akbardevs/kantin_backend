<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function create(User $currentUser)
    {
        return false;
    }

    /**
     * Determine whether the user can view any resource.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $currentUser, User $user)
    {
        return true;
    }

    public function viewAny(User $user)
    {
        //
        // if ($user->role === UserRole::MEMBER || $user->role === UserRole::USER) {
        //     return true;
        // }

        // return in_array($user->role, [UserRole::ADMIN, UserRole::MEMBER, UserRole::USER]);
        return true;
    }

    public function update(User $currentUser, User $user)
    {
        // return $this->updateAndDeleteRule($currentUser, $user);
        return false;
    }


    public function delete(User $currentUser, User $user)
    {
        // return $this->updateAndDeleteRule($currentUser, $user);
        return false;
    }

    private function updateAndDeleteRule(User $currentUser, User $user)
    {
        // if ($currentUser->role === UserRole::SUPER_ADMIN || $currentUser->role === UserRole::ADMIN) {
        //     return true;
        // }

        // return in_array($user->role, [UserRole::MEMBER]);
        return false;
    }
}
