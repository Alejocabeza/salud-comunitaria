<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Lesion;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class LesionPolicy
{
    use HandlesAuthorization;

    public function viewAll(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAll:Lesion');
    }

    public function view(AuthUser $authUser, Lesion $lesion): bool
    {
        return $authUser->can('View:Lesion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Lesion');
    }

    public function update(AuthUser $authUser, Lesion $lesion): bool
    {
        return $authUser->can('Update:Lesion');
    }

    public function delete(AuthUser $authUser, Lesion $lesion): bool
    {
        return $authUser->can('Delete:Lesion');
    }

    public function restore(AuthUser $authUser, Lesion $lesion): bool
    {
        return $authUser->can('Restore:Lesion');
    }

    public function forceDelete(AuthUser $authUser, Lesion $lesion): bool
    {
        return $authUser->can('ForceDelete:Lesion');
    }

    public function forceDeleteTodo(AuthUser $authUser, Lesion $lesion): bool
    {
        return $authUser->can('ForceDeleteTodo:Lesion');
    }

    public function forceDeleteAll(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAll:Lesion');
    }

    public function replicate(AuthUser $authUser, Lesion $lesion): bool
    {
        return $authUser->can('Replicate:Lesion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Lesion');
    }
}
