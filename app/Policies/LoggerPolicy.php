<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Logger;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class LoggerPolicy
{
    use HandlesAuthorization;

    public function viewAll(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAll:Logger');
    }

    public function view(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('View:Logger');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Logger');
    }

    public function update(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('Update:Logger');
    }

    public function delete(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('Delete:Logger');
    }

    public function restore(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('Restore:Logger');
    }

    public function forceDelete(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('ForceDelete:Logger');
    }

    public function forceDeleteTodo(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('ForceDeleteTodo:Logger');
    }

    public function forceDeleteAll(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAll:Logger');
    }

    public function replicate(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('Replicate:Logger');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Logger');
    }
}
