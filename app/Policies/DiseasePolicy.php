<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Disease;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiseasePolicy
{
    use HandlesAuthorization;
    
    public function viewAll(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAll:Disease');
    }

    public function view(AuthUser $authUser, Disease $disease): bool
    {
        return $authUser->can('View:Disease');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Disease');
    }

    public function update(AuthUser $authUser, Disease $disease): bool
    {
        return $authUser->can('Update:Disease');
    }

    public function delete(AuthUser $authUser, Disease $disease): bool
    {
        return $authUser->can('Delete:Disease');
    }

    public function restore(AuthUser $authUser, Disease $disease): bool
    {
        return $authUser->can('Restore:Disease');
    }

    public function forceDelete(AuthUser $authUser, Disease $disease): bool
    {
        return $authUser->can('ForceDelete:Disease');
    }

    public function forceDeleteTodo(AuthUser $authUser, Disease $disease): bool
    {
        return $authUser->can('ForceDeleteTodo:Disease');
    }

    public function forceDeleteAll(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAll:Disease');
    }

    public function replicate(AuthUser $authUser, Disease $disease): bool
    {
        return $authUser->can('Replicate:Disease');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Disease');
    }

}