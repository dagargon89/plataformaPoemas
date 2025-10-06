# 📚 Sistema de Poemas Dinámico

Un sistema completo de gestión de poemas con múltiples vistas de lectura y panel administrativo.

## 🏗️ Estructura del Proyecto

```
plataformaPoemas/
├── 📄 index.html                    # Página principal
├── 📁 src/                          # Código fuente (MVC)
│   ├── 📁 components/               # Componentes reutilizables
│   │   ├── BaseComponent.js         # Clase base para componentes
│   │   ├── NavbarComponent.js       # Barra de navegación
│   │   └── FooterComponent.js       # Pie de página
│   ├── 📁 config/                   # Configuraciones
│   │   └── app-config.js            # Configuración principal
│   ├── 📁 pages/                    # Páginas/Vistas (Controllers)
│   │   ├── BasePage.js              # Clase base para páginas
│   │   └── HomePage.js              # Página principal
│   ├── 📁 services/                 # Servicios
│   │   └── RouterService.js         # Gestión de rutas
│   ├── 📁 styles/                   # Estilos
│   │   └── main.css                 # Estilos principales
│   └── app.js                       # Aplicación principal
├── 📁 templates/                    # Plantillas HTML
│   ├── navbar.html                  # Plantilla de navegación
│   └── footer.html                  # Plantilla de pie
├── 📁 admin/                        # Panel administrativo
│   ├── 📁 api/                      # API REST
│   └── 📁 js/                       # JavaScript del panel
├── 📁 data/                         # Base de datos
└── 📄 DOCUMENTACION_COMPLETA.md     # Documentación completa
```

## 🚀 Características

- **Patrón MVC**: Arquitectura Model-View-Controller
- **Componentes Reutilizables**: Sistema modular de componentes
- **Responsive Design**: Diseño adaptativo con Tailwind CSS
- **API REST**: Backend con PHP y MySQL
- **Panel Administrativo**: Gestión completa de contenido
- **Múltiples Vistas**: Tarjetas, Lista y Libro tradicional

## 🎨 Tecnologías

- **Frontend**: HTML5, CSS3, JavaScript ES6+, Tailwind CSS
- **Backend**: PHP 7.4+, MySQL 5.7+
- **Patrón**: MVC con componentes modulares

## 📱 Vistas Disponibles

1. **Vista Tarjetas** - Poemas en tarjetas interactivas con efectos 3D
2. **Vista Lista** - Lista de poemas con scroll suave y animaciones  
3. **Vista Libro** - Simulación de libro tradicional con volteo de páginas

## 🔧 Instalación

1. **Requisitos**:
   - PHP 7.4 o superior
   - MySQL 5.7 o superior
   - Extensiones: `pdo_mysql`, `json`
   - Servidor web (Apache/Nginx)

2. **Configuración**:
   ```bash
   # El proyecto debe estar en tu directorio web
   # Ejemplo: C:\laragon\www\plataformaPoemas\
   ```

3. **Verificación**:
   - Acceder a: `http://tu-dominio/`
   - Panel admin: `http://tu-dominio/admin/`

## 🎯 Uso

### Página Principal
- Navegación entre diferentes vistas de lectura
- Estadísticas del sistema
- Acceso al panel administrativo

### Panel Administrativo
- Gestión de poemas, autores, categorías
- API REST para datos dinámicos
- Sistema de migración de base de datos

## 📊 Arquitectura MVC

### Model (Modelo)
- **Base de Datos**: SQLite con tablas relacionadas
- **API**: Endpoints REST para CRUD operations
- **Servicios**: Gestión de datos y estado

### View (Vista)
- **Templates**: Plantillas HTML reutilizables
- **Componentes**: Elementos UI modulares
- **Páginas**: Vistas específicas de cada funcionalidad

### Controller (Controlador)
- **Páginas**: Lógica de presentación
- **Servicios**: Lógica de negocio
- **Router**: Gestión de navegación

## 🔄 Flujo de Datos

1. **Usuario** interactúa con la interfaz
2. **Router** maneja la navegación
3. **Page Controller** procesa la lógica
4. **API Service** obtiene datos del backend
5. **Components** renderizan la información
6. **View** muestra el resultado al usuario

## 🎨 Personalización

### Colores
Editar variables CSS en `src/styles/main.css`:
```css
:root {
    --color-verde-principal: #636B2F;
    --color-rosa-principal: #E89EB8;
    /* ... */
}
```

### Configuración
Modificar `src/config/app-config.js`:
```javascript
export const AppConfig = {
    appName: 'Mi Sistema de Poemas',
    // ... más configuraciones
};
```

## 📝 Próximas Funcionalidades

- [ ] Páginas de vistas específicas (Tarjetas, Lista, Libro)
- [ ] Panel administrativo completo
- [ ] API REST funcional
- [ ] Sistema de autenticación
- [ ] Editor de texto enriquecido

## 📞 Soporte

Para más información, consulta `DOCUMENTACION_COMPLETA.md`

---

**Versión**: 1.0.0  
**Desarrollado con**: ❤️ y JavaScript
