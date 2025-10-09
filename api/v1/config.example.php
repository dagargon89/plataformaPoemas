<?php
/**
 * Configuración opcional para las APIs públicas v1
 * 
 * Este archivo es OPCIONAL. Las APIs funcionan perfectamente sin él.
 * Úsalo solo si necesitas personalizar algunos aspectos de la API.
 * 
 * INSTRUCCIONES:
 * 1. Copia este archivo como config.php
 * 2. Modifica los valores según tus necesidades
 * 3. Descomenta las líneas que quieras usar
 */

// Configuración de paginación por defecto
// define('API_DEFAULT_PAGE_SIZE', 20);
// define('API_MAX_PAGE_SIZE', 100);

// Límite de resultados de búsqueda
// define('API_SEARCH_LIMIT', 100);

// Habilitar/deshabilitar caché de respuestas (en segundos)
// define('API_CACHE_ENABLED', true);
// define('API_CACHE_DURATION', 300); // 5 minutos

// Configuración de CORS personalizada
// define('API_CORS_ORIGIN', '*'); // O un dominio específico: 'https://tu-dominio.com'
// define('API_CORS_METHODS', 'GET, OPTIONS');

// Rate limiting (opcional, requiere implementación adicional)
// define('API_RATE_LIMIT_ENABLED', false);
// define('API_RATE_LIMIT_REQUESTS', 100); // Peticiones
// define('API_RATE_LIMIT_WINDOW', 3600); // Por hora

// Zona horaria para timestamps
// date_default_timezone_set('America/Mexico_City');

// Mostrar errores detallados (solo para desarrollo)
// define('API_DEBUG_MODE', false);

// Configuración de logging
// define('API_LOG_ENABLED', false);
// define('API_LOG_FILE', __DIR__ . '/../logs/api-v1.log');

// Campos a excluir en las respuestas (por privacidad)
// define('API_EXCLUDE_FIELDS', [
//     // Ejemplo: 'email', 'password', 'api_key'
// ]);

// Mensaje de mantenimiento (si está en modo mantenimiento)
// define('API_MAINTENANCE_MODE', false);
// define('API_MAINTENANCE_MESSAGE', 'La API está en mantenimiento. Intenta más tarde.');

// Configuración de formato de fecha
// define('API_DATE_FORMAT', 'c'); // ISO 8601 por defecto

// Habilitar compresión GZIP de respuestas
// define('API_GZIP_ENABLED', true);

// Agregar headers personalizados
// define('API_CUSTOM_HEADERS', [
//     'X-API-Version' => 'v1.0.0',
//     'X-Powered-By' => 'Mi Plataforma'
// ]);

?>

