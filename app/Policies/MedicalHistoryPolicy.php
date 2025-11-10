<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MedicalHistory;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicalHistoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAll(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAll:MedicalHistory');
    }

    public function view(AuthUser $authUser, MedicalHistory $medicalHistory): bool
    {
        return $authUser->can('View:MedicalHistory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MedicalHistory');
    }

    public function update(AuthUser $authUser, MedicalHistory $medicalHistory): bool
    {
        return $authUser->can('Update:MedicalHistory');
    }

    public function delete(AuthUser $authUser, MedicalHistory $medicalHistory): bool
    {
        return $authUser->can('Delete:MedicalHistory');
    }

    public function restore(AuthUser $authUser, MedicalHistory $medicalHistory): bool
    {
        return $authUser->can('Restore:MedicalHistory');
    }

    public function forceDelete(AuthUser $authUser, MedicalHistory $medicalHistory): bool
    {
        return $authUser->can('ForceDelete:MedicalHistory');
    }

    public function forceDeleteTodo(AuthUser $authUser, MedicalHistory $medicalHistory): bool
    {
        return $authUser->can('ForceDeleteTodo:MedicalHistory');
    }

    public function forceDeleteAll(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAll:MedicalHistory');
    }

    public function replicate(AuthUser $authUser, MedicalHistory $medicalHistory): bool
    {
        return $authUser->can('Replicate:MedicalHistory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MedicalHistory');
    }

}