<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as FilamentLogin;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use SensitiveParameter;

class Login extends FilamentLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('dni')
                    ->label('Nombre/Email/Cédula')
                    ->required()
                    ->autofocus(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent()
            ]);
    }

    /**
     * Map the submitted form data to the credentials array expected by the
     * authentication layer. Filament's default expects an 'email' key; here we
     * support 'dni' so users can log in with their cédula.
     *
     * @param  array<string, mixed>  $data
     */
    protected function getCredentialsFromFormData(#[SensitiveParameter] array $data): array
    {
        if (isset($data['dni'])) {
            return [
                'dni' => $data['dni'],
                'password' => $data['password'] ?? '',
            ];
        }

        return parent::getCredentialsFromFormData($data);
    }
}
