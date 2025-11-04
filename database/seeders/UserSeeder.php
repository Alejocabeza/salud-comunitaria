<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('12345678'),
            ]
        );

        // Assign super_admin role and give it all permissions
        $roleName = config('filament-shield.super_admin.name', 'super_admin');
        $guard = config('auth.defaults.guard', 'web');

        $role = Role::firstOrCreate([
            'name' => $roleName,
            'guard_name' => $guard,
        ]);

        $permissions = Permission::where('guard_name', $guard)->get();

        // If there are no permissions yet, try to generate them via Filament Shield
        if ($permissions->isEmpty()) {
            $this->command?->info('No permissions found; generating permissions via Filament Shield...');
            try {
                Artisan::call('shield:generate', [
                    '--panel' => 'admin',
                    '--option' => 'permissions',
                    '--all' => true,
                    '--no-interaction' => true,
                ]);
                // Reset permission cache and reload
                Artisan::call('permission:cache-reset');
            } catch (\Throwable $e) {
                $this->command?->error('Failed to generate permissions: ' . $e->getMessage());
            }

            $permissions = Permission::where('guard_name', $guard)->get();
        }

        if ($permissions->isNotEmpty()) {
            $role->syncPermissions($permissions);
            $this->command?->info("Assigned {$permissions->count()} permissions to role '{$roleName}'.");
        } else {
            $this->command?->info("No permissions found for guard '{$guard}' to assign to '{$roleName}'.");
        }

        // Assign the role to the created user
        $user->assignRole($roleName);
        $this->command?->info("Assigned role '{$roleName}' to user {$user->email}.");
    }
}
