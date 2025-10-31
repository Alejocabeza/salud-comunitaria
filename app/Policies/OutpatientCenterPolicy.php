<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OutpatientCenter;
use Illuminate\Auth\Access\HandlesAuthorization;

class OutpatientCenterPolicy
{
    use HandlesAuthorization;
    
    public function verTodo(AuthUser $authUser): bool
    {
        return $authUser->can('VerTodo:OutpatientCenter');
    }

    public function ver(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('Ver:OutpatientCenter');
    }

    public function crear(AuthUser $authUser): bool
    {
        return $authUser->can('Crear:OutpatientCenter');
    }

    public function actualizar(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('Actualizar:OutpatientCenter');
    }

    public function eliminar(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('Eliminar:OutpatientCenter');
    }

    public function restaurar(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('Restaurar:OutpatientCenter');
    }

    public function forzarEliminacion(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('ForzarEliminacion:OutpatientCenter');
    }

    public function forzarEliminacionTodo(AuthUser $authUser): bool
    {
        return $authUser->can('ForzarEliminacionTodo:OutpatientCenter');
    }

    public function restaurarTodo(AuthUser $authUser): bool
    {
        return $authUser->can('RestaurarTodo:OutpatientCenter');
    }

    public function replicar(AuthUser $authUser, OutpatientCenter $outpatientCenter): bool
    {
        return $authUser->can('Replicar:OutpatientCenter');
    }

    public function reordenar(AuthUser $authUser): bool
    {
        return $authUser->can('Reordenar:OutpatientCenter');
    }

}