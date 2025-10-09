# API RESTful Pública v1 - Plataforma de Poemas

Esta es la documentación completa de la API RESTful pública para consumir los datos de la plataforma de poemas desde aplicaciones externas.

## 📋 Características

- ✅ **Solo lectura (GET)**: Endpoints seguros para consulta de datos
- ✅ **Paginación**: Todos los listados soportan paginación
- ✅ **Filtros avanzados**: Búsqueda y filtrado de contenido
- ✅ **CORS habilitado**: Compatible con aplicaciones web desde cualquier origen
- ✅ **Respuestas JSON estructuradas**: Formato consistente y predecible
- ✅ **Metadatos incluidos**: Información de paginación y filtros aplicados
- ✅ **Fechas en formato ISO 8601**: Compatible con cualquier librería de manejo de fechas

## 🚀 Base URL

```
http://tu-dominio.com/api/v1/
```

## 📦 Formato de Respuesta

### Respuesta Exitosa

```json
{
  "success": true,
  "data": { ... },
  "meta": {
    "timestamp": "2025-10-09T10:30:00+00:00",
    "version": "v1",
    "pagination": {
      "total_items": 50,
      "total_pages": 3,
      "current_page": 1,
      "items_per_page": 20,
      "has_next_page": true,
      "has_prev_page": false
    },
    "filters": { ... }
  }
}
```

### Respuesta de Error

```json
{
  "success": false,
  "error": {
    "message": "Descripción del error",
    "code": 404,
    "details": { ... }
  },
  "meta": {
    "timestamp": "2025-10-09T10:30:00+00:00",
    "version": "v1"
  }
}
```

## 📚 Endpoints

### 1. Poemas

#### Listar todos los poemas
```
GET /api/v1/poemas.php
```

**Parámetros de consulta:**

| Parámetro | Tipo | Descripción | Default |
|-----------|------|-------------|---------|
| `page` | integer | Número de página | 1 |
| `limit` | integer | Elementos por página (max: 100) | 20 |
| `categoria` | integer | Filtrar por ID de categoría | - |
| `autor` | integer | Filtrar por ID de autor | - |
| `etiqueta` | integer | Filtrar por ID de etiqueta | - |
| `search` | string | Buscar en título, contenido y extracto | - |
| `orden` | string | Ordenar por fecha (`asc` o `desc`) | desc |

**Ejemplo de solicitud:**
```bash
curl "http://tu-dominio.com/api/v1/poemas.php?page=1&limit=10&categoria=2&search=amor"
```

**Ejemplo de respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "titulo": "Tu nombre en mi corazón",
      "icono": "💖",
      "extracto": "Escribo tu nombre en mi corazón cada día...",
      "contenido": "Escribo tu nombre en mi corazón cada día...",
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
    }
  ],
  "meta": {
    "timestamp": "2025-10-09T10:30:00+00:00",
    "version": "v1",
    "pagination": {
      "total_items": 1,
      "total_pages": 1,
      "current_page": 1,
      "items_per_page": 10,
      "has_next_page": false,
      "has_prev_page": false
    },
    "filters": {
      "categoria": 2,
      "search": "amor"
    }
  }
}
```

#### Obtener un poema específico
```
GET /api/v1/poemas.php/{id}
```

**Ejemplo de solicitud:**
```bash
curl "http://tu-dominio.com/api/v1/poemas.php/2"
```

---

### 2. Autores

#### Listar todos los autores
```
GET /api/v1/autores.php
```

**Parámetros de consulta:**

| Parámetro | Tipo | Descripción | Default |
|-----------|------|-------------|---------|
| `page` | integer | Número de página | 1 |
| `limit` | integer | Elementos por página (max: 100) | 20 |
| `search` | string | Buscar en nombre y biografía | - |
| `orden` | string | Ordenar por nombre (`asc` o `desc`) | asc |

**Ejemplo de solicitud:**
```bash
curl "http://tu-dominio.com/api/v1/autores.php?page=1&limit=10"
```

**Ejemplo de respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "nombre": "Pablo Neruda",
      "biografia": "Poeta chileno, premio Nobel de Literatura 1971.",
      "total_poemas": 5,
      "fechas": {
        "creacion": "2025-10-09T10:30:00+00:00",
        "actualizacion": "2025-10-09T10:30:00+00:00"
      }
    }
  ],
  "meta": {
    "timestamp": "2025-10-09T10:30:00+00:00",
    "version": "v1",
    "pagination": {
      "total_items": 5,
      "total_pages": 1,
      "current_page": 1,
      "items_per_page": 10,
      "has_next_page": false,
      "has_prev_page": false
    }
  }
}
```

#### Obtener un autor específico con sus poemas
```
GET /api/v1/autores.php/{id}
```

**Ejemplo de solicitud:**
```bash
curl "http://tu-dominio.com/api/v1/autores.php/2"
```

---

### 3. Categorías

#### Listar todas las categorías
```
GET /api/v1/categorias.php
```

**Parámetros de consulta:**

| Parámetro | Tipo | Descripción | Default |
|-----------|------|-------------|---------|
| `page` | integer | Número de página | 1 |
| `limit` | integer | Elementos por página (max: 100) | 20 |
| `search` | string | Buscar en nombre y descripción | - |
| `orden` | string | Ordenar por nombre (`asc` o `desc`) | asc |

**Ejemplo de solicitud:**
```bash
curl "http://tu-dominio.com/api/v1/categorias.php"
```

**Ejemplo de respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "nombre": "Amor",
      "icono": "💕",
      "color": "#E89EB8",
      "descripcion": "Poemas románticos y de amor",
      "total_poemas": 8,
      "fechas": {
        "creacion": "2025-10-09T10:30:00+00:00",
        "actualizacion": "2025-10-09T10:30:00+00:00"
      }
    }
  ],
  "meta": {
    "timestamp": "2025-10-09T10:30:00+00:00",
    "version": "v1",
    "pagination": {
      "total_items": 5,
      "total_pages": 1,
      "current_page": 1,
      "items_per_page": 20,
      "has_next_page": false,
      "has_prev_page": false
    }
  }
}
```

#### Obtener una categoría específica con sus poemas
```
GET /api/v1/categorias.php/{id}
```

**Ejemplo de solicitud:**
```bash
curl "http://tu-dominio.com/api/v1/categorias.php/2"
```

---

### 4. Etiquetas

#### Listar todas las etiquetas
```
GET /api/v1/etiquetas.php
```

**Parámetros de consulta:**

| Parámetro | Tipo | Descripción | Default |
|-----------|------|-------------|---------|
| `page` | integer | Número de página | 1 |
| `limit` | integer | Elementos por página (max: 100) | 20 |
| `search` | string | Buscar en nombre | - |
| `orden` | string | Ordenar por nombre (`asc` o `desc`) | asc |

**Ejemplo de solicitud:**
```bash
curl "http://tu-dominio.com/api/v1/etiquetas.php"
```

**Ejemplo de respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Romántico",
      "total_poemas": 10,
      "fecha_creacion": "2025-10-09T10:30:00+00:00"
    }
  ],
  "meta": {
    "timestamp": "2025-10-09T10:30:00+00:00",
    "version": "v1",
    "pagination": {
      "total_items": 10,
      "total_pages": 1,
      "current_page": 1,
      "items_per_page": 20,
      "has_next_page": false,
      "has_prev_page": false
    }
  }
}
```

#### Obtener una etiqueta específica con sus poemas
```
GET /api/v1/etiquetas.php/{id}
```

**Ejemplo de solicitud:**
```bash
curl "http://tu-dominio.com/api/v1/etiquetas.php/1"
```

---

## 💻 Ejemplos de Uso

### JavaScript (Fetch API)

```javascript
// Obtener poemas con filtros
async function obtenerPoemas() {
  try {
    const response = await fetch('http://tu-dominio.com/api/v1/poemas.php?page=1&limit=10&categoria=2');
    const data = await response.json();
    
    if (data.success) {
      console.log('Poemas:', data.data);
      console.log('Total de páginas:', data.meta.pagination.total_pages);
    } else {
      console.error('Error:', data.error.message);
    }
  } catch (error) {
    console.error('Error de conexión:', error);
  }
}

// Obtener un poema específico
async function obtenerPoema(id) {
  try {
    const response = await fetch(`http://tu-dominio.com/api/v1/poemas.php/${id}`);
    const data = await response.json();
    
    if (data.success) {
      console.log('Poema:', data.data);
    } else {
      console.error('Error:', data.error.message);
    }
  } catch (error) {
    console.error('Error de conexión:', error);
  }
}
```

### JavaScript (Axios)

```javascript
import axios from 'axios';

const API_BASE_URL = 'http://tu-dominio.com/api/v1';

// Obtener poemas con filtros
axios.get(`${API_BASE_URL}/poemas.php`, {
  params: {
    page: 1,
    limit: 10,
    categoria: 2,
    search: 'amor'
  }
})
.then(response => {
  console.log('Poemas:', response.data.data);
  console.log('Meta:', response.data.meta);
})
.catch(error => {
  console.error('Error:', error.response?.data?.error || error.message);
});
```

### Python (Requests)

```python
import requests

API_BASE_URL = 'http://tu-dominio.com/api/v1'

# Obtener poemas con filtros
response = requests.get(f'{API_BASE_URL}/poemas.php', params={
    'page': 1,
    'limit': 10,
    'categoria': 2,
    'search': 'amor'
})

if response.ok:
    data = response.json()
    if data['success']:
        poemas = data['data']
        print(f"Total de poemas: {data['meta']['pagination']['total_items']}")
        for poema in poemas:
            print(f"- {poema['titulo']} por {poema['autor']['nombre']}")
    else:
        print(f"Error: {data['error']['message']}")
else:
    print(f"Error HTTP: {response.status_code}")
```

### PHP (cURL)

```php
<?php
$apiUrl = 'http://tu-dominio.com/api/v1/poemas.php';
$params = http_build_query([
    'page' => 1,
    'limit' => 10,
    'categoria' => 2
]);

$ch = curl_init("$apiUrl?$params");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    
    if ($data['success']) {
        echo "Total de poemas: " . $data['meta']['pagination']['total_items'] . "\n";
        foreach ($data['data'] as $poema) {
            echo "- {$poema['titulo']} por {$poema['autor']['nombre']}\n";
        }
    } else {
        echo "Error: {$data['error']['message']}\n";
    }
} else {
    echo "Error HTTP: $httpCode\n";
}
?>
```

### React Example

```jsx
import React, { useState, useEffect } from 'react';

const API_BASE_URL = 'http://tu-dominio.com/api/v1';

function ListaPoemas() {
  const [poemas, setPoemas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [pagination, setPagination] = useState({});

  useEffect(() => {
    fetch(`${API_BASE_URL}/poemas.php?page=1&limit=10`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          setPoemas(data.data);
          setPagination(data.meta.pagination);
        } else {
          setError(data.error.message);
        }
        setLoading(false);
      })
      .catch(err => {
        setError('Error de conexión');
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Cargando...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div>
      <h1>Poemas ({pagination.total_items})</h1>
      {poemas.map(poema => (
        <article key={poema.id}>
          <h2>{poema.icono} {poema.titulo}</h2>
          <p>Por: {poema.autor.nombre}</p>
          <p>{poema.extracto}</p>
          <span style={{ 
            backgroundColor: poema.categoria.color,
            padding: '5px 10px',
            borderRadius: '5px'
          }}>
            {poema.categoria.icono} {poema.categoria.nombre}
          </span>
        </article>
      ))}
    </div>
  );
}

export default ListaPoemas;
```

---

## 🔒 Códigos de Estado HTTP

| Código | Descripción |
|--------|-------------|
| 200 | OK - Solicitud exitosa |
| 400 | Bad Request - Parámetros inválidos |
| 404 | Not Found - Recurso no encontrado |
| 405 | Method Not Allowed - Solo se permite GET |
| 500 | Internal Server Error - Error del servidor |

---

## ⚡ Mejores Prácticas

1. **Usa paginación**: No solicites todos los datos de una vez, usa los parámetros `page` y `limit`
2. **Implementa caché**: Almacena respuestas en caché para mejorar el rendimiento
3. **Maneja errores**: Siempre verifica el campo `success` en la respuesta
4. **Usa filtros**: Aprovecha los filtros para reducir la cantidad de datos transferidos
5. **Respeta los límites**: El límite máximo es 100 elementos por página

---

## 🆚 Diferencias con las APIs Admin

Estas APIs públicas son diferentes de las APIs administrativas (`/admin/api/`):

| Característica | API Pública (v1) | API Admin |
|----------------|------------------|-----------|
| **Acceso** | Solo lectura (GET) | CRUD completo |
| **Paginación** | ✅ Incluida | ❌ No incluida |
| **Filtros** | ✅ Avanzados | ⚠️ Básicos |
| **Formato JSON** | Estructurado y anidado | Plano con relaciones |
| **Metadatos** | ✅ Completos | ⚠️ Básicos |
| **Propósito** | Consumo externo | Gestión interna |

---

## 📞 Soporte

Para reportar problemas o solicitar nuevas características, contacta al equipo de desarrollo.

---

## 📝 Changelog

### v1.0.0 (2025-10-09)
- ✅ Lanzamiento inicial
- ✅ Endpoints para poemas, autores, categorías y etiquetas
- ✅ Paginación y filtros
- ✅ Documentación completa

