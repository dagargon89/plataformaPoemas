# Changelog - API RESTful Pública v1

Todos los cambios notables en esta API serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Versionado Semántico](https://semver.org/lang/es/).

---

## [1.0.0] - 2025-10-09

### 🎉 Lanzamiento Inicial

#### Agregado
- ✅ Endpoint de Poemas (`/api/v1/poemas.php`)
  - Listar todos los poemas con paginación
  - Obtener un poema específico por ID
  - Filtros por categoría, autor y etiqueta
  - Búsqueda en título, contenido y extracto
  - Ordenamiento por fecha (ascendente/descendente)
  
- ✅ Endpoint de Autores (`/api/v1/autores.php`)
  - Listar todos los autores con paginación
  - Obtener un autor específico con sus poemas
  - Búsqueda en nombre y biografía
  - Contador de poemas por autor

- ✅ Endpoint de Categorías (`/api/v1/categorias.php`)
  - Listar todas las categorías con paginación
  - Obtener una categoría específica con sus poemas
  - Búsqueda en nombre y descripción
  - Contador de poemas por categoría

- ✅ Endpoint de Etiquetas (`/api/v1/etiquetas.php`)
  - Listar todas las etiquetas con paginación
  - Obtener una etiqueta específica con sus poemas
  - Búsqueda en nombre
  - Contador de poemas por etiqueta

- ✅ Sistema de Paginación
  - Parámetro `page` (número de página)
  - Parámetro `limit` (elementos por página, max: 100)
  - Metadatos completos de paginación
  - Indicadores has_next_page y has_prev_page

- ✅ Formato de Respuesta Estructurado
  - Campo `success` (boolean)
  - Campo `data` (datos de la respuesta)
  - Campo `meta` (metadatos, paginación, timestamp)
  - Campo `error` (en caso de error)

- ✅ Funciones Comunes (`base.php`)
  - sendJsonResponse() - Respuestas exitosas
  - sendJsonError() - Respuestas de error
  - getPaginationParams() - Parámetros de paginación
  - getPaginationMeta() - Metadatos de paginación
  - getIdFromUrl() - Extracción de ID de URL
  - validateGetMethod() - Validación de método HTTP
  - sanitizeSearchParam() - Sanitización de búsquedas
  - formatDate() - Formato ISO 8601

- ✅ Configuración Apache (`.htaccess`)
  - Headers CORS configurados
  - Cache-Control para mejor rendimiento
  - Headers de seguridad
  - Compresión GZIP
  - URLs amigables (opcional)

- ✅ Documentación Completa
  - README.md con guía completa de uso
  - Ejemplos en JavaScript, Python, PHP, React
  - Tabla de comparación con APIs admin
  - Descripción de todos los endpoints
  - Códigos de estado HTTP

- ✅ Herramientas de Prueba
  - PRUEBA_API.html - Interfaz visual para probar endpoints
  - index.php - Información de la API en JSON
  - RESUMEN_APIS.md - Resumen ejecutivo
  - config.example.php - Configuración opcional

#### Características Técnicas
- 🔒 Solo lectura (GET) - Seguro para consumo público
- 🌐 CORS habilitado para acceso desde cualquier origen
- 📅 Fechas en formato ISO 8601
- 🔢 Validación de parámetros
- 🛡️ Protección contra inyección SQL (prepared statements)
- ⚡ Respuestas optimizadas con JOINs eficientes
- 📊 Metadatos completos en todas las respuestas
- 🎯 Códigos de estado HTTP apropiados

#### Seguridad
- Validación de entrada de usuario
- Prepared statements para prevenir SQL injection
- Headers de seguridad configurados
- Límite máximo de resultados por página
- Solo método GET permitido
- Sin exposición de información sensible

---

## [1.0.1] - 2025-10-09

### 🐛 Corregido
- **Error SQL en paginación**: Corregido error "Syntax error or access violation: 1064" en consultas con LIMIT y OFFSET
  - Cambiado de placeholders `?` a named parameters `:limit` y `:offset`
  - Agregado `bindValue()` con tipo `PDO::PARAM_INT` para forzar valores enteros
  - Afectaba a: `poemas.php`, `autores.php`, `categorias.php`, `etiquetas.php`
  - Los valores ahora se pasan correctamente como enteros en lugar de strings

---

## [Futuras Versiones]

### En Consideración para v1.1.0
- [ ] Sistema de caché para mejorar rendimiento
- [ ] Rate limiting para prevenir abuso
- [ ] Endpoint de estadísticas generales
- [ ] Búsqueda full-text más avanzada
- [ ] Filtros combinados más complejos
- [ ] Ordenamiento por múltiples campos
- [ ] Endpoint de búsqueda global
- [ ] Soporte para múltiples formatos (JSON, XML)
- [ ] Webhooks para notificaciones
- [ ] API keys opcionales para tracking

### En Consideración para v2.0.0
- [ ] GraphQL endpoint complementario
- [ ] WebSocket support para actualizaciones en tiempo real
- [ ] Sistema de suscripciones
- [ ] Versionado de contenido
- [ ] Multilenguaje
- [ ] Autenticación opcional OAuth2
- [ ] Métricas y analytics

---

## Tipos de Cambios

- **Agregado** - Para funcionalidades nuevas
- **Cambiado** - Para cambios en funcionalidades existentes
- **Deprecado** - Para funcionalidades que serán removidas
- **Removido** - Para funcionalidades removidas
- **Corregido** - Para corrección de bugs
- **Seguridad** - Para vulnerabilidades corregidas

---

## Notas de Versionado

Esta API sigue el versionado semántico (MAJOR.MINOR.PATCH):

- **MAJOR** - Cambios incompatibles con versiones anteriores
- **MINOR** - Nueva funcionalidad compatible con versiones anteriores
- **PATCH** - Correcciones de bugs compatibles con versiones anteriores

---

_Última actualización: 2025-10-09_

