<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MedicationRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicationRequestPolicy
{
    use HandlesAuthorization;
    
    public function viewAll(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAll:MedicationRequest');
    }

    public function view(AuthUser $authUser, MedicationRequest $medicationRequest): bool
    {
        return $authUser->can('View:MedicationRequest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MedicationRequest');
    }

    public function update(AuthUser $authUser, MedicationRequest $medicationRequest): bool
    {
        return $authUser->can('Update:MedicationRequest');
    }

    public function delete(AuthUser $authUser, MedicationRequest $medicationRequest): bool
    {
        return $authUser->can('Delete:MedicationRequest');
    }

    public function restore(AuthUser $authUser, MedicationRequest $medicationRequest): bool
    {
        return $authUser->can('Restore:MedicationRequest');
    }

    public function forceDelete(AuthUser $authUser, MedicationRequest $medicationRequest): bool
    {
        return $authUser->can('ForceDelete:MedicationRequest');
    }

    public function forceDeleteTodo(AuthUser $authUser, MedicationRequest $medicationRequest): bool
    {
        return $authUser->can('ForceDeleteTodo:MedicationRequest');
    }

    public function forceDeleteAll(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAll:MedicationRequest');
    }

    public function replicate(AuthUser $authUser, MedicationRequest $medicationRequest): bool
    {
        return $authUser->can('Replicate:MedicationRequest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MedicationRequest');
    }

}