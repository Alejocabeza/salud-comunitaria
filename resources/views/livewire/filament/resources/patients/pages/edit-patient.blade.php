<x-filament-panels::page>
    <x-filament::tabs>
        <x-filament::tabs.item :active="$activeTab === 'patient'" wire:click="$set('activeTab', 'patient')">
            Paciente
        </x-filament::tabs.item>
        <x-filament::tabs.item :active="$activeTab === 'diseases'" wire:click="$set('activeTab', 'diseases')">
            Enfermedades
        </x-filament::tabs.item>
        <x-filament::tabs.item :active="$activeTab === 'lesions'" wire:click="$set('activeTab', 'lesions')">
            Lesiones
        </x-filament::tabs.item>
        <x-filament::tabs.item :active="$activeTab === 'events'" wire:click="$set('activeTab', 'events')">
            Eventos
        </x-filament::tabs.item>
    </x-filament::tabs>

    <div x-show="$wire.activeTab === 'patient'" class="py-4">
        {{ $this->form }}
    </div>

    <div x-show="$wire.activeTab === 'diseases'" class="py-4">
        @livewire(\App\Filament\Resources\Patients\RelationManagers\DiseasesRelationManager::class, ['ownerRecord' => $record, 'pageClass' => \App\Filament\Resources\Patients\Pages\EditPatient::class])
    </div>

    <div x-show="$wire.activeTab === 'lesions'" class="py-4">
        @livewire(\App\Filament\Resources\Patients\RelationManagers\LesionsRelationManager::class, ['ownerRecord' => $record, 'pageClass' => \App\Filament\Resources\Patients\Pages\EditPatient::class])
    </div>

    <div x-show="$wire.activeTab === 'events'" class="py-4">
        @if ($record->medicalHistory)
            @livewire(\App\Filament\Resources\MedicalHistories\RelationManagers\EventsRelationManager::class, ['ownerRecord' => $record->medicalHistory, 'pageClass' => \App\Filament\Resources\Patients\Pages\EditPatient::class])
        @else
            <p class="text-center text-gray-500">No hay un historial médico principal para este paciente.</p>
        @endif
    </div>

</x-filament-panels::page>
