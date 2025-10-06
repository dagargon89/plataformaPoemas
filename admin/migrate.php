<?php
/**
 * Script de migración para inicializar la base de datos
 * Sistema de Poemas Dinámico
 */

require_once 'database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = new Database();
    $result = [
        'success' => true,
        'message' => 'Migración completada exitosamente',
        'steps' => []
    ];

    // Paso 1: Crear tablas
    $db->createTables();
    $result['steps'][] = '✅ Tablas creadas correctamente';

    // Paso 2: Insertar datos de ejemplo
    $db->insertSampleData();
    $result['steps'][] = '✅ Datos de ejemplo insertados';

    // Paso 3: Verificar estado
    $status = $db->checkDatabaseStatus();
    $result['database_status'] = $status;

    if ($status['database_exists'] && $status['tables_created'] && $status['data_inserted']) {
        $result['steps'][] = '✅ Base de datos verificada y lista para usar';
    } else {
        $result['success'] = false;
        $result['message'] = 'Error en la verificación de la base de datos';
    }

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    $error = [
        'success' => false,
        'message' => 'Error en la migración: ' . $e->getMessage(),
        'error_details' => $e->getTraceAsString()
    ];
    
    echo json_encode($error, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    http_response_code(500);
}
?>
