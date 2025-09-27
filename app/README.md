# Lazarus - Salud Comunitaria Inteligente

Una plataforma completa para la gestión de salud comunitaria, diseñada con una arquitectura que "grita" su propósito a través de la organización del código.

## 🎯 Visión

Lazarus es una aplicación web que conecta, empodera y transforma comunidades mediante herramientas inteligentes para el bienestar colectivo. Construye un futuro más saludable juntos.

## 🏗️ Arquitectura

Este proyecto implementa la **Arquitectura que Grita** (Screaming Architecture) propuesta por Robert C. Martin, donde la estructura del proyecto revela claramente el propósito del sistema a través de la organización por características en lugar de capas técnicas.

### Estructura del Proyecto

```
src/
├── features/                    # Características principales
│   ├── auth/                    # Autenticación
│   │   ├── components/          # Componentes específicos de auth
│   │   │   ├── forms/          # Formularios de login/registro
│   │   │   └── section/        # Secciones de páginas de auth
│   │   ├── pages/              # Páginas de auth (login, forgot-password)
│   │   │   └── api/            # Endpoints API de auth
│   ├── home/                    # Página principal
│   │   ├── components/
│   │   │   ├── home/           # Componentes de la landing page
│   │   │   └── section/        # Sección Home
│   ├── dashboard/               # Panel de usuario
│   │   ├── components/dashboard/ # Componentes del dashboard
│   │   ├── layouts/            # Layout específico del dashboard
│   │   └── pages/              # Página del dashboard
├── shared/                      # Recursos compartidos
│   ├── components/
│   │   ├── ui/                 # Componentes de UI reutilizables
│   │   ├── icons/              # Iconos
│   │   ├── Header.astro        # Header global
│   │   └── Footer.astro        # Footer global
│   ├── layouts/                 # Layouts compartidos
│   ├── lib/                     # Utilidades y helpers
│   ├── styles/                  # Estilos globales
│   └── assets/                  # Imágenes y recursos estáticos
├── pages/                       # Rutas de Astro
│   ├── index.astro             # Página principal (/)
│   ├── auth/                   # Rutas de auth (/auth/*)
│   └── dashboard/              # Ruta del dashboard (/dashboard)
└── middleware.ts               # Middleware de Astro
```

### Principios de la Arquitectura

- **Organización por Características**: Cada carpeta principal representa una funcionalidad del negocio
- **Separación de Responsabilidades**: Componentes compartidos vs. específicos por feature
- **Escalabilidad**: Fácil agregar nuevas características sin afectar otras
- **Mantenibilidad**: Código organizado y fácil de encontrar

## 🛠️ Tecnologías

### Framework y Lenguajes

- **Astro**: Framework web moderno para contenido-driven websites
- **React**: Para componentes interactivos
- **TypeScript**: Tipado estático para mayor robustez

### UI y Estilos

- **Tailwind CSS**: Framework CSS utility-first
- **Radix UI**: Componentes primitivos accesibles
- **Lucide React**: Iconos modernos
- **Tailwind Animate**: Animaciones CSS

### Funcionalidades

- **Astro Sessions**: Gestión de sesiones del lado del servidor
- **Sonner**: Notificaciones toast
- **Next Themes**: Soporte para temas (modo oscuro/claro)

## 🚀 Inicio Rápido

### Prerrequisitos

- Node.js 18+
- npm o yarn

### Instalación

1. Clona el repositorio:

```bash
git clone <repository-url>
cd salud-comunitaria
```

2. Instala las dependencias:

```bash
npm install
```

3. Inicia el servidor de desarrollo:

```bash
npm run dev
```

4. Abre [http://localhost:4321](http://localhost:4321) en tu navegador

### Comandos Disponibles

| Comando               | Descripción                             |
| --------------------- | --------------------------------------- |
| `npm run dev`         | Inicia el servidor de desarrollo        |
| `npm run build`       | Construye la aplicación para producción |
| `npm run preview`     | Vista previa de la build de producción  |
| `npm run astro check` | Verifica tipos y errores                |

## 📁 Características

### 🔐 Autenticación

- Login con email/contraseña
- Recuperación de contraseña
- Sesiones seguras
- Protección de rutas

### 🏠 Página Principal

- Hero section con llamada a la acción
- Estadísticas de impacto comunitario
- Características destacadas
- Diseño responsive

### 📊 Dashboard

- Panel de control personalizado
- Gestión de programas de salud
- Analytics y métricas
- Interfaz intuitiva

## 🎨 Diseño

La aplicación utiliza un sistema de diseño moderno con:

- **Colores**: Paleta cálida enfocada en confianza y salud
- **Tipografía**: Fuentes legibles y accesibles
- **Componentes**: Diseño consistente con Radix UI
- **Responsive**: Optimizado para móvil y desktop

## 🔧 Configuración

### Variables de Entorno

Crea un archivo `.env` en la raíz del proyecto:

```env
PUBLIC_API_URL=https://api.lazarus.com
```

### API Backend

La aplicación espera una API REST con los siguientes endpoints:

- `POST /auth/login` - Autenticación
- `POST /auth/forgot-password` - Recuperación de contraseña
- `GET /auth/logout` - Cierre de sesión

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.

## 📞 Contacto

Proyecto desarrollado para la transformación digital de la salud comunitaria.

---

_Construido con ❤️ para comunidades saludables_
