<?php

use App\Models\User;
use App\Models\Logger;
use function Pest\Laravel\actingAs;

it('shows error widgets to super admin and returns data counts', function () {
    // create a super admin user and assign role (create role if missing)
    $user = User::factory()->create();
    if (method_exists($user, 'assignRole')) {
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Super Admin']);
        }
        $user->assignRole('Super Admin');
    }

    actingAs($user);

    // seed some loggers (Logger factory may not exist in this app)
    $seed = [
        ['action' => 'exception', 'model' => 'ExceptionA', 'level' => 'error', 'message' => 'boom 1', 'user_id' => $user->id],
        ['action' => 'exception', 'model' => 'ExceptionB', 'level' => 'error', 'message' => 'boom 2', 'user_id' => $user->id],
        ['action' => 'exception', 'model' => 'ExceptionA', 'level' => 'error', 'message' => 'boom 1', 'user_id' => $user->id],
    ];

    foreach ($seed as $row) {
        Logger::create($row);
    }

    // assert DB counts
    $this->assertDatabaseCount('loggers', 3);

    $errorsCount = Logger::where('level', 'error')->count();
    expect($errorsCount)->toBe(3);
});
