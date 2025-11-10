<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Appointment;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAll(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAll:Appointment');
    }

    public function view(AuthUser $authUser, Appointment $appointment): bool
    {
        return $authUser->can('View:Appointment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Appointment');
    }

    public function update(AuthUser $authUser, Appointment $appointment): bool
    {
        return $authUser->can('Update:Appointment');
    }

    public function delete(AuthUser $authUser, Appointment $appointment): bool
    {
        return $authUser->can('Delete:Appointment');
    }

    public function restore(AuthUser $authUser, Appointment $appointment): bool
    {
        return $authUser->can('Restore:Appointment');
    }

    public function forceDelete(AuthUser $authUser, Appointment $appointment): bool
    {
        return $authUser->can('ForceDelete:Appointment');
    }

    public function forceDeleteTodo(AuthUser $authUser, Appointment $appointment): bool
    {
        return $authUser->can('ForceDeleteTodo:Appointment');
    }

    public function forceDeleteAll(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAll:Appointment');
    }

    public function replicate(AuthUser $authUser, Appointment $appointment): bool
    {
        return $authUser->can('Replicate:Appointment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Appointment');
    }

}