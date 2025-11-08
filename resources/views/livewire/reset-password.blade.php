<?php

use function Livewire\Volt\{state};
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Lang;

new class extends Component {
    public string $token = '';
    public string $identifier = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
    }

    public function rules(): array
    {
        return [
            'token' => 'required',
            'identifier' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function resetPassword()
    {
        $this->validate();

        $user = User::where('email', $this->identifier)->orWhere('dni', $this->identifier)->orWhere('name', $this->identifier)->first();

        if (!$user) {
            $this->addError('identifier', 'No se encontró un usuario con ese identificador.');
            $this->dispatch('toast', [
                'type' => 'danger',
                'message' => 'No se encontró un usuario con ese identificador.',
                'time' => now()->toDateTimeString(),
            ]);
            return;
        }

        $response = Password::reset(
            [
                'email' => $user->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function (User $user) {
                $user
                    ->forceFill([
                        'password' => Hash::make($this->password),
                    ])
                    ->save();

                $this->dispatch('toast', [
                    'type' => 'success',
                    'message' => 'Su contraseña ha sido restablecida.',
                    'time' => now()->toDateTimeString(),
                ]);

                $this->redirect('/login');
            },
        );

        if ($response == Password::PASSWORD_RESET) {
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => Lang::get($response),
                'time' => now()->toDateTimeString(),
            ]);
            $this->redirect('/login');
        } else {
            $this->dispatch('toast', [
                'type' => 'danger',
                'message' => Lang::get($response),
                'time' => now()->toDateTimeString(),
            ]);
        }
    }
};

?>

<section class="bg-gray-50 dark:bg-gray-900">
    @include('components.toast')
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="/" class="flex items-center gap-2 mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
            <x-logo />
            Lazarus
        </a>
        <div
            class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Restablecer Contraseña
                </h1>
                <form class="space-y-4 md:space-y-6" wire:submit="resetPassword">
                    <div>
                        <label for="identifier" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Correo Electronico, Nombre o Cedula
                        </label>
                        <input type="text" name="identifier" id="identifier" wire:model="identifier"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Email, Nombre o DNI" required="">
                        @error('identifier')
                            <p class="text-sm text-red-600 dark:text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Nueva Contraseña
                        </label>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                            wire:model="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required="">
                        @error('password')
                            <p class="text-sm text-red-600 dark:text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Confirmar Contraseña
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="••••••••" wire:model="password_confirmation"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required="">
                        @error('password_confirmation')
                            <p class="text-sm text-red-600 dark:text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center text-white bg-primary hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <span wire:loading.remove wire:target="resetPassword">
                            Restablecer Contraseña
                        </span>
                        <div wire:loading wire:target="resetPassword">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
