# 🎨 Sistema de Temas Dinámicos

## Descripción

El Sistema de Temas Dinámicos permite cambiar los colores y estilos de la aplicación en tiempo real, proporcionando una experiencia visual personalizable para los usuarios. El sistema está diseñado para ser fácil de usar y extensible.

## Características Principales

### ✨ Temas Predefinidos
- **Naturaleza** (por defecto): Verde, rosa y amarillo
- **Océano**: Azules y verdes oceánicos
- **Atardecer**: Naranjas y rojos cálidos
- **Nocturno**: Grises y azules oscuros
- **Primavera**: Verdes y rosas frescos

### 🔧 Funcionalidades
- Cambio de tema en tiempo real
- Persistencia de preferencias en localStorage
- Transiciones suaves entre temas
- Selector de temas compacto y completo
- Navegación entre temas (anterior/siguiente)
- Tema aleatorio
- Reset al tema original

## Estructura del Sistema

### Archivos Principales

```
src/
├── services/
│   └── ThemeService.js          # Servicio principal de gestión de temas
├── components/
│   └── ThemeSelectorComponent.js # Componente selector de temas
└── styles/
    └── main.css                 # Estilos con variables CSS dinámicas
```

### Archivos de Demostración

```
├── temas-demo.html              # Página de demostración completa
└── index.html                   # Página principal con selector compacto
```

## Uso del Sistema

### 1. Inicialización Básica

```javascript
// El servicio se inicializa automáticamente
const themeService = window.themeService;

// Aplicar un tema específico
themeService.applyTheme('ocean');

// Obtener tema actual
const currentTheme = themeService.getCurrentTheme();
```

### 2. Uso del Componente Selector

```javascript
// Selector compacto
const themeSelector = new ThemeSelectorComponent('theme-selector', {
    compact: true,
    showDescription: false
});

// Selector completo
const themeSelectorFull = new ThemeSelectorComponent('theme-selector-full', {
    compact: false,
    showDescription: true
});
```

### 3. Navegación entre Temas

```javascript
// Tema siguiente
themeService.nextTheme();

// Tema anterior
themeService.previousTheme();

// Tema aleatorio
themeService.randomTheme();

// Reset al tema original
themeService.applyTheme('default');
```

## Personalización

### Añadir Nuevos Temas

Para añadir un nuevo tema, modifica el método `initializeThemes()` en `ThemeService.js`:

```javascript
miTema: {
    name: 'Mi Tema',
    description: 'Descripción de mi tema personalizado',
    colors: {
        primary: {
            main: '#COLOR_PRINCIPAL',
            light: '#COLOR_CLARO',
            dark: '#COLOR_OSCURO',
            veryLight: '#COLOR_MUY_CLARO'
        },
        secondary: {
            main: '#COLOR_SECUNDARIO',
            light: '#COLOR_SECUNDARIO_CLARO',
            dark: '#COLOR_SECUNDARIO_OSCURO',
            veryLight: '#COLOR_SECUNDARIO_MUY_CLARO'
        },
        accent: {
            light: '#COLOR_ACENTO_CLARO',
            medium: '#COLOR_ACENTO_MEDIO',
            gold: '#COLOR_ACENTO_DORADO',
            intense: '#COLOR_ACENTO_INTENSO'
        }
    },
    fonts: {
        primary: 'Fuente Principal',
        heading: 'Fuente Títulos'
    }
}
```

### Personalizar Estilos

Los estilos se definen usando variables CSS que se actualizan dinámicamente:

```css
/* Variables que cambian con el tema */
:root {
    --color-verde-principal: #636B2F;
    --color-verde-claro: #8B9A4A;
    --color-verde-oscuro: #4A5220;
    --color-verde-muy-claro: #F0F4E8;
    /* ... más variables */
}

/* Uso en estilos */
.mi-elemento {
    background-color: var(--color-verde-principal);
    color: var(--color-verde-oscuro);
    transition: var(--theme-transition);
}
```

## Eventos del Sistema

### Eventos Disponibles

```javascript
// Escuchar cambios de tema
document.addEventListener('theme-changed', (e) => {
    console.log('Tema cambiado:', e.detail.theme);
    console.log('Datos del tema:', e.detail.data);
});

// Solicitar cambio de tema
document.dispatchEvent(new CustomEvent('theme-change', {
    detail: { theme: 'ocean' }
}));
```

## API del ThemeService

### Métodos Principales

```javascript
// Aplicar tema
themeService.applyTheme(themeName)

// Obtener tema actual
themeService.getCurrentTheme()

// Obtener todos los temas
themeService.getAvailableThemes()

// Navegación
themeService.nextTheme()
themeService.previousTheme()
themeService.randomTheme()

// Información del servicio
themeService.getInfo()
```

## Demostración

Para ver el sistema en acción:

1. Abre `temas-demo.html` en tu navegador
2. Usa el selector de temas en la esquina superior izquierda
3. Explora los diferentes temas disponibles
4. Observa cómo cambian los colores en tiempo real
5. Prueba los controles de navegación entre temas

## Integración con Componentes Existentes

El sistema está diseñado para integrarse fácilmente con los componentes existentes:

### NavbarComponent
- Incluye enlace a la demo de temas
- Usa clases de tema dinámico

### FooterComponent
- Incluye enlace a la demo de temas
- Se adapta automáticamente a los cambios de tema

### Otros Componentes
- Usar variables CSS para colores
- Añadir transiciones con `var(--theme-transition)`
- Usar clases de tema como `card-theme`, `btn-primary-theme`

## Consideraciones Técnicas

### Rendimiento
- Los cambios de tema son instantáneos
- Las transiciones son suaves y no afectan el rendimiento
- El localStorage se usa para persistir preferencias

### Compatibilidad
- Compatible con todos los navegadores modernos
- Funciona con Tailwind CSS
- No requiere dependencias externas

### Accesibilidad
- Soporte para navegación con teclado
- Indicadores visuales claros
- Transiciones que respetan las preferencias del usuario

## Futuras Mejoras

- [ ] Temas personalizados por usuario
- [ ] Editor visual de temas
- [ ] Temas basados en la hora del día
- [ ] Sincronización de temas entre dispositivos
- [ ] Temas con animaciones personalizadas

## Soporte

Para cualquier duda o problema con el sistema de temas:

1. Revisa la consola del navegador para errores
2. Verifica que todos los archivos estén cargados correctamente
3. Comprueba que el localStorage esté habilitado
4. Consulta la documentación de la API

---

**Versión**: 1.0.0  
**Última actualización**: Enero 2024  
**Autor**: Sistema de Poemas Dinámico
