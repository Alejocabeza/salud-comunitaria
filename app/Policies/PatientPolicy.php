<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Patient;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PatientPolicy
{
    use HandlesAuthorization;

    public function viewAll(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAll:Patient');
    }

    public function view(AuthUser $authUser, Patient $patient): bool
    {
        return $authUser->can('View:Patient');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Patient');
    }

    public function update(AuthUser $authUser, Patient $patient): bool
    {
        return $authUser->can('Update:Patient');
    }

    public function delete(AuthUser $authUser, Patient $patient): bool
    {
        return $authUser->can('Delete:Patient');
    }

    public function restore(AuthUser $authUser, Patient $patient): bool
    {
        return $authUser->can('Restore:Patient');
    }

    public function forceDelete(AuthUser $authUser, Patient $patient): bool
    {
        return $authUser->can('ForceDelete:Patient');
    }

    public function forceDeleteTodo(AuthUser $authUser, Patient $patient): bool
    {
        return $authUser->can('ForceDeleteTodo:Patient');
    }

    public function forceDeleteAll(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAll:Patient');
    }

    public function replicate(AuthUser $authUser, Patient $patient): bool
    {
        return $authUser->can('Replicate:Patient');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Patient');
    }
}
