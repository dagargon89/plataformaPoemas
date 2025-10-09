# 🚀 Inicio Rápido - API RESTful Pública v1

## ⚡ Comienza en 3 pasos

### 1️⃣ Verifica que todo funcione

Abre en tu navegador:
```
http://localhost/plataformaPoemas/api/PRUEBA_API.html
```

Haz clic en cualquier botón para probar los endpoints.

---

### 2️⃣ Prueba tu primera petición

Abre tu navegador o usa curl:

```bash
# Obtener todos los poemas
http://localhost/plataformaPoemas/api/v1/poemas.php

# O con curl en la terminal
curl "http://localhost/plataformaPoemas/api/v1/poemas.php?limit=5"
```

---

### 3️⃣ Integra en tu aplicación

**JavaScript/Frontend:**
```javascript
fetch('http://localhost/plataformaPoemas/api/v1/poemas.php')
  .then(res => res.json())
  .then(data => {
    console.log('Poemas:', data.data);
    console.log('Total:', data.meta.pagination.total_items);
  });
```

**Python/Backend:**
```python
import requests

response = requests.get('http://localhost/plataformaPoemas/api/v1/poemas.php')
poemas = response.json()

print(f"Total de poemas: {poemas['meta']['pagination']['total_items']}")
```

---

## 📚 Endpoints Disponibles

### Poemas
```
GET /api/v1/poemas.php           # Todos los poemas
GET /api/v1/poemas.php/1         # Poema específico
GET /api/v1/poemas.php?search=amor&limit=10
```

### Autores
```
GET /api/v1/autores.php          # Todos los autores
GET /api/v1/autores.php/1        # Autor específico con sus poemas
```

### Categorías
```
GET /api/v1/categorias.php       # Todas las categorías
GET /api/v1/categorias.php/2     # Categoría específica con sus poemas
```

### Etiquetas
```
GET /api/v1/etiquetas.php        # Todas las etiquetas
GET /api/v1/etiquetas.php/1      # Etiqueta específica con sus poemas
```

---

## 🎯 Parámetros Comunes

| Parámetro | Descripción | Ejemplo |
|-----------|-------------|---------|
| `page` | Número de página | `?page=2` |
| `limit` | Resultados por página (max: 100) | `?limit=20` |
| `search` | Buscar texto | `?search=amor` |
| `orden` | Ordenar (asc/desc) | `?orden=asc` |
| `categoria` | Filtrar por categoría | `?categoria=2` |
| `autor` | Filtrar por autor | `?autor=1` |
| `etiqueta` | Filtrar por etiqueta | `?etiqueta=3` |

---

## 💡 Ejemplos Útiles

### Buscar poemas sobre "amor" ordenados por antigüedad
```
/api/v1/poemas.php?search=amor&orden=asc&limit=10
```

### Obtener poemas de una categoría específica
```
/api/v1/poemas.php?categoria=2&page=1
```

### Buscar autores que contengan "neruda"
```
/api/v1/autores.php?search=neruda
```

### Obtener poemas con una etiqueta específica
```
/api/v1/poemas.php?etiqueta=1&limit=5
```

---

## 📖 Formato de Respuesta

Todas las respuestas siguen este formato:

```json
{
  "success": true,
  "data": [ ... ],
  "meta": {
    "timestamp": "2025-10-09T12:00:00+00:00",
    "version": "v1",
    "pagination": {
      "total_items": 50,
      "total_pages": 3,
      "current_page": 1,
      "items_per_page": 20,
      "has_next_page": true,
      "has_prev_page": false
    }
  }
}
```

---

## 🔧 Ejemplos de Código

### React Component
```jsx
import { useState, useEffect } from 'react';

function Poemas() {
  const [poemas, setPoemas] = useState([]);
  
  useEffect(() => {
    fetch('http://localhost/plataformaPoemas/api/v1/poemas.php?limit=10')
      .then(res => res.json())
      .then(data => setPoemas(data.data));
  }, []);
  
  return (
    <div>
      {poemas.map(poema => (
        <div key={poema.id}>
          <h2>{poema.titulo}</h2>
          <p>{poema.extracto}</p>
          <small>Por: {poema.autor.nombre}</small>
        </div>
      ))}
    </div>
  );
}
```

### Vue.js Component
```vue
<template>
  <div>
    <div v-for="poema in poemas" :key="poema.id">
      <h2>{{ poema.titulo }}</h2>
      <p>{{ poema.extracto }}</p>
      <small>Por: {{ poema.autor.nombre }}</small>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      poemas: []
    }
  },
  mounted() {
    fetch('http://localhost/plataformaPoemas/api/v1/poemas.php?limit=10')
      .then(res => res.json())
      .then(data => this.poemas = data.data);
  }
}
</script>
```

### jQuery
```javascript
$.ajax({
  url: 'http://localhost/plataformaPoemas/api/v1/poemas.php',
  data: { limit: 10, categoria: 2 },
  success: function(response) {
    if (response.success) {
      response.data.forEach(function(poema) {
        $('#poemas-list').append(
          '<div>' +
            '<h3>' + poema.titulo + '</h3>' +
            '<p>' + poema.extracto + '</p>' +
          '</div>'
        );
      });
    }
  }
});
```

---

## 📱 Aplicación Móvil (React Native)

```javascript
import React, { useState, useEffect } from 'react';
import { View, Text, FlatList } from 'react-native';

const PoemasScreen = () => {
  const [poemas, setPoemas] = useState([]);

  useEffect(() => {
    fetch('http://tu-servidor.com/api/v1/poemas.php?limit=20')
      .then(res => res.json())
      .then(data => setPoemas(data.data))
      .catch(error => console.error(error));
  }, []);

  return (
    <FlatList
      data={poemas}
      keyExtractor={item => item.id.toString()}
      renderItem={({ item }) => (
        <View>
          <Text style={{ fontSize: 20, fontWeight: 'bold' }}>
            {item.titulo}
          </Text>
          <Text>{item.extracto}</Text>
          <Text>Por: {item.autor.nombre}</Text>
        </View>
      )}
    />
  );
};

export default PoemasScreen;
```

---

## 🐍 Python Script Completo

```python
import requests
import json

# Configuración
API_BASE = 'http://localhost/plataformaPoemas/api/v1'

# Función helper
def obtener_datos(endpoint, params=None):
    url = f"{API_BASE}/{endpoint}"
    response = requests.get(url, params=params)
    
    if response.ok:
        data = response.json()
        if data['success']:
            return data['data']
    return None

# Obtener todos los poemas
poemas = obtener_datos('poemas.php', {'limit': 10})

if poemas:
    print(f"Se encontraron {len(poemas)} poemas:")
    for poema in poemas:
        print(f"- {poema['titulo']} por {poema['autor']['nombre']}")

# Buscar poemas sobre "amor"
poemas_amor = obtener_datos('poemas.php', {'search': 'amor'})

if poemas_amor:
    print(f"\nPoemas sobre 'amor': {len(poemas_amor)}")

# Obtener un autor específico
autor = obtener_datos('autores.php/1')

if autor:
    print(f"\nAutor: {autor['nombre']}")
    print(f"Total de poemas: {autor['total_poemas']}")
```

---

## 🔗 Recursos Adicionales

1. **Documentación Completa**: `api/v1/README.md`
2. **Herramienta de Prueba**: `api/PRUEBA_API.html`
3. **Resumen Ejecutivo**: `api/RESUMEN_APIS.md`
4. **Registro de Cambios**: `api/v1/CHANGELOG.md`
5. **Configuración Opcional**: `api/v1/config.example.php`

---

## ❓ Solución de Problemas

### Error 404 - No se encuentra la API
- Verifica que la ruta sea correcta
- Asegúrate de que el servidor esté corriendo
- Revisa que los archivos estén en la carpeta correcta

### Error 500 - Error del servidor
- Verifica que la base de datos esté configurada
- Revisa los permisos de los archivos
- Consulta los logs del servidor

### No se muestran datos
- Verifica que haya datos en la base de datos
- Prueba con el archivo PRUEBA_API.html
- Revisa la consola del navegador para errores

### CORS Error
- Verifica que el archivo .htaccess esté activo
- Asegúrate de que mod_headers esté habilitado en Apache
- Revisa la configuración CORS en base.php

---

## 🎉 ¡Listo!

Ya puedes empezar a consumir la API desde cualquier aplicación o plataforma.

**Siguiente paso:** Lee la documentación completa en `api/v1/README.md` para conocer todas las funcionalidades disponibles.

---

**¿Necesitas ayuda?** Consulta los archivos de documentación o revisa los ejemplos incluidos.

