<?php
$stats = [
    [
        'value' => '500+',
        'label' => 'Comunidades Activas',
        'description' => 'Transformando vidas diariamente',
        'company' => 'Hospitales Públicos',
    ],
    [
        'value' => '95%',
        'label' => 'Mejora en Indicadores',
        'description' => 'De salud comunitaria',
        'company' => 'Centros de Salud',
    ],
    [
        'value' => '50K+',
        'label' => 'Usuarios Beneficiados',
        'description' => 'En programas de bienestar',
        'company' => 'ONGs de Salud',
    ],
    [
        'value' => '3x',
        'label' => 'Más Participación',
        'description' => 'En actividades comunitarias',
        'company' => 'Municipalidades',
    ],
];
?>

<section class="py-20 bg-secondary/20" id="stats">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4 text-balance">
                Resultados que transforman comunidades
            </h2>
            <p class="text-lg text-muted-foreground text-pretty max-w-2xl mx-auto">
                Miles de organizaciones confían en Lazarus para mejorar la salud y el
                bienestar de sus comunidades
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($stats as $stat)
                <x-ui.card class="p-6 text-center bg-card border-border hover:border-primary/50 transition-colors">
                    <div class="space-y-3">
                        <div class="text-3xl font-bold text-primary">{{ $stat['value'] }}</div>
                        <div class="font-semibold text-foreground">{{ $stat['label'] }}</div>
                        <div class="text-sm text-muted-foreground">
                            {{ $stat['description'] }}
                        </div>
                        <div class="text-xs font-medium text-primary/80 uppercase tracking-wide">
                            {{ $stat['company'] }}
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </div>
</section>
