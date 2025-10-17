# Para Ti, Mi Poesía - Santuario Digital de Versos

Un santuario digital donde los versos florecen y las emociones encuentran su voz. Una plataforma moderna y elegante para explorar poemas de amor, naturaleza y melancolía.

## ✨ Características

### 🎨 Diseño Moderno
- **Paleta de colores elegante**: Verde oliva, rosa pastel y amarillo pastel
- **Tipografías refinadas**: Playfair Display para títulos y Lora para texto
- **Efectos visuales**: Backdrop blur, sombras suaves, animaciones flotantes
- **Modo oscuro**: Soporte completo con transiciones suaves
- **Diseño responsivo**: Adaptable a todos los dispositivos

### 🎬 Animaciones Dinámicas
- **GSAP**: Animaciones fluidas y profesionales
- **Three.js**: Efectos 3D y partículas flotantes
- **Transiciones suaves**: Entre páginas y elementos
- **Efectos hover**: Interactividad mejorada
- **Scroll parallax**: Efectos de profundidad

### 📱 Tres Vistas de Lectura

#### 📖 Vista de Libro
- Experiencia inmersiva como un libro real
- Efectos 3D de volteo de páginas
- Diseño de doble página
- Controles de personalización

#### 🎴 Vista de Tarjetas
- Galería elegante de poemas
- Efectos de hover suaves
- Filtros y búsqueda
- Paginación intuitiva

#### 📋 Vista de Lista
- Scroll infinito
- Lista detallada de poemas
- Información completa
- Navegación fluida

### 🛠️ Tecnologías

- **HTML5**: Estructura semántica
- **CSS3**: Estilos modernos con variables CSS
- **JavaScript ES6+**: Funcionalidad dinámica
- **Tailwind CSS**: Framework de utilidades
- **GSAP**: Animaciones avanzadas
- **Three.js**: Gráficos 3D
- **Material Icons**: Iconografía consistente

## 🚀 Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/tu-usuario/plataforma-poemas.git
   cd plataforma-poemas
   ```

2. **Configurar servidor local**
   ```bash
   # Usando PHP (recomendado)
   php -S localhost:8000
   
   # O usando Python
   python -m http.server 8000
   
   # O usando Node.js
   npx serve .
   ```

3. **Abrir en el navegador**
   ```
   http://localhost:8000
   ```

## 📁 Estructura del Proyecto

```
plataformaPoemas/
├── 📄 index.html                 # Página principal
├── 📄 libro-tradicional.html     # Vista de libro
├── 📄 lista-scroll.html          # Vista de lista
├── 📄 tarjetas-poemas.html       # Vista de tarjetas
├── 📁 src/                       # Código fuente
│   ├── 📁 components/            # Componentes reutilizables
│   ├── 📁 config/                # Configuración
│   ├── 📁 pages/                 # Páginas específicas
│   ├── 📁 services/              # Servicios
│   ├── 📁 styles/                # Estilos CSS
│   └── 📄 app.js                 # Aplicación principal
├── 📁 api/                       # API del backend
├── 📁 admin/                     # Panel de administración
├── 📁 backup_frontend_*/         # Respaldo del frontend anterior
└── 📄 README.md                  # Este archivo
```

## 🎯 Uso

### Navegación Principal
- **Inicio**: Página principal con tres opciones de vista
- **Vista de Libro**: Experiencia inmersiva de lectura
- **Vista de Tarjetas**: Galería elegante de poemas
- **Vista de Lista**: Lista detallada con scroll infinito

### Controles
- **Modo oscuro**: Botón en la barra de herramientas
- **Filtros**: Por categoría, autor, fecha
- **Búsqueda**: Título, autor o contenido
- **Personalización**: Tamaño de fuente, tipo de letra

### Efectos Interactivos
- **Hover**: Efectos suaves en tarjetas y botones
- **Scroll**: Animaciones de entrada y parallax
- **Partículas**: Efectos de fondo dinámicos
- **Transiciones**: Cambios suaves entre estados

## ⚙️ Configuración

### Variables de Entorno
```javascript
// src/config/app-config.js
window.AppConfig = {
    debug: true,                    // Modo debug
    environment: 'development',     // Entorno
    animations: { enabled: true },  // Animaciones
    threejs: { enabled: true },     // Three.js
    // ... más configuraciones
};
```

### Personalización de Colores
```css
:root {
    --color-primary: #6B7A40;      /* Verde oliva */
    --color-secondary: #FADADD;     /* Rosa pastel */
    --color-accent: #FFFACD;        /* Amarillo pastel */
    /* ... más colores */
}
```

### Configuración de Animaciones
```javascript
// GSAP
gsap.from(".element", {
    opacity: 0,
    y: 20,
    duration: 0.8,
    ease: "power2.out"
});

// Three.js
const particles = new THREE.Points(geometry, material);
scene.add(particles);
```

## 🎨 Personalización

### Temas
- **Claro**: Colores suaves y elegantes
- **Oscuro**: Modo nocturno con contraste optimizado
- **Automático**: Detecta preferencias del sistema

### Animaciones
- **Habilitadas**: Efectos completos
- **Reducidas**: Para usuarios sensibles al movimiento
- **Deshabilitadas**: Sin efectos de animación

### Efectos
- **Partículas**: Efectos de fondo dinámicos
- **Corazones flotantes**: Elementos románticos
- **Brillo romántico**: Efectos de luz suave
- **Glassmorphism**: Efectos de cristal

## 📱 Responsive Design

### Breakpoints
- **XS**: < 480px (Móviles pequeños)
- **SM**: 640px (Móviles)
- **MD**: 768px (Tablets)
- **LG**: 1024px (Laptops)
- **XL**: 1280px (Desktop)
- **2XL**: 1536px (Pantallas grandes)

### Adaptaciones
- **Móviles**: Efectos reducidos, navegación simplificada
- **Tablets**: Diseño híbrido, controles táctiles
- **Desktop**: Efectos completos, interacciones avanzadas

## 🔧 Desarrollo

### Estructura de Componentes
```javascript
class Component {
    constructor(options) {
        this.options = options;
        this.init();
    }
    
    init() {
        // Inicialización
    }
    
    destroy() {
        // Limpieza
    }
}
```

### Servicios
- **ThemeService**: Gestión de temas
- **RouterService**: Navegación
- **StateService**: Estado de la aplicación
- **StorageService**: Almacenamiento local

### Páginas
- **HomePage**: Página principal
- **BasePage**: Página base con funcionalidad común

## 🚀 Despliegue

### Producción
1. **Optimizar recursos**
   ```bash
   # Minificar CSS y JS
   npm run build
   ```

2. **Configurar servidor**
   ```nginx
   # Nginx
   server {
       listen 80;
       server_name tu-dominio.com;
       root /path/to/plataforma-poemas;
       index index.html;
   }
   ```

3. **Habilitar compresión**
   ```nginx
   gzip on;
   gzip_types text/css application/javascript;
   ```

### CDN
- **Tailwind CSS**: CDN oficial
- **GSAP**: CDN de Cloudflare
- **Three.js**: CDN oficial
- **Material Icons**: Google Fonts

## 📊 Performance

### Optimizaciones
- **Lazy Loading**: Carga diferida de imágenes
- **Code Splitting**: División de código
- **Caching**: Almacenamiento en caché
- **Compresión**: Gzip/Brotli

### Métricas
- **Lighthouse Score**: 90+
- **First Contentful Paint**: < 1.5s
- **Largest Contentful Paint**: < 2.5s
- **Cumulative Layout Shift**: < 0.1

## 🐛 Solución de Problemas

### Problemas Comunes
1. **Animaciones no funcionan**
   - Verificar que GSAP esté cargado
   - Comprobar configuración de animaciones

2. **Efectos 3D no aparecen**
   - Verificar que Three.js esté cargado
   - Comprobar configuración de Three.js

3. **Modo oscuro no funciona**
   - Verificar configuración de temas
   - Comprobar localStorage

### Debug
```javascript
// Habilitar modo debug
window.AppConfig.debug = true;

// Ver información de la aplicación
console.log(window.app.getAppInfo());
```

## 🤝 Contribución

1. **Fork** el proyecto
2. **Crear** una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. **Push** a la rama (`git push origin feature/AmazingFeature`)
5. **Abrir** un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👥 Autores

- **David García** - *Desarrollo inicial* - [@tu-usuario](https://github.com/tu-usuario)

## 🙏 Agradecimientos

- **Tailwind CSS** - Framework de utilidades
- **GSAP** - Animaciones avanzadas
- **Three.js** - Gráficos 3D
- **Material Icons** - Iconografía
- **Google Fonts** - Tipografías

## 📞 Contacto

- **Proyecto**: [Para Ti, Mi Poesía](https://github.com/tu-usuario/plataforma-poemas)
- **Email**: tu-email@ejemplo.com
- **Twitter**: [@tu-usuario](https://twitter.com/tu-usuario)

---

**Para Ti, Mi Poesía** - Donde cada verso es un susurro de amor, cada palabra un latido del corazón 💕