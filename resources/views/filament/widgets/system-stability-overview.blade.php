<x-filament-widgets::widget>
    <x-filament::card>
        <div class="mb-4">
            <h3 class="text-sm text-gray-500">Logs (24h)</h3>
            <div class="text-2xl font-bold">{{ $this->logs24h }}</div>
        </div>

        <div>
            <h4 class="text-sm text-gray-500">Últimos registros</h4>
            <ul class="mt-2 space-y-2">
                @foreach ($this->recentLogs as $log)
                    <li class="text-sm">
                        <span class="text-gray-700">[{{ optional($log->created_at)->toDateTimeString() }}]</span>
                        <span class="ml-2">{{ $log->action }} @if ($log->user)
                                — {{ $log->user->getKey() }}
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </x-filament::card>
</x-filament-widgets::widget>
