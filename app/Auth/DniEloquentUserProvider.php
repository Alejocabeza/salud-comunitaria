<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class DniEloquentUserProvider extends EloquentUserProvider
{
    public function __construct(HasherContract $hasher, $model)
    {
        parent::__construct($hasher, $model);
    }

    /**
     * Retrieve a user by the given credentials.
     * If the provided identifier is not an email, treat it as a DNI and
     * attempt to resolve an email from Patient/Doctor/OutpatientCenter records.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return null;
        }

    // Support several possible identifiers: email, username, or dni
    $identifier = $credentials['email'] ?? $credentials['username'] ?? $credentials['dni'] ?? null;

        if (! $identifier) {
            return parent::retrieveByCredentials($credentials);
        }

        // If it's an email, use normal behavior
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return parent::retrieveByCredentials($credentials);
        }

        // Otherwise treat as DNI: try to find a model with that dni and get its email
        $models = [
            \App\Models\Patient::class,
            \App\Models\Doctor::class,
            \App\Models\OutpatientCenter::class,
        ];

        $email = null;

        foreach ($models as $model) {
            try {
                $record = $model::where('dni', $identifier)->first();
            } catch (\Throwable $e) {
                $record = null;
            }

            if ($record && isset($record->email) && $record->email) {
                $email = $record->email;
                break;
            }
        }

        if ($email) {
            $newCredentials = $credentials;
            $newCredentials['email'] = $email;

            return parent::retrieveByCredentials($newCredentials);
        }

        return null;
    }
}
