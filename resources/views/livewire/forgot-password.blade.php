<?php

use App\Models\User;
use function Livewire\Volt\{state};
use Livewire\Volt\Component;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

new class extends Component {
    public string $identifier = '';

    public function rules()
    {
        return [
            'identifier' => 'required|email|max:255|string',
        ];
    }

    public function forgotPassword()
    {
        try {
            $this->validate();

            $user = User::where('email', $this->identifier)->first();

            if (!$user) {
                $this->addError('identifier', 'No se encontró un usuario con ese correo.');
                $this->dispatch('toast', [
                    'type' => 'danger',
                    'message' => 'No se encontró un usuario con ese correo.',
                    'time' => now()->toDateTimeString(),
                ]);
                return;
            }

            $response = Password::sendResetLink(['email' => $user->email]);

            if ($response == Password::RESET_LINK_SENT) {
                $this->dispatch('toast', [
                    'type' => 'success',
                    'message' => Lang::get($response),
                    'time' => now()->toDateTimeString(),
                ]);
            } else {
                $this->dispatch('toast', [
                    'type' => 'danger',
                    'message' => Lang::get($response),
                    'time' => now()->toDateTimeString(),
                ]);
            }
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                    $this->dispatch('toast', [
                        'type' => 'danger',
                        'message' => $message,
                        'time' => now()->toDateTimeString(),
                    ]);
                }
            }
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
                    Inicia sesión en tu cuenta
                </h1>
                <form class="space-y-4 md:space-y-6" wire:submit="forgotPassword">
                    <div>
                        <label for="identifier" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Correo Electronico
                        </label>
                        <input type="text" name="identifier" id="identifier" wire:model="identifier"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Correo Electronico" required="">
                        @error('identifier')
                            <p class="text-sm text-red-600 dark:text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center text-white bg-primary hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <span wire:loading.remove wire:target="forgotPassword">
                            Enviar enlace de restablecimiento
                        </span>
                        <div wire:loading wire:target="forgotPassword">
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
