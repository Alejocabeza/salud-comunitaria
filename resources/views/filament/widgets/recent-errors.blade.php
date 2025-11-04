<x-filament-widgets::widget>
    <x-filament::card>
        <h3 class="text-sm text-gray-500 mb-2">Últimos errores</h3>
        <ul class="space-y-2 text-sm">
            @foreach ($this->recent as $log)
                <li>
                    <div class="text-gray-700">[{{ optional($log->created_at)->toDateTimeString() }}]
                        <strong>{{ $log->message }}</strong></div>
                    @if ($log->context)
                        <div class="text-xs text-gray-500">{{ json_encode($log->context) }}</div>
                    @endif
                </li>
            @endforeach
        </ul>
    </x-filament::card>
</x-filament-widgets::widget>
