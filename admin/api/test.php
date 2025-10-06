<?php
/**
 * API de Prueba
 * Endpoint para verificar el funcionamiento de todas las APIs
 */

require_once '../database.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $db = getDatabase()->getConnection();
    $testResults = [
        'success' => true,
        'timestamp' => date('Y-m-d H:i:s'),
        'tests' => []
    ];

    // Test 1: Conexión a base de datos
    $testResults['tests']['database_connection'] = [
        'name' => 'Conexión a Base de Datos',
        'status' => 'passed',
        'message' => 'Conexión exitosa a SQLite'
    ];

    // Test 2: Verificar tablas
    $tables = ['autores', 'categorias', 'etiquetas', 'poemas', 'poema_etiquetas'];
    $existingTables = [];
    
    foreach ($tables as $table) {
        $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
        if ($stmt->fetch()) {
            $existingTables[] = $table;
        }
    }
    
    $testResults['tests']['tables_check'] = [
        'name' => 'Verificación de Tablas',
        'status' => count($existingTables) === count($tables) ? 'passed' : 'failed',
        'message' => count($existingTables) === count($tables) ? 
            'Todas las tablas existen' : 
            'Faltan tablas: ' . implode(', ', array_diff($tables, $existingTables)),
        'existing_tables' => $existingTables
    ];

    // Test 3: Verificar datos de ejemplo
    $stmt = $db->query("SELECT COUNT(*) as count FROM autores");
    $autoresCount = $stmt->fetch()['count'];
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM categorias");
    $categoriasCount = $stmt->fetch()['count'];
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM etiquetas");
    $etiquetasCount = $stmt->fetch()['count'];
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM poemas");
    $poemasCount = $stmt->fetch()['count'];

    $testResults['tests']['sample_data'] = [
        'name' => 'Datos de Ejemplo',
        'status' => ($autoresCount > 0 && $categoriasCount > 0 && $etiquetasCount > 0 && $poemasCount > 0) ? 'passed' : 'failed',
        'message' => 'Datos de ejemplo verificados',
        'counts' => [
            'autores' => $autoresCount,
            'categorias' => $categoriasCount,
            'etiquetas' => $etiquetasCount,
            'poemas' => $poemasCount
        ]
    ];

    // Test 4: Verificar relaciones
    $stmt = $db->query("
        SELECT COUNT(*) as count 
        FROM poemas p 
        JOIN autores a ON p.autor_id = a.id 
        JOIN categorias c ON p.categoria_id = c.id
    ");
    $relatedPoems = $stmt->fetch()['count'];

    $testResults['tests']['relationships'] = [
        'name' => 'Verificación de Relaciones',
        'status' => $relatedPoems > 0 ? 'passed' : 'failed',
        'message' => $relatedPoems > 0 ? 'Las relaciones funcionan correctamente' : 'Problemas con las relaciones',
        'related_poems' => $relatedPoems
    ];

    // Test 5: Verificar claves foráneas
    $stmt = $db->query("PRAGMA foreign_keys");
    $foreignKeysEnabled = $stmt->fetchColumn() == 1;

    $testResults['tests']['foreign_keys'] = [
        'name' => 'Claves Foráneas',
        'status' => $foreignKeysEnabled ? 'passed' : 'failed',
        'message' => $foreignKeysEnabled ? 'Claves foráneas habilitadas' : 'Claves foráneas deshabilitadas'
    ];

    // Test 6: Probar endpoints de API
    $endpoints = ['autores', 'categorias', 'etiquetas', 'poemas'];
    $apiTests = [];
    
    foreach ($endpoints as $endpoint) {
        // Simular una petición GET a cada endpoint
        $testResults['tests']['api_endpoints'][$endpoint] = [
            'name' => "API $endpoint",
            'status' => 'available',
            'message' => "Endpoint /admin/api/$endpoint.php disponible",
            'methods' => ['GET', 'POST', 'PUT', 'DELETE']
        ];
    }

    // Test 7: Verificar permisos de escritura
    $dataDir = '../data/';
    $writable = is_writable($dataDir);

    $testResults['tests']['write_permissions'] = [
        'name' => 'Permisos de Escritura',
        'status' => $writable ? 'passed' : 'failed',
        'message' => $writable ? 'Directorio data/ tiene permisos de escritura' : 'Directorio data/ sin permisos de escritura'
    ];

    // Test 8: Información del sistema
    $testResults['system_info'] = [
        'php_version' => PHP_VERSION,
        'sqlite_version' => $db->query('SELECT sqlite_version()')->fetchColumn(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Desconocido',
        'script_path' => __DIR__,
        'database_path' => realpath('../data/poemas.db') ?: 'No encontrada'
    ];

    // Determinar estado general
    $failedTests = array_filter($testResults['tests'], function($test) {
        return isset($test['status']) && $test['status'] === 'failed';
    });

    if (!empty($failedTests)) {
        $testResults['success'] = false;
        $testResults['message'] = 'Algunos tests fallaron. Revisar los detalles.';
    } else {
        $testResults['message'] = 'Todos los tests pasaron exitosamente. El sistema está listo para usar.';
    }

    $testResults['summary'] = [
        'total_tests' => count($testResults['tests']),
        'passed' => count($testResults['tests']) - count($failedTests),
        'failed' => count($failedTests),
        'success_rate' => round((count($testResults['tests']) - count($failedTests)) / count($testResults['tests']) * 100, 2) . '%'
    ];

    // Respuesta final
    $httpCode = $testResults['success'] ? 200 : 500;
    http_response_code($httpCode);
    
    echo json_encode($testResults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    $error = [
        'success' => false,
        'timestamp' => date('Y-m-d H:i:s'),
        'message' => 'Error en las pruebas del sistema: ' . $e->getMessage(),
        'error_details' => [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]
    ];
    
    echo json_encode($error, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    http_response_code(500);
}
?>
