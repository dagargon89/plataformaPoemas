<?php
/**
 * Página de índice de la API RESTful v1
 * Muestra información sobre los endpoints disponibles
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/api/v1';

$response = [
    'success' => true,
    'message' => 'API RESTful Pública v1 - Plataforma de Poemas',
    'version' => '1.0.0',
    'documentation' => $baseUrl . '/README.md',
    'endpoints' => [
        'poemas' => [
            'url' => $baseUrl . '/poemas.php',
            'description' => 'Gestión de poemas',
            'methods' => ['GET'],
            'endpoints' => [
                'GET /poemas.php' => 'Lista todos los poemas (paginado)',
                'GET /poemas.php/{id}' => 'Obtiene un poema específico'
            ],
            'params' => [
                'page' => 'Número de página (default: 1)',
                'limit' => 'Elementos por página (default: 20, max: 100)',
                'categoria' => 'Filtrar por ID de categoría',
                'autor' => 'Filtrar por ID de autor',
                'etiqueta' => 'Filtrar por ID de etiqueta',
                'search' => 'Buscar en título, contenido y extracto',
                'orden' => 'Ordenar por fecha (asc|desc, default: desc)'
            ],
            'examples' => [
                $baseUrl . '/poemas.php?page=1&limit=10',
                $baseUrl . '/poemas.php?categoria=2&search=amor',
                $baseUrl . '/poemas.php/1'
            ]
        ],
        'autores' => [
            'url' => $baseUrl . '/autores.php',
            'description' => 'Gestión de autores',
            'methods' => ['GET'],
            'endpoints' => [
                'GET /autores.php' => 'Lista todos los autores (paginado)',
                'GET /autores.php/{id}' => 'Obtiene un autor específico con sus poemas'
            ],
            'params' => [
                'page' => 'Número de página (default: 1)',
                'limit' => 'Elementos por página (default: 20, max: 100)',
                'search' => 'Buscar en nombre y biografía',
                'orden' => 'Ordenar por nombre (asc|desc, default: asc)'
            ],
            'examples' => [
                $baseUrl . '/autores.php?page=1&limit=10',
                $baseUrl . '/autores.php?search=neruda',
                $baseUrl . '/autores.php/2'
            ]
        ],
        'categorias' => [
            'url' => $baseUrl . '/categorias.php',
            'description' => 'Gestión de categorías',
            'methods' => ['GET'],
            'endpoints' => [
                'GET /categorias.php' => 'Lista todas las categorías (paginado)',
                'GET /categorias.php/{id}' => 'Obtiene una categoría específica con sus poemas'
            ],
            'params' => [
                'page' => 'Número de página (default: 1)',
                'limit' => 'Elementos por página (default: 20, max: 100)',
                'search' => 'Buscar en nombre y descripción',
                'orden' => 'Ordenar por nombre (asc|desc, default: asc)'
            ],
            'examples' => [
                $baseUrl . '/categorias.php',
                $baseUrl . '/categorias.php/2'
            ]
        ],
        'etiquetas' => [
            'url' => $baseUrl . '/etiquetas.php',
            'description' => 'Gestión de etiquetas',
            'methods' => ['GET'],
            'endpoints' => [
                'GET /etiquetas.php' => 'Lista todas las etiquetas (paginado)',
                'GET /etiquetas.php/{id}' => 'Obtiene una etiqueta específica con sus poemas'
            ],
            'params' => [
                'page' => 'Número de página (default: 1)',
                'limit' => 'Elementos por página (default: 20, max: 100)',
                'search' => 'Buscar en nombre',
                'orden' => 'Ordenar por nombre (asc|desc, default: asc)'
            ],
            'examples' => [
                $baseUrl . '/etiquetas.php',
                $baseUrl . '/etiquetas.php/1'
            ]
        ]
    ],
    'features' => [
        'Solo lectura (GET)' => 'Endpoints seguros para consulta de datos',
        'Paginación' => 'Todos los listados soportan paginación',
        'Filtros avanzados' => 'Búsqueda y filtrado de contenido',
        'CORS habilitado' => 'Compatible con aplicaciones web desde cualquier origen',
        'Respuestas estructuradas' => 'Formato JSON consistente y predecible',
        'Fechas ISO 8601' => 'Compatible con cualquier librería de manejo de fechas'
    ],
    'response_format' => [
        'success' => [
            'success' => true,
            'data' => '...',
            'meta' => [
                'timestamp' => 'ISO 8601',
                'version' => 'v1',
                'pagination' => '...'
            ]
        ],
        'error' => [
            'success' => false,
            'error' => [
                'message' => 'Descripción del error',
                'code' => 400,
                'details' => '...'
            ],
            'meta' => [
                'timestamp' => 'ISO 8601',
                'version' => 'v1'
            ]
        ]
    ],
    'meta' => [
        'timestamp' => date('c'),
        'server_time' => date('Y-m-d H:i:s'),
        'timezone' => date_default_timezone_get()
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>

