<?php

namespace App\Filament\Resources\Roles;

use BackedEnum;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource as ShieldRoleResource;
use Filament\Support\Icons\Heroicon;

class RoleResource extends ShieldRoleResource
{
    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShieldCheck;

    public static function getNavigationGroup(): ?string
    {
        return 'Gestión del Sistema';
    }

    public static function getEssentialsPlugin(): ?FilamentShieldPlugin
    {
        return null;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->guard()->user();
        if (! $user) {
            return false;
        }

        return $user->can('ViewAll:Role');
    }
}
