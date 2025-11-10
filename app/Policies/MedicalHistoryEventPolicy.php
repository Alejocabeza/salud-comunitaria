<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MedicalHistoryEvent;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MedicalHistoryEventPolicy
{
    use HandlesAuthorization;

    public function viewAll(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAll:MedicalHistoryEvent');
    }

    public function view(AuthUser $authUser, MedicalHistoryEvent $medicalHistoryEvent): bool
    {
        return $authUser->can('View:MedicalHistoryEvent');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MedicalHistoryEvent');
    }

    public function update(AuthUser $authUser, MedicalHistoryEvent $medicalHistoryEvent): bool
    {
        return $authUser->can('Update:MedicalHistoryEvent');
    }

    public function delete(AuthUser $authUser, MedicalHistoryEvent $medicalHistoryEvent): bool
    {
        return $authUser->can('Delete:MedicalHistoryEvent');
    }

    public function restore(AuthUser $authUser, MedicalHistoryEvent $medicalHistoryEvent): bool
    {
        return $authUser->can('Restore:MedicalHistoryEvent');
    }

    public function forceDelete(AuthUser $authUser, MedicalHistoryEvent $medicalHistoryEvent): bool
    {
        return $authUser->can('ForceDelete:MedicalHistoryEvent');
    }

    public function forceDeleteTodo(AuthUser $authUser, MedicalHistoryEvent $medicalHistoryEvent): bool
    {
        return $authUser->can('ForceDeleteTodo:MedicalHistoryEvent');
    }

    public function forceDeleteAll(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAll:MedicalHistoryEvent');
    }

    public function replicate(AuthUser $authUser, MedicalHistoryEvent $medicalHistoryEvent): bool
    {
        return $authUser->can('Replicate:MedicalHistoryEvent');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MedicalHistoryEvent');
    }
}
