# 📋 Resumen de APIs - Plataforma de Poemas

## 🎯 ¿Qué se ha creado?

Se han creado **dos sistemas de APIs separados** para diferentes propósitos:

### 1️⃣ APIs Administrativas (Existentes - Sin modificar)
📂 Ubicación: `/admin/api/`

**Características:**
- ✅ CRUD completo (Create, Read, Update, Delete)
- ✅ Para uso interno del panel de administración
- ✅ Validaciones de datos completas
- ✅ Gestión de relaciones entre tablas

**Archivos:**
- `autores.php`
- `categorias.php`
- `etiquetas.php`
- `poemas.php`

**Uso:** Panel administrativo de la plataforma

---

### 2️⃣ APIs Públicas RESTful v1 (NUEVAS) ⭐
📂 Ubicación: `/api/v1/`

**Características:**
- ✅ Solo lectura (GET) - Seguras para consumo público
- ✅ Paginación automática en todos los listados
- ✅ Filtros avanzados de búsqueda
- ✅ Formato JSON estructurado y anidado
- ✅ Metadatos completos (paginación, timestamps, filtros)
- ✅ CORS habilitado para acceso desde cualquier origen
- ✅ Fechas en formato ISO 8601
- ✅ Códigos de estado HTTP apropiados

**Archivos creados:**

1. **`base.php`** - Funciones comunes y utilidades
2. **`poemas.php`** - Endpoint para poemas
3. **`autores.php`** - Endpoint para autores
4. **`categorias.php`** - Endpoint para categorías
5. **`etiquetas.php`** - Endpoint para etiquetas
6. **`index.php`** - Página de información de la API
7. **`.htaccess`** - Configuración Apache (URLs amigables, CORS, caché)
8. **`README.md`** - Documentación completa con ejemplos

**Archivos adicionales:**

- **`/api/PRUEBA_API.html`** - Herramienta visual para probar todos los endpoints
- **`/api/RESUMEN_APIS.md`** - Este archivo

---

## 🚀 Cómo usar las APIs públicas

### Acceso rápido

1. **Ver información de la API:**
   ```
   http://tu-dominio.com/api/v1/
   ```

2. **Probar visualmente:**
   ```
   http://tu-dominio.com/api/PRUEBA_API.html
   ```

3. **Leer documentación completa:**
   ```
   http://tu-dominio.com/api/v1/README.md
   ```

### Ejemplos básicos

#### Obtener todos los poemas (paginado)
```bash
GET http://tu-dominio.com/api/v1/poemas.php?page=1&limit=10
```

#### Buscar poemas sobre "amor"
```bash
GET http://tu-dominio.com/api/v1/poemas.php?search=amor
```

#### Obtener un poema específico
```bash
GET http://tu-dominio.com/api/v1/poemas.php/1
```

#### Filtrar poemas por categoría
```bash
GET http://tu-dominio.com/api/v1/poemas.php?categoria=2
```

#### Obtener autores con sus poemas
```bash
GET http://tu-dominio.com/api/v1/autores.php/1
```

---

## 📊 Comparación de APIs

| Característica | APIs Admin | APIs Públicas v1 |
|----------------|------------|------------------|
| **Ubicación** | `/admin/api/` | `/api/v1/` |
| **Métodos HTTP** | GET, POST, PUT, DELETE | Solo GET |
| **Propósito** | Gestión interna | Consumo externo |
| **Autenticación** | Requerida (admin) | Pública |
| **Paginación** | ❌ No | ✅ Sí |
| **Filtros** | ⚠️ Básicos | ✅ Avanzados |
| **Búsqueda** | ❌ No | ✅ Sí |
| **Formato JSON** | Plano | Estructurado/Anidado |
| **Metadatos** | ⚠️ Básicos | ✅ Completos |
| **CORS** | ✅ Sí | ✅ Sí |
| **Documentación** | ⚠️ En código | ✅ README completo |
| **Modificaciones** | Permitidas | Solo lectura |

---

## 🎨 Ejemplo de Respuesta - APIs Públicas

### Petición:
```bash
GET /api/v1/poemas.php/2
```

### Respuesta:
```json
{
  "success": true,
  "data": {
    "id": 2,
    "titulo": "Tu nombre en mi corazón",
    "icono": "💖",
    "extracto": "Escribo tu nombre en mi corazón cada día...",
    "contenido": "Escribo tu nombre en mi corazón cada día,\ncomo si fuera la primera vez...",
    "tiempo_lectura": 4,
    "autor": {
      "id": 2,
      "nombre": "Pablo Neruda",
      "biografia": "Poeta chileno, premio Nobel de Literatura 1971."
    },
    "categoria": {
      "id": 2,
      "nombre": "Amor",
      "icono": "💕",
      "color": "#E89EB8",
      "descripcion": "Poemas románticos y de amor"
    },
    "etiquetas": [
      {
        "id": 1,
        "nombre": "Romántico"
      },
      {
        "id": 7,
        "nombre": "Esperanza"
      }
    ],
    "fechas": {
      "creacion": "2025-10-09T10:30:00+00:00",
      "actualizacion": "2025-10-09T10:30:00+00:00"
    }
  },
  "meta": {
    "timestamp": "2025-10-09T12:45:30+00:00",
    "version": "v1"
  }
}
```

---

## 🔧 Casos de Uso

### 1. Aplicación Móvil (React Native / Flutter)
```javascript
// Obtener poemas para mostrar en la app
fetch('https://tu-api.com/api/v1/poemas.php?limit=20')
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      mostrarPoemas(data.data);
    }
  });
```

### 2. Widget de Sitio Web
```html
<script>
// Mostrar poema aleatorio del día
fetch('https://tu-api.com/api/v1/poemas.php?limit=1')
  .then(res => res.json())
  .then(data => {
    document.getElementById('poema-del-dia').innerHTML = 
      data.data[0].contenido;
  });
</script>
```

### 3. Dashboard de Estadísticas
```python
import requests

# Obtener estadísticas de todos los autores
response = requests.get('https://tu-api.com/api/v1/autores.php')
autores = response.json()['data']

for autor in autores:
    print(f"{autor['nombre']}: {autor['total_poemas']} poemas")
```

### 4. Bot de Telegram/Discord
```javascript
// Buscar poemas por palabra clave
async function buscarPoema(keyword) {
  const response = await fetch(
    `https://tu-api.com/api/v1/poemas.php?search=${keyword}&limit=1`
  );
  const data = await response.json();
  
  if (data.success && data.data.length > 0) {
    return data.data[0];
  }
  return null;
}
```

---

## 📝 Notas Importantes

### ✅ Ventajas de las APIs Públicas v1

1. **Seguridad**: Solo lectura, no se pueden modificar datos
2. **Rendimiento**: Respuestas cacheables, paginación eficiente
3. **Escalabilidad**: Diseñadas para alto tráfico
4. **Compatibilidad**: Funcionan con cualquier lenguaje/framework
5. **Mantenibilidad**: Código limpio y bien documentado
6. **Versionado**: v1 permite futuras actualizaciones sin romper integraciones

### 🎯 Cuándo usar cada API

**Usa APIs Admin (`/admin/api/`):**
- ✅ Panel de administración
- ✅ Crear, editar o eliminar contenido
- ✅ Gestión interna de la plataforma
- ✅ Operaciones que requieren autenticación

**Usa APIs Públicas v1 (`/api/v1/`):**
- ✅ Aplicaciones móviles
- ✅ Sitios web de terceros
- ✅ Widgets y embeds
- ✅ Bots y automatizaciones
- ✅ Integraciones externas
- ✅ Consumo público de datos

---

## 🔐 Seguridad

Las APIs públicas v1 están diseñadas con las siguientes medidas de seguridad:

1. **Solo lectura (GET)**: No se pueden modificar datos
2. **Límite de resultados**: Máximo 100 elementos por página
3. **CORS configurado**: Headers de seguridad apropiados
4. **Validación de parámetros**: Previene inyección SQL
5. **Manejo de errores**: No expone información sensible
6. **Sin autenticación requerida**: Datos públicos

---

## 📚 Recursos

- **Documentación completa**: `/api/v1/README.md`
- **Herramienta de prueba**: `/api/PRUEBA_API.html`
- **Info de la API**: `/api/v1/` (JSON)
- **Código fuente**: `/api/v1/*.php`

---

## 🎉 ¡Listo para usar!

Las APIs públicas están completamente funcionales y listas para ser consumidas desde cualquier plataforma o aplicación.

**Próximos pasos sugeridos:**

1. ✅ Abre `/api/PRUEBA_API.html` en tu navegador
2. ✅ Prueba todos los endpoints visualmente
3. ✅ Lee la documentación en `/api/v1/README.md`
4. ✅ Integra las APIs en tu aplicación externa
5. ✅ Comparte la documentación con tu equipo

---

**¿Necesitas ayuda?** Consulta la documentación completa o revisa los ejemplos de código incluidos.

