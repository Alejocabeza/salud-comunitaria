<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Doctor;
use Illuminate\Auth\Access\HandlesAuthorization;

class DoctorPolicy
{
    use HandlesAuthorization;
    
    public function viewAll(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAll:Doctor');
    }

    public function view(AuthUser $authUser, Doctor $doctor): bool
    {
        return $authUser->can('View:Doctor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Doctor');
    }

    public function update(AuthUser $authUser, Doctor $doctor): bool
    {
        return $authUser->can('Update:Doctor');
    }

    public function delete(AuthUser $authUser, Doctor $doctor): bool
    {
        return $authUser->can('Delete:Doctor');
    }

    public function restore(AuthUser $authUser, Doctor $doctor): bool
    {
        return $authUser->can('Restore:Doctor');
    }

    public function forceDelete(AuthUser $authUser, Doctor $doctor): bool
    {
        return $authUser->can('ForceDelete:Doctor');
    }

    public function forceDeleteTodo(AuthUser $authUser, Doctor $doctor): bool
    {
        return $authUser->can('ForceDeleteTodo:Doctor');
    }

    public function forceDeleteAll(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAll:Doctor');
    }

    public function replicate(AuthUser $authUser, Doctor $doctor): bool
    {
        return $authUser->can('Replicate:Doctor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Doctor');
    }

}