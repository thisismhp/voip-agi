<?php

namespace App\Policies;

use App\User;
use App\Service;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any services.
     *
     * @return mixed
     */
    public function viewAny()
    {
        return true;
    }

    /**
     * Determine whether the user can view the service.
     *
     * @param User $user
     * @param Service $service
     * @return mixed
     */
    public function view(User $user, Service $service)
    {
        return $user->serviceOwner($service);
    }

    /**
     * Determine whether the user can create services.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the service.
     *
     * @param User $user
     * @param Service $service
     * @return mixed
     */
    public function update(User $user, Service $service)
    {
        return $user->serviceOwner($service);
    }

    /**
     * Determine whether the user can delete the service.
     *
     * @param User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the service.
     *
     * @param User $user
     * @return mixed
     */
    public function restore(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the service.
     *
     * @param User $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        return $user->isAdmin();
    }
}
