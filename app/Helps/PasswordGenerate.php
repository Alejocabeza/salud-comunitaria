<?php

namespace App\Helps;

class PasswordGenerate
{
    public static function make(?string $password = null): string
    {
        if ($password) {
            return $password;
        }

        return bin2hex(random_bytes(4));
    }
}
