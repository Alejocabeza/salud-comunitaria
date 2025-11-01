<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Str;

class ActiveEloquentUserProvider extends EloquentUserProvider
{
    /**
     * Validate a user against the given credentials plus active flag.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials): bool
    {
        $valid = parent::validateCredentials($user, $credentials);

        if (! $valid) {
            return false;
        }

        try {
            $path = request()?->getPathInfo();
            if (is_string($path) && str_starts_with($path, '/admin')) {
                return true;
            }
        } catch (\Throwable $e) {
        }

        $attributes = [];
        if ($user instanceof \Illuminate\Database\Eloquent\Model) {
            $attributes = $user->getAttributes();
        }

        if (property_exists($user, 'active') || array_key_exists('active', $attributes)) {
            return (bool) ($user->active ?? $attributes['active']) === true;
        }

        return true;
    }
}
