<?php

namespace App\Policies;

use App\Models\OverrideLokasi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OverrideLokasiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OverrideLokasi $overrideLokasi): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OverrideLokasi $overrideLokasi): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OverrideLokasi $overrideLokasi): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OverrideLokasi $overrideLokasi): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OverrideLokasi $overrideLokasi): bool
    {
        return false;
    }
}
