<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\OutpatientCenter;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class OutpatientCenterPolicy
{
    use HandlesAuthorization;

    public function viewAll(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAll:OutpatientCenter');
    }

    public function view(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('View:OutpatientCenter');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OutpatientCenter');
    }

    public function update(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('Update:OutpatientCenter');
    }

    public function delete(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('Delete:OutpatientCenter');
    }

    public function restore(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('Restore:OutpatientCenter');
    }

    public function forceDelete(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('ForceDelete:OutpatientCenter');
    }

    public function forceDeleteTodo(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('ForceDeleteTodo:OutpatientCenter');
    }

    public function forceDeleteAll(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAll:OutpatientCenter');
    }

    public function replicate(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('Replicate:OutpatientCenter');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OutpatientCenter');
    }
}
