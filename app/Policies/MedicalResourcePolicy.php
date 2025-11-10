<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MedicalResource;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicalResourcePolicy
{
    use HandlesAuthorization;
    
    public function viewAll(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAll:MedicalResource');
    }

    public function view(AuthUser $authUser, MedicalResource $medicalResource): bool
    {
        return $authUser->can('View:MedicalResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MedicalResource');
    }

    public function update(AuthUser $authUser, MedicalResource $medicalResource): bool
    {
        return $authUser->can('Update:MedicalResource');
    }

    public function delete(AuthUser $authUser, MedicalResource $medicalResource): bool
    {
        return $authUser->can('Delete:MedicalResource');
    }

    public function restore(AuthUser $authUser, MedicalResource $medicalResource): bool
    {
        return $authUser->can('Restore:MedicalResource');
    }

    public function forceDelete(AuthUser $authUser, MedicalResource $medicalResource): bool
    {
        return $authUser->can('ForceDelete:MedicalResource');
    }

    public function forceDeleteTodo(AuthUser $authUser, MedicalResource $medicalResource): bool
    {
        return $authUser->can('ForceDeleteTodo:MedicalResource');
    }

    public function forceDeleteAll(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAll:MedicalResource');
    }

    public function replicate(AuthUser $authUser, MedicalResource $medicalResource): bool
    {
        return $authUser->can('Replicate:MedicalResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MedicalResource');
    }

}