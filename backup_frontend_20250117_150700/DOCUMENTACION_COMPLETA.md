# 📚 Documentación Completa - Sistema de Poemas Dinámico

## 🎯 Resumen del Proyecto

Este proyecto es un **sistema completo de gestión de poemas** que combina un **frontend interactivo** con múltiples formas de lectura y un **panel administrativo dinámico** para gestionar el contenido. El sistema mantiene el diseño original del frontend mientras añade funcionalidad completa de gestión de contenido.

---

## 🏗️ Arquitectura del Sistema

### **Frontend (Cliente)**
- **Tecnologías**: HTML5, CSS3, JavaScript ES6+, Tailwind CSS
- **Patrón**: MVC (Model-View-Controller) con componentes modulares
- **Características**: 
  - 3 vistas diferentes de lectura (Tarjetas, Lista, Libro)
  - Diseño responsivo y accesible
  - Animaciones y transiciones suaves
  - Consumo de datos dinámicos desde API

### **Backend (Servidor)**
- **Tecnologías**: PHP 7.4+, SQLite3
- **Patrón**: API REST con arquitectura de capas
- **Características**:
  - CRUD completo para poemas, autores, categorías y etiquetas
  - Base de datos SQLite ligera
  - Panel administrativo web
  - Sistema de migración de datos

### **Base de Datos**
- **Motor**: SQLite3
- **Estructura**: 5 tablas relacionadas
- **Características**: Ligera, portable, sin configuración compleja

---

## 📁 Estructura del Proyecto

```
album/
├── 📄 Archivos HTML principales
│   ├── index.html                 # Página principal con selector de vistas
│   ├── tarjetas-poemas.html       # Vista de tarjetas interactivas
│   ├── lista-scroll.html          # Vista de lista con scroll
│   └── libro-tradicional.html     # Vista de libro tradicional
│
├── 📁 src/                        # Código fuente del frontend
│   ├── 📁 components/             # Componentes reutilizables
│   │   ├── BaseComponent.js       # Clase base para componentes
│   │   ├── NavbarComponent.js     # Barra de navegación
│   │   └── FooterComponent.js     # Pie de página
│   │
│   ├── 📁 config/                 # Configuraciones
│   │   ├── app-config.js          # Configuración principal
│   │   └── poems-config.js        # Configuración específica de poemas
│   │
│   ├── 📁 data/                   # Gestión de datos
│   │   ├── poems-data.js          # Datos estáticos (fallback)
│   │   └── api-data.js            # Servicio de datos dinámicos
│   │
│   ├── 📁 pages/                  # Páginas/vistas
│   │   ├── BasePage.js            # Clase base para páginas
│   │   ├── HomePage.js            # Página principal
│   │   ├── TarjetasPage.js        # Página de tarjetas
│   │   ├── ListaPage.js           # Página de lista
│   │   └── LibroPage.js           # Página de libro
│   │
│   ├── 📁 services/               # Servicios
│   │   ├── RouterService.js       # Gestión de rutas
│   │   ├── StateService.js        # Gestión de estado
│   │   ├── StorageService.js      # Almacenamiento local
│   │   └── TemplateService.js     # Gestión de plantillas
│   │
│   ├── 📁 styles/                 # Estilos
│   │   └── main.css               # Estilos principales
│   │
│   └── app.js                     # Aplicación principal
│
├── 📁 admin/                      # Panel administrativo
│   ├── 📄 index.php               # Panel principal (estado del sistema)
│   ├── 📄 panel.php               # Panel de gestión de contenido
│   ├── 📄 database.php            # Configuración de base de datos
│   ├── 📄 migrate.php             # Script de migración
│   ├── 📄 config.php              # Configuración del sistema
│   │
│   ├── 📁 api/                    # API REST
│   │   ├── poemas.php             # CRUD de poemas
│   │   ├── autores.php            # CRUD de autores
│   │   ├── categorias.php         # CRUD de categorías
│   │   ├── etiquetas.php          # CRUD de etiquetas
│   │   └── test.php               # API de prueba
│   │
│   ├── 📁 js/                     # JavaScript del panel
│   │   └── admin.js               # Funcionalidad del panel
│   │
│   └── 📄 .htaccess               # Configuración Apache
│
├── 📁 data/                       # Base de datos
│   └── poemas.db                  # Base de datos SQLite
│
├── 📁 templates/                  # Plantillas HTML
│   ├── navbar.html                # Plantilla de navegación
│   ├── footer.html                # Plantilla de pie
│   └── filters.html               # Plantilla de filtros
│
├── 📁 node_modules/               # Dependencias Node.js
├── 📄 package.json                # Configuración del proyecto
├── 📄 vite.config.js              # Configuración de Vite
├── 📄 postcss.config.js           # Configuración PostCSS
└── 📄 README.md                   # Documentación básica
```

---

## 🎨 Diseño y Estilos

### **Paleta de Colores**

```css
/* Verde Principal (Color principal) */
--color-verde-principal: #636B2F;
--color-verde-claro: #8B9A4A;
--color-verde-oscuro: #4A5220;
--color-verde-muy-claro: #F0F4E8;

/* Rosa Complementario */
--color-rosa-principal: #E89EB8;
--color-rosa-claro: #F2C4D1;
--color-rosa-oscuro: #D67A9A;
--color-rosa-muy-claro: #FDF2F8;

/* Amarillos/Cremas */
--color-amarillo-claro: #FDF4E3;
--color-amarillo-medio: #F9E6B3;
--color-amarillo-dorado: #F4D03F;
--color-amarillo-intenso: #D4AC0D;
```

### **Tipografías**

- **Títulos**: Playfair Display (serif, elegante)
- **Texto**: Crimson Text (serif, legible)
- **Sistema**: Fuentes del sistema para UI

### **Componentes de Diseño**

#### **1. Tarjetas de Poemas**
- **Efecto flip**: Animación 3D para mostrar contenido
- **Colores por categoría**: Verde (naturaleza), Rosa (amor), Amarillo (reflexión)
- **Interactividad**: Hover, click para voltear, botones de acción

#### **2. Navegación**
- **Navbar fija**: Sticky header con navegación principal
- **Footer**: Enlaces a todas las vistas y información del sitio

#### **3. Responsive Design**
- **Mobile First**: Diseño optimizado para móviles
- **Breakpoints**: 768px (tablet), 480px (móvil)
- **Grid adaptativo**: Columnas que se ajustan según el tamaño

---

## 🚀 Instalación y Configuración

### **Requisitos del Sistema**

#### **Servidor Web**
- **PHP**: 7.4 o superior
- **Extensiones PHP**:
  - `pdo_sqlite` (obligatorio)
  - `sqlite3` (obligatorio)
  - `json` (obligatorio)
- **Servidor**: Apache, Nginx o servidor con soporte PHP

#### **Cliente (Desarrollo)**
- **Node.js**: 16 o superior
- **NPM**: Para gestión de dependencias
- **Navegador**: Chrome, Firefox, Safari (versiones recientes)

### **Pasos de Instalación**

#### **1. Configuración del Servidor**

```bash
# 1. Clonar o descargar el proyecto
# 2. Configurar el servidor web para que apunte al directorio del proyecto
# 3. Asegurar permisos de escritura en el directorio data/

# Ejemplo para Apache (Laragon)
# El proyecto debe estar en: C:\laragon\www\album\
```

#### **2. Configuración de PHP**

```bash
# Verificar extensiones PHP
php -m | grep -i sqlite

# Debe mostrar:
# pdo_sqlite
# sqlite3
```

#### **3. Instalación de Dependencias (Opcional para desarrollo)**

```bash
# Instalar dependencias Node.js
npm install

# Ejecutar servidor de desarrollo
npm run dev

# Construir para producción
npm run build
```

#### **4. Configuración de la Base de Datos**

```bash
# Ejecutar migración inicial
cd admin
php migrate.php

# Esto creará:
# - Directorio data/ (si no existe)
# - Base de datos poemas.db
# - Tablas necesarias
# - Datos de ejemplo
```

#### **5. Verificación de la Instalación**

```bash
# 1. Acceder al panel administrativo
http://tu-dominio/admin/

# 2. Verificar que todo esté en verde (✅)
# 3. Acceder al panel de gestión
http://tu-dominio/admin/panel.php

# 4. Verificar el sitio web
http://tu-dominio/
```

---

## 🎮 Funcionalidades del Sistema

### **Frontend - Experiencias de Lectura**

#### **1. Vista de Tarjetas (`tarjetas-poemas.html`)**
- **Características**:
  - Tarjetas interactivas con efecto flip 3D
  - Filtros por categoría
  - Animaciones de entrada
  - Botones de compartir
  - Vista compacta/expandida

- **Interactividad**:
  - Click para voltear tarjeta
  - Hover effects
  - Filtros dinámicos
  - Mezcla aleatoria

#### **2. Vista de Lista (`lista-scroll.html`)**
- **Características**:
  - Scroll suave y fluido
  - Animaciones de entrada
  - Filtros por categoría
  - Búsqueda en tiempo real

- **Interactividad**:
  - Scroll infinito
  - Animaciones parallax
  - Filtros dinámicos

#### **3. Vista de Libro (`libro-tradicional.html`)**
- **Características**:
  - Simulación de libro físico
  - Volteo de páginas
  - Navegación por capítulos
  - Controles de lectura

- **Interactividad**:
  - Click para voltear páginas
  - Navegación por teclado
  - Marcador de progreso

### **Backend - Panel Administrativo**

#### **1. Dashboard Principal (`admin/index.php`)**
- **Estado del Sistema**:
  - Versión de PHP
  - Extensiones disponibles
  - Estado de la base de datos
  - Permisos de archivos

#### **2. Gestión de Contenido (`admin/panel.php`)**
- **Funcionalidades**:
  - ✅ Ver todos los poemas
  - ✅ Crear nuevos poemas
  - ✅ Editar poemas existentes
  - ✅ Eliminar poemas
  - ✅ Gestión de autores
  - ✅ Gestión de categorías
  - ✅ Gestión de etiquetas

#### **3. API REST (`admin/api/`)**
- **Endpoints Disponibles**:

```http
# Poemas
GET    /admin/api/poemas.php           # Listar todos
GET    /admin/api/poemas.php/{id}      # Obtener uno
POST   /admin/api/poemas.php           # Crear nuevo
PUT    /admin/api/poemas.php/{id}      # Actualizar
DELETE /admin/api/poemas.php/{id}      # Eliminar

# Autores
GET    /admin/api/autores.php          # Listar todos
GET    /admin/api/autores.php/{id}     # Obtener uno
POST   /admin/api/autores.php          # Crear nuevo
PUT    /admin/api/autores.php/{id}     # Actualizar
DELETE /admin/api/autores.php/{id}     # Eliminar

# Categorías
GET    /admin/api/categorias.php       # Listar todas
GET    /admin/api/categorias.php/{id}  # Obtener una
POST   /admin/api/categorias.php       # Crear nueva
PUT    /admin/api/categorias.php/{id}  # Actualizar
DELETE /admin/api/categorias.php/{id}  # Eliminar

# Etiquetas
GET    /admin/api/etiquetas.php        # Listar todas
GET    /admin/api/etiquetas.php/{id}   # Obtener una
POST   /admin/api/etiquetas.php        # Crear nueva
PUT    /admin/api/etiquetas.php/{id}   # Actualizar
DELETE /admin/api/etiquetas.php/{id}   # Eliminar
```

---

## 🗄️ Base de Datos

### **Estructura de Tablas**

#### **1. Tabla `autores`**
```sql
CREATE TABLE autores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    biografia TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

#### **2. Tabla `categorias`**
```sql
CREATE TABLE categorias (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    icono VARCHAR(50),
    color VARCHAR(20),
    descripcion TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

#### **3. Tabla `poemas`**
```sql
CREATE TABLE poemas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo VARCHAR(200) NOT NULL,
    autor_id INTEGER NOT NULL,
    categoria_id INTEGER NOT NULL,
    icono VARCHAR(50),
    extracto TEXT,
    contenido TEXT NOT NULL,
    tiempo_lectura INTEGER DEFAULT 2,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES autores(id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);
```

#### **4. Tabla `etiquetas`**
```sql
CREATE TABLE etiquetas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

#### **5. Tabla `poema_etiquetas` (Relación muchos a muchos)**
```sql
CREATE TABLE poema_etiquetas (
    poema_id INTEGER,
    etiqueta_id INTEGER,
    PRIMARY KEY (poema_id, etiqueta_id),
    FOREIGN KEY (poema_id) REFERENCES poemas(id) ON DELETE CASCADE,
    FOREIGN KEY (etiqueta_id) REFERENCES etiquetas(id) ON DELETE CASCADE
);
```

---

## 🔧 Configuración Técnica

### **Configuración de Tailwind CSS**

```javascript
// Configuración personalizada en cada archivo HTML
tailwind.config = {
    theme: {
        extend: {
            colors: {
                'verde': {
                    'principal': '#636B2F',
                    'claro': '#8B9A4A',
                    'oscuro': '#4A5220',
                    'muy-claro': '#F0F4E8',
                },
                'rosa': {
                    'principal': '#E89EB8',
                    'claro': '#F2C4D1',
                    'oscuro': '#D67A9A',
                    'muy-claro': '#FDF2F8',
                },
                'amarillo': {
                    'claro': '#FDF4E3',
                    'medio': '#F9E6B3',
                    'dorado': '#F4D03F',
                    'intenso': '#D4AC0D',
                }
            },
            fontFamily: {
                'crimson': ['Crimson Text', 'serif'],
                'playfair': ['Playfair Display', 'serif'],
            }
        }
    }
}
```

### **Configuración de Vite**

```javascript
// vite.config.js
export default defineConfig({
  root: '.',
  base: './',
  build: {
    outDir: 'dist',
    assetsDir: 'assets',
    sourcemap: true,
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'index.html'),
        tarjetas: resolve(__dirname, 'tarjetas-poemas.html'),
        lista: resolve(__dirname, 'lista-scroll.html'),
        libro: resolve(__dirname, 'libro-tradicional.html'),
      }
    }
  }
});
```

### **Configuración de PostCSS**

```javascript
// postcss.config.js
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
```

---

## 📱 Responsive Design

### **Breakpoints**

```css
/* Mobile First */
/* Base: 320px - 479px */

/* Tablet */
@media (min-width: 768px) {
  /* Estilos para tablet */
}

/* Desktop */
@media (min-width: 1024px) {
  /* Estilos para desktop */
}

/* Large Desktop */
@media (min-width: 1280px) {
  /* Estilos para pantallas grandes */
}
```

### **Grid System**

```html
<!-- Ejemplo de grid responsivo -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
  <!-- Contenido -->
</div>
```

---

## 🎨 Personalización del Diseño

### **Cambiar Colores**

1. **Modificar configuración de Tailwind** en cada archivo HTML:
```javascript
colors: {
    'tu-color': {
        'principal': '#TU_COLOR',
        'claro': '#TU_COLOR_CLARO',
        'oscuro': '#TU_COLOR_OSCURO',
        'muy-claro': '#TU_COLOR_MUY_CLARO',
    }
}
```

2. **Actualizar variables CSS** en `src/styles/main.css`:
```css
:root {
    --color-tu-color-principal: #TU_COLOR;
    /* ... */
}
```

### **Cambiar Tipografías**

1. **Importar nuevas fuentes** en `src/styles/main.css`:
```css
@import url('https://fonts.googleapis.com/css2?family=Nueva+Fuente:wght@400;700&display=swap');
```

2. **Actualizar configuración**:
```css
--font-nueva: 'Nueva Fuente', serif;
```

### **Modificar Animaciones**

```css
/* En src/styles/main.css */
@keyframes nuevaAnimacion {
    0% { /* estado inicial */ }
    100% { /* estado final */ }
}

.animate-nueva {
    animation: nuevaAnimacion 0.5s ease-in-out;
}
```

---

## 🔒 Seguridad y Mejores Prácticas

### **Seguridad del Panel Administrativo**

```php
// Configuración de seguridad básica
// En admin/.htaccess
<Files "database.php">
    Require all denied
</Files>

<Files "migrate.php">
    Require all denied
</Files>
```

### **Validación de Datos**

```php
// Ejemplo de validación en la API
function validatePoemData($data) {
    $errors = [];
    
    if (empty($data['titulo']) || strlen($data['titulo']) > 200) {
        $errors[] = 'Título inválido';
    }
    
    if (empty($data['contenido'])) {
        $errors[] = 'Contenido requerido';
    }
    
    return $errors;
}
```

### **Sanitización de Entrada**

```php
// Sanitizar datos de entrada
$titulo = filter_var($data['titulo'], FILTER_SANITIZE_STRING);
$contenido = htmlspecialchars($data['contenido'], ENT_QUOTES, 'UTF-8');
```

---

## 🚀 Despliegue en Producción

### **Configuración del Servidor**

#### **Apache (.htaccess)**
```apache
# Configuración de producción
RewriteEngine On

# Ocultar información del servidor
ServerTokens Prod
ServerSignature Off

# Configurar cache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>

# Configurar compresión
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css application/javascript
</IfModule>
```

#### **Nginx**
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /var/www/album;
    index index.html index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }

    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1M;
        add_header Cache-Control "public, immutable";
    }
}
```

### **Optimización de Rendimiento**

#### **1. Minificación**
```bash
# Construir versión optimizada
npm run build

# Los archivos se generarán en dist/
```

#### **2. Compresión de Imágenes**
```bash
# Usar herramientas como ImageOptim, TinyPNG, etc.
# Optimizar todas las imágenes antes del despliegue
```

#### **3. CDN para Assets**
```html
<!-- Usar CDN para librerías externas -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
```

---

## 🔧 Mantenimiento y Actualizaciones

### **Respaldos de Base de Datos**

```bash
# Respaldar base de datos
cp data/poemas.db backups/poemas_backup_$(date +%Y%m%d).db

# Restaurar base de datos
cp backups/poemas_backup_20240101.db data/poemas.db
```

### **Logs y Monitoreo**

```php
// Configurar logging en admin/config.php
define('LOG_ENABLED', true);
define('LOG_FILE', '../logs/system.log');

function logMessage($message, $level = 'INFO') {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
    file_put_contents(LOG_FILE, $logEntry, FILE_APPEND | LOCK_EX);
}
```

### **Actualizaciones**

```bash
# 1. Respaldar datos
cp -r data/ backups/

# 2. Actualizar archivos
# (Reemplazar archivos del proyecto)

# 3. Ejecutar migraciones si es necesario
php admin/migrate.php

# 4. Verificar funcionamiento
```

---

## 🐛 Solución de Problemas

### **Problemas Comunes**

#### **1. Error 500 - Internal Server Error**
```bash
# Verificar:
- Permisos de escritura en directorio data/
- Extensiones PHP (sqlite3, pdo_sqlite)
- Sintaxis de archivos PHP
- Logs del servidor web
```

#### **2. Base de Datos No Se Conecta**
```bash
# Verificar:
- Directorio data/ existe
- Permisos de escritura
- Extensión SQLite3 habilitada
```

#### **3. API No Responde**
```bash
# Verificar:
- Configuración de CORS
- Rutas de la API
- Logs de PHP
```

#### **4. Estilos No Se Cargan**
```bash
# Verificar:
- Conexión a internet (para Tailwind CDN)
- Configuración de Tailwind
- Cache del navegador
```

### **Herramientas de Debug**

```bash
# Verificar estado del sistema
http://tu-dominio/admin/test.php

# Verificar API
http://tu-dominio/admin/api/test.php

# Logs del servidor
tail -f /var/log/apache2/error.log
```

---

## 📊 Métricas y Análisis

### **Rendimiento**

```javascript
// Medir tiempo de carga
window.addEventListener('load', function() {
    const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
    console.log('Tiempo de carga:', loadTime + 'ms');
});
```

### **Uso de la API**

```php
// Log de uso de API
function logApiUsage($endpoint, $method, $response_time) {
    $log = [
        'timestamp' => date('Y-m-d H:i:s'),
        'endpoint' => $endpoint,
        'method' => $method,
        'response_time' => $response_time
    ];
    
    file_put_contents('logs/api_usage.log', json_encode($log) . "\n", FILE_APPEND);
}
```

---

## 🎯 Roadmap y Futuras Mejoras

### **Fase 1 - Funcionalidades Básicas** ✅
- [x] Panel administrativo funcional
- [x] API REST completa
- [x] Base de datos SQLite
- [x] Frontend dinámico

### **Fase 2 - Mejoras de UX**
- [ ] Autenticación para el panel admin
- [ ] Editor de texto enriquecido (WYSIWYG)
- [ ] Subida de imágenes para poemas
- [ ] Sistema de comentarios

### **Fase 3 - Funcionalidades Avanzadas**
- [ ] Sistema de usuarios
- [ ] Favoritos y marcadores
- [ ] Exportación a PDF
- [ ] Temas personalizables

### **Fase 4 - Optimización**
- [ ] Cache de base de datos
- [ ] Compresión de assets
- [ ] PWA (Progressive Web App)
- [ ] SEO optimizado

---

## 📞 Soporte y Contribución

### **Documentación Adicional**

- **README.md**: Guía de inicio rápido
- **ARCHITECTURE.md**: Arquitectura técnica detallada
- **MIGRATION_GUIDE.md**: Guía de migración de datos

### **Contacto**

Para soporte técnico o reportar problemas:
1. Revisar esta documentación
2. Verificar logs de error
3. Consultar la sección de solución de problemas
4. Contactar al desarrollador

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo LICENSE para más detalles.

---

**Versión de la Documentación**: 1.0.0  
**Última actualización**: Enero 2024  
**Autor**: Sistema de Poemas  

---

*Esta documentación proporciona una guía completa para entender, instalar, configurar y mantener el Sistema de Poemas Dinámico. Para cualquier duda específica, consulta las secciones correspondientes o contacta al equipo de desarrollo.*
