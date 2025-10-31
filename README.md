# Salud Comunitaria — Sistema (técnico)

## Resumen

Proyecto Laravel (Sistema de Salud Comunitaria) orientado a la gestión de recursos humanos, pacientes y procesos clínicos con panel administrativo basado en Filament v4. Este README describe la arquitectura, dependencias, configuración, flujos de desarrollo, pruebas y recomendaciones de seguridad y despliegue de forma técnica y extendida.

## Stack y versiones clave

-   PHP: 8.4.12
-   Laravel: 12 (estructura moderna)
-   Filament: v4 (Server Driven UI)
-   Livewire: v3 (Volt incluido para componentes)
-   Tailwind CSS: v4
-   Pest: v4 / PHPUnit v12
-   Otros: composer, npm (Vite/Vite/Volt según configuración del repo)

## Estructura principal del proyecto

(Resaltar carpetas relevantes)

-   app/
    -   Filament/Resources/ — Recursos CRUD para Filament (schemas, tables, actions)
    -   Filament/Pages/ — Páginas personalizadas del panel
    -   Filament/Widgets/ — Widgets para dash
    -   Models/ — Eloquent models
    -   Http/Controllers/ — APIs y logic fuera de Filament
    -   Http/Requests/ — FormRequests para validación
    -   Policies/ — Policies de autorización
    -   Providers/Filament/ — Proveedores de panel (ej: AdminPanelProvider.php)
-   database/
    -   seeders/ — Seeders (users, permissions)
    -   factories/ — Model factories
-   resources/ — assets (JS/CSS/Vue/Volt)
-   tests/ — Pest tests (Feature / Unit / Browser)
-   routes/ — routes/web.php, routes/api.php (según estructura v12)
-   .env — entorno (no comitear)
-   README.md — este archivo

## Conceptos arquitectónicos (Filament + Laravel)

-   Filament Resources: clases estáticas que definen formularios (Schemas), tablas (Table), infolists y acciones. Viven en app/Filament/Resources.
-   Panels: se configuran en providers (ej. app/Providers/Filament/AdminPanelProvider.php). Un panel define rutas y middleware para la UI de Filament.
-   Livewire / Volt: componentes server-driven; interactividad y estados se gestionan por PHP.
-   Requests & Controllers: para APIs o lógica externa al CRUD de Filament, use Controllers + FormRequests (validación centralizada).
-   Policies & Gates: autorización a nivel de modelos; Filament respeta las policies registradas en AuthServiceProvider.

## Instalación local

1. Clonar repo y dependencias:
    ```
    git clone <repo>
    cd salud-comunitaria-laravel
    composer install
    cp .env.example .env
    ```
2. Generar APP_KEY y configurar .env:

    ```
    php artisan key:generate
    ```

    Ajustar DB\_\* y otros servicios (MAIL, QUEUE, S3, etc).

3. Migraciones y seeders:

    ```
    php artisan migrate --no-interaction
    php artisan db:seed --class=UserSeeder --no-interaction
    ```

    (Ver `database/seeders/UserSeeder.php` para usuario admin inicial.)

4. Dependencias front:
    ```
    npm install
    npm run dev   # o npm run build para producción
    ```
    Si no ves cambios del frontend, ejecutar `composer run dev` o `npm run build`.

## Integración de roles y permisos (recomendado)

Recomendación: usar spatie/laravel-permission.

Instalación (ejemplo técnico):

```
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --no-interaction
php artisan migrate --no-interaction
```

Pasos técnicos:

-   Añadir trait HasRoles al modelo User:

    ```php
    use Spatie\Permission\Traits\HasRoles;

    class User extends Authenticatable
    {
        use HasRoles;
        // protected $guard_name = 'web'; // opcional
    }
    ```

-   Crear seeder de roles/permissions (ej. database/seeders/PermissionSeeder.php) y ejecutar.
-   Crear Policies para modelos (php artisan make:policy UserPolicy --model=User) y registrarlas en AuthServiceProvider.
-   En Filament Resource controlar visibilidad/authorize usando policies o closures:
    -   Implementar `public static function canViewAny(?User $user): bool` o usar `->visible()` en acciones: `EditAction::make()->visible(fn() => auth()->user()?->can('edit users') ?? false)`.

## Filament — puntos prácticos

-   Resources: definen `form(Schema $schema)`, `table(Table $table)`, `infolist(Schema $schema)` y `getPages()`. Reutilizar FormRequests para reglas:
    ```php
    $rules = app(\App\Http\Requests\StoreUserRequest::class)->rules();
    TextInput::make('email')->rules($rules['email'] ?? []);
    ```
-   Actions: todas las acciones extienden Filament\Actions\Action. Para lógica compleja, delegar a un Controller o Service.
-   Panels: revisar `app/Providers/Filament/AdminPanelProvider.php` para middleware y discovery de resources/pages/widgets.
-   Visibilidad en navegación: `protected static ?string $navigationIcon`, `protected static ?int $navigationSort` y `public static function getNavigationGroup()`; controlar con policies si el resource debe mostrarse.

## Validación y FormRequests

-   Crear FormRequests para validación y mensajes personalizados:
    ```
    php artisan make:request StoreUserRequest --no-interaction
    ```
-   Reutilizar reglas en Filament Resources y controllers para mantener DRY.
-   Para updates, crear UpdateUserRequest con regla unique ignorando el id actual.

## API Resources y Controllers

-   Para endpoints REST: usar Controllers + FormRequests + Eloquent API Resources.
    ```
    php artisan make:controller Api/UserController --api --no-interaction
    php artisan make:resource UserResource --no-interaction
    ```
-   Usar paginación y Resource collections para respuestas consistentes:
    ```php
    return UserResource::collection(User::query()->paginate());
    ```

## Pruebas (Pest)

-   Crear tests con Pest:
    ```
    php artisan make:test UserResourceTest --pest
    ```
-   Filament testing tips:
    -   Autenticar antes de usar Livewire tests.
    -   Usar Livewire::test(...) o livewire(PageClass::class).
    -   Ejemplo mínimo (Pest):
        ```php
        livewire(CreateUser::class)
          ->fillForm(['name' => 'Prueba', 'email' => 't@e.com'])
          ->call('create')
          ->assertNotified()
          ->assertRedirect();
        ```
-   Ejecutar subset de tests durante el desarrollo:
    ```
    php artisan test --filter=UserResourceTest
    ```

## Comandos útiles

-   Composer / Artisan:
    -   composer install
    -   php artisan migrate --no-interaction
    -   php artisan db:seed --no-interaction
    -   php artisan route:list
    -   php artisan config:cache
-   Front:
    -   npm run dev
    -   npm run build
-   Formato:
    -   vendor/bin/pint --dirty

## Buenas prácticas y seguridad

-   Nunca commitear .env. Mantener variables sensibles en entorno.
-   Asegurar APP_KEY y usar HTTPS en producción.
-   Hashear contraseñas con Hash::make (Eloquent mutators o FormRequests).
-   Habilitar CSP, X-Frame-Options, HSTS en servidor.
-   Limitar acceso al panel Filament con middleware auth y roles; usar policies para control fino.
-   Logs: revisar storage/logs/laravel.log y configurar alertas para errores críticos.

## Despliegue recomendado (resumen técnico)

-   Pipeline CI: composer install --no-dev, npm ci && npm run build, php artisan migrate --force, php artisan config:cache, php artisan route:cache, php artisan view:cache.
-   Backups: base de datos y storage (S3 / offsite).
-   Workers: configurar queue workers supervisord / systemd para jobs.
-   Scheduler: registrar cron `php artisan schedule:run` cada minuto.

## Extensiones comunes / próximos pasos

-   Integrar spatie/laravel-permission (roles/permissions) y sincronizar con Filament Resources/Policies.
-   Añadir auditoría (spatie/laravel-activitylog) para trazabilidad de acciones clínico-administrativas.
-   Internacionalización i18n (es) para la UI Filament.
-   Crear tests de integración para flujos clínicos (creación paciente, cita, historia).

## Referencias internas útiles

-   Archivo panel Filament: `app/Providers/Filament/AdminPanelProvider.php`
-   Recursos Filament de usuarios: `app/Filament/Resources/Users/UserResource.php`
-   Seeder usuario inicial: `database/seeders/UserSeeder.php`

## Contacto y contribución

-   Seguir convenciones del repo y Laravel Boost guidelines.
-   Antes de proponer cambios estructurales (nuevas carpetas o dependencias), abrir issue y discutir.
-   Formatear con Pint antes de push: `vendor/bin/pint --dirty`.

## Apéndice — snippet: seeder de permisos (ejemplo técnico)

```php
// Ejemplo: database/seeders/PermissionSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $perms = ['view users', 'create users', 'edit users', 'delete users'];
        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($perms);

        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->syncPermissions(['view users']);
    }
}
```

## Notas finales

Este README está orientado a un público técnico (desarrolladores/DevOps). Si desea, puedo:

-   Generar seeders/policies/requests y aplicar integración con Filament (crear archivos en el repo).
-   Añadir ejemplos de tests Pest concretos para UserResource.
-   Proveer un checklist de despliegue CI/CD adaptado a su infraestructura.
