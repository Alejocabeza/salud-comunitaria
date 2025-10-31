<?php

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    
    public function verTodo(AuthUser $authUser): bool
    {
        return $authUser->can('VerTodo:User');
    }

    public function ver(AuthUser $authUser): bool
    {
        return $authUser->can('Ver:User');
    }

    public function crear(AuthUser $authUser): bool
    {
        return $authUser->can('Crear:User');
    }

    public function actualizar(AuthUser $authUser): bool
    {
        return $authUser->can('Actualizar:User');
    }

    public function eliminar(AuthUser $authUser): bool
    {
        return $authUser->can('Eliminar:User');
    }

    public function restaurar(AuthUser $authUser): bool
    {
        return $authUser->can('Restaurar:User');
    }

    public function forzarEliminacion(AuthUser $authUser): bool
    {
        return $authUser->can('ForzarEliminacion:User');
    }

    public function forzarEliminacionTodo(AuthUser $authUser): bool
    {
        return $authUser->can('ForzarEliminacionTodo:User');
    }

    public function restaurarTodo(AuthUser $authUser): bool
    {
        return $authUser->can('RestaurarTodo:User');
    }

    public function replicar(AuthUser $authUser): bool
    {
        return $authUser->can('Replicar:User');
    }

    public function reordenar(AuthUser $authUser): bool
    {
        return $authUser->can('Reordenar:User');
    }

}