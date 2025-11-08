<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => config('filament-shield.super_admin.name', 'super_admin')]);
        Role::firstOrCreate(['name' => 'Manager']);
        Role::firstOrCreate(['name' => 'Doctor']);
        Role::firstOrCreate(['name' => 'Paciente']);

        $user = User::where('email', 'admin@example.com')->first();
        if ($user && ! $user->hasRole($role->name)) {
            $user->assignRole($role->name);
        }
    }
}
