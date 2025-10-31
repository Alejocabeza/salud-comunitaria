<?php

namespace App\Helps;

class PasswordGenerate
{
    public static function make()
    {
        return bin2hex(random_bytes(4));
    }
}
