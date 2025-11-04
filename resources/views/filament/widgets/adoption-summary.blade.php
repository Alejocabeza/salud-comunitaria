<x-filament-widgets::widget>
    <x-filament::card>
        <div class="grid grid-cols-4 gap-4">
            <div>
                <h3 class="text-sm text-gray-500">Comunidades</h3>
                <div class="text-2xl font-bold">{{ $this->communities }}</div>
            </div>

            <div>
                <h3 class="text-sm text-gray-500">Centros totales</h3>
                <div class="text-2xl font-bold">{{ $this->centers }}</div>
            </div>

            <div>
                <h3 class="text-sm text-gray-500">Centros activos</h3>
                <div class="text-2xl font-bold">{{ $this->activeCenters }}</div>
            </div>

            <div>
                <h3 class="text-sm text-gray-500">Comunidades adoptadas</h3>
                <div class="text-2xl font-bold">{{ $this->adoptionRate }}%</div>
            </div>
        </div>
    </x-filament::card>
</x-filament-widgets::widget>
