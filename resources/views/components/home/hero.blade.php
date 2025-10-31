<section class="relative flex items-center justify-center grid-pattern" id="hero">
    <div class="container mx-auto px-4 py-20 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8">
                <div class="space-y-4">
                    <h1 class="text-5xl lg:text-6xl font-bold text-balance leading-tight">
                        La plataforma completa para <span class="text-primary">salud comunitaria</span>
                    </h1>
                    <p class="text-xl text-muted-foreground text-pretty leading-relaxed">
                        Conecta, empodera y transforma tu comunidad con herramientas
                        inteligentes para el bienestar colectivo. Construye un futuro más
                        saludable juntos.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/admin/login"
                        class="cursor-pointer hover:bg-blue-500 transition-colors duration-150 bg-black text-white px-4 py-2 rounded-sm flex justify-center items-center gap-1">
                        Iniciar Sesión
                        <x-lucide-arrow-right class='ml-2 h-4 w-4' />
                    </a>
                </div>

                <div class="flex items-center space-x-8 pt-4">
                    <div class="flex items-center space-x-2">
                        <x-lucide-heart class="h-5 w-5 text-primary" />
                        <span class="text-sm text-muted-foreground">Centrado en el bienestar</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-lucide-users class='h-5 w-5 text-primary' />
                        <span class="text-sm text-muted-foreground">Enfoque comunitario</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-lucide-shield class="h-5 w-5 text-primary" />
                        <span class="text-sm text-muted-foreground">Datos seguros</span>
                    </div>
                </div>
            </div>

            <div class="relative motion-safe:animate-bounce-soft will-change-transform" id="dashboard-mockup">
                <div class="bg-card border border-border rounded-2xl p-8 shadow-2xl">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold">Panel de Salud Comunitaria</h3>
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-secondary rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium">Participación Comunitaria</span>
                                    <span class="text-primary font-bold">+85%</span>
                                </div>
                                <div class="w-full bg-muted rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full w-4/5"></div>
                                </div>
                            </div>

                            <div class="bg-secondary rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium">Programas Activos</span>
                                    <span class="text-primary font-bold">24</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2 mt-3">
                                    <div class="bg-primary/20 rounded p-2 text-xs text-center">
                                        Nutrición
                                    </div>
                                    <div class="bg-primary/20 rounded p-2 text-xs text-center">
                                        Ejercicio
                                    </div>
                                    <div class="bg-primary/20 rounded p-2 text-xs text-center">
                                        Mental
                                    </div>
                                </div>
                            </div>

                            <div class="bg-secondary rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium">Impacto en Salud</span>
                                    <span class="text-primary font-bold">↗ 92%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
