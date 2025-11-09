<?php
$features = [
    [
        'icon' => 'users',
        'title' => 'Gestión Comunitaria',
        'description' => 'Organiza y coordina programas de salud para toda tu comunidad con herramientas intuitivas.',
    ],
    [
        'icon' => 'bar-chart-3',
        'title' => 'Analytics Avanzados',
        'description' => 'Monitorea el impacto de tus programas con métricas detalladas y reportes en tiempo real.',
    ],
    [
        'icon' => 'heart',
        'title' => 'Seguimiento de Bienestar',
        'description' => 'Rastrea indicadores de salud comunitaria y identifica áreas de mejora automáticamente.',
    ],
    [
        'icon' => 'message-square',
        'title' => 'Comunicación Efectiva',
        'description' => 'Mantén a tu comunidad informada con notificaciones personalizadas y canales de comunicación.',
    ],
    [
        'icon' => 'calendar',
        'title' => 'Programación Inteligente',
        'description' => 'Planifica eventos, citas y actividades con un sistema de calendario colaborativo.',
    ],
    [
        'icon' => 'shield',
        'title' => 'Privacidad Garantizada',
        'description' => 'Protege los datos de salud con encriptación de nivel médico y cumplimiento HIPAA.',
    ],
    [
        'icon' => 'smartphone',
        'title' => 'Acceso Móvil',
        'description' => 'Aplicación móvil nativa para que tu comunidad acceda desde cualquier dispositivo.',
    ],
    [
        'icon' => 'globe',
        'title' => 'Integración Universal',
        'description' => 'Conecta con sistemas de salud existentes y plataformas de terceros sin complicaciones.',
    ],
];
?>
<section id="features" class="py-20">

    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4 text-balance">
                Herramientas poderosas para <span class="text-primary">comunidades saludables</span>
            </h2>
            <p class="text-lg text-muted-foreground text-pretty max-w-2xl mx-auto">
                Todo lo que necesitas para crear, gestionar y hacer crecer programas de
                salud comunitaria exitosos
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($features as $feature)
                <x-ui.card
                    class="p-6 bg-card border-border hover:border-primary/50 transition-all duration-300 hover:shadow-lg">
                    <div class="space-y-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                            <x-dynamic-component :component="'lucide-' . $feature['icon']" class="h-6 w-6 text-primary" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-foreground mb-2">
                                {{ $feature['title'] }}
                            </h3>
                            <p class="text-sm text-muted-foreground leading-relaxed">
                                {{ $feature['description'] }}
                            </p>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </div>
</section>
