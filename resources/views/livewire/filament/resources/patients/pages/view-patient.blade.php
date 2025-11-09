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
        <x-filament::tabs.item :active="$activeTab === 'medical_history'" wire:click="$set('activeTab', 'medical_history')">
            Historial Medico
        </x-filament::tabs.item>
    </x-filament::tabs>

    <div x-show="$wire.activeTab === 'patient'" class="py-4">
        {{ $this->infolist }}
    </div>

    <div x-show="$wire.activeTab === 'diseases'" class="py-4">
        @livewire(\App\Filament\Resources\Patients\RelationManagers\DiseasesRelationManager::class, ['ownerRecord' => $record, 'pageClass' => \App\Filament\Resources\Patients\Pages\ViewPatient::class])
    </div>

    <div x-show="$wire.activeTab === 'lesions'" class="py-4">
        @livewire(\App\Filament\Resources\Patients\RelationManagers\LesionsRelationManager::class, ['ownerRecord' => $record, 'pageClass' => \App\Filament\Resources\Patients\Pages\ViewPatient::class])
    </div>

    <div x-show="$wire.activeTab === 'medical_history'" class="py-4">
        @livewire(\App\Filament\Resources\Patients\RelationManagers\MedicalHistoriesRelationManager::class, ['ownerRecord' => $record, 'pageClass' => \App\Filament\Resources\Patients\Pages\ViewPatient::class])
    </div>

</x-filament-panels::page>
