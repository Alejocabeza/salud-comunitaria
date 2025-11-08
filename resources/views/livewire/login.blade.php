<?php

use function Livewire\Volt\{state};
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

new class extends Component {
    public string $identifier = '';
    public string $password = '';
    public bool $remember = false;

    public function rules()
    {
        return [
            'identifier' => 'required|max:255|string',
            'password' => 'required|min:6|max:58|string',
        ];
    }

    public function login()
    {
        try {
            $this->validate();
            $this->ensureIsNotRateLimited();

            $credentials = ['password' => $this->password];

            if (!Auth::attempt(array_merge($credentials, ['email' => $this->identifier]), $this->remember) && !Auth::attempt(array_merge($credentials, ['dni' => $this->identifier]), $this->remember) && !Auth::attempt(array_merge($credentials, ['name' => $this->identifier]), $this->remember)) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'identifier' => __('auth.failed'),
                ]);
            }

            RateLimiter::clear($this->throttleKey());
            Session::regenerate();
            $this->redirect('/admin');
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    Notification::make()->title($message)->danger()->send();
                    $this->addError($field, $message);
                }
            }
        }
    }

    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'identifier' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey()
    {
        return Str::transliterate(Str::lower($this->identifier) . '|' . request()->ip());
    }
};
?>

<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="/" class="flex items-center gap-2 mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
            <x-logo />
            Lazarus
        </a>
        <div
            class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Inicia sesión en tu cuenta
                </h1>
                <form class="space-y-4 md:space-y-6" wire:submit="login">
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
                            Contraseña
                        </label>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                            wire:model="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required="">
                        @error('password')
                            <p class="text-sm text-red-600 dark:text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="remember" aria-describedby="remember" type="checkbox" wire:model="remember"
                                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="remember" class="text-gray-500 dark:text-gray-300">Recuerdame</label>
                            </div>
                        </div>
                        <a href="/forgot-password"
                            class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Olvidaste
                            mi Contraseña?</a>
                    </div>
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center text-white bg-primary hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <span wire:loading.remove wire:target="login">
                            Iniciar sesión
                        </span>
                        <div wire:loading wire:target="login">
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
