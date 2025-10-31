<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678'),
        ]);

        $role = Role::firstOrCreate(['name' => config('filament-shield.super_admin.name', 'super_admin')]);

        $user = User::where('email', 'admin@example.com')->first();
        if ($user && ! $user->hasRole($role->name)) {
            $user->assignRole($role->name);
        }
    }
}
