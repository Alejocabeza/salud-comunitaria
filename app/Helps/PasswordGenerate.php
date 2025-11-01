<?php

namespace App\Helps;

use Illuminate\Support\Facades\Hash;

class PasswordGenerate
{
    public static function make(?string $password = null): string
    {
        if ($password) {
            return Hash::make($password);
        }
        return bin2hex(random_bytes(4));
    }
}
