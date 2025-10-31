<?php

namespace App\Filament\Resources\Roles;

use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource as ShieldRoleResource;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class RoleResource extends ShieldRoleResource
{
    protected static ?int $navigationSort = 3;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShieldCheck;

    public static function getNavigationGroup(): ?string
    {
        return 'Gestión del Sistema';
    }

    public static function getEssentialsPlugin(): ?FilamentShieldPlugin
    {
        return null;
    }
}
