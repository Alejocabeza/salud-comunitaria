<header class="bg-background/80 backdrop-blur-sm sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-lg">L</span>
            </div>
            <span class="text-xl font-bold text-black">Lazarus</span>
        </div>

        <nav class="hidden md:flex items-center space-x-8">
            <a href="#hero" class="text-muted-foreground hover:text-foreground transition-colors">
                Inicio
            </a>
            <a href="#stats" class="text-muted-foreground hover:text-foreground transition-colors">
                Beneficios
            </a>
            <a href="#features" class="text-muted-foreground hover:text-foreground transition-colors">
                Características
            </a>
        </nav>

        <div class="flex items-center space-x-4" id="auth-container">
            @auth
                <div>
                    <a href="/admin"
                        class="cursor-pointer hover:bg-blue-500 transition-colors duration-150 bg-black text-white px-3 py-2 rounded-sm">
                        Dashboard
                    </a>
                </div>
            @else
                <div>
                    <a href="/login"
                        class="cursor-pointer hover:bg-blue-500 transition-colors duration-150 bg-black text-white px-3 py-2 rounded-sm">
                        Iniciar Sesión
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>
