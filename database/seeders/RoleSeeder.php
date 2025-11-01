<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        Role::firstOrCreate(['name' => 'manager']);
        Role::firstOrCreate(['name' => 'doctor']);
        Role::firstOrCreate(['name' => 'patient']);

        $user = User::where('email', 'admin@example.com')->first();
        if ($user && ! $user->hasRole($role->name)) {
            $user->assignRole($role->name);
        }
    }
}
