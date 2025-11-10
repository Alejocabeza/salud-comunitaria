<x-filament-panels::page>
    <x-filament::tabs>
        <x-filament::tabs.item :active="$activeTab === 'patient'" wire:click="$set('activeTab', 'patient')">
            Paciente
        </x-filament::tabs.item>
        <x-filament::tabs.item :active="$activeTab === 'events'" wire:click="$set('activeTab', 'events')">
            Eventos Medicos
        </x-filament::tabs.item>
    </x-filament::tabs>

    <div x-show="$wire.activeTab === 'patient'" class="py-4">
        {{ $this->infolist }}
    </div>

    <div x-show="$wire.activeTab === 'events'" class="py-4">
        @livewire(\App\Filament\Resources\MedicalHistories\RelationManagers\EventsRelationManager::class, ['ownerRecord' => $record, 'pageClass' => \App\Filament\Resources\MedicalHistories\Pages\ViewMedicalHistory::class])
    </div>

</x-filament-panels::page>
