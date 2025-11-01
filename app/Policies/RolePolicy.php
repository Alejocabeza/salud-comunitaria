<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function verTodo(AuthUser $authUser): bool
    {
        return $authUser->can('VerTodo:Role');
    }

    public function ver(AuthUser $authUser, Role $role): bool
    {
        return $authUser->can('Ver:Role');
    }

    public function crear(AuthUser $authUser): bool
    {
        return $authUser->can('Crear:Role');
    }

    public function actualizar(AuthUser $authUser, Role $role): bool
    {
        return $authUser->can('Actualizar:Role');
    }

    public function eliminar(AuthUser $authUser, Role $role): bool
    {
        return $authUser->can('Eliminar:Role');
    }

    public function restaurar(AuthUser $authUser, Role $role): bool
    {
        return $authUser->can('Restaurar:Role');
    }

    public function forzarEliminacion(AuthUser $authUser, Role $role): bool
    {
        return $authUser->can('ForzarEliminacion:Role');
    }

    public function forzarEliminacionTodo(AuthUser $authUser): bool
    {
        return $authUser->can('ForzarEliminacionTodo:Role');
    }

    public function restaurarTodo(AuthUser $authUser): bool
    {
        return $authUser->can('RestaurarTodo:Role');
    }

    public function replicar(AuthUser $authUser, Role $role): bool
    {
        return $authUser->can('Replicar:Role');
    }

    public function reordenar(AuthUser $authUser): bool
    {
        return $authUser->can('Reordenar:Role');
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Role');
    }

    public function view(AuthUser $authUser, Role $role): bool
    {
        return $authUser->can('View:Role');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Role');
    }

    public function update(AuthUser $authUser, Role $role): bool
    {
        return $authUser->can('Update:Role');
    }

    public function delete(AuthUser $authUser, Role $role): bool
    {
        return $authUser->can('Delete:Role');
    }
}
