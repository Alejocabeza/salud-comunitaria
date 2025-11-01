<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Logger;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoggerPolicy
{
    use HandlesAuthorization;

    public function verTodo(AuthUser $authUser): bool
    {
        return $authUser->can('VerTodo:Logger');
    }

    public function ver(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('Ver:Logger');
    }

    public function crear(AuthUser $authUser): bool
    {
        return $authUser->can('Crear:Logger');
    }

    public function actualizar(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('Actualizar:Logger');
    }

    public function eliminar(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('Eliminar:Logger');
    }

    public function restaurar(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('Restaurar:Logger');
    }

    public function forzarEliminacion(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('ForzarEliminacion:Logger');
    }

    public function forzarEliminacionTodo(AuthUser $authUser): bool
    {
        return $authUser->can('ForzarEliminacionTodo:Logger');
    }

    public function restaurarTodo(AuthUser $authUser): bool
    {
        return $authUser->can('RestaurarTodo:Logger');
    }

    public function replicar(AuthUser $authUser, Logger $logger): bool
    {
        return $authUser->can('Replicar:Logger');
    }

    public function reordenar(AuthUser $authUser): bool
    {
        return $authUser->can('Reordenar:Logger');
    }

    // Standard Laravel policy methods (English) used by some callers
    public function viewAny(AuthUser $authUser): bool
    {
        // admins can view all; otherwise allow if they have the generated permission
        return method_exists($authUser, 'hasRole') && $authUser->hasRole('admin')
            || $authUser->can('ViewAny:Logger')
            || $authUser->can('VerTodo:Logger');
    }

    public function view(AuthUser $authUser, Logger $logger): bool
    {
        // admins can view any; owners can view their own logs; or users with explicit permission
        if (method_exists($authUser, 'hasRole') && $authUser->hasRole('admin')) {
            return true;
        }

        if ($authUser->id === $logger->user_id) {
            return true;
        }

        return $authUser->can('View:Logger') || $authUser->can('Ver:Logger');
    }
}
