@php use Illuminate\Support\Str; @endphp

<x-filament-widgets::widget>
    <x-filament::card>
        <h3 class="text-sm text-gray-500 mb-2">Top errores</h3>
        <ul class="space-y-2">
            @foreach ($this->top as $row)
                <li class="text-sm">
                    <strong class="text-gray-800">{{ Str::limit($row->message, 120) }}</strong>
                    <span class="text-gray-500"> — {{ $row->cnt }}</span>
                </li>
            @endforeach
        </ul>
    </x-filament::card>
</x-filament-widgets::widget>
