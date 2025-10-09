<?php
/**
 * Archivo base para APIs públicas RESTful
 * Funciones comunes y configuración compartida
 */

// Incluir la base de datos del sistema
require_once __DIR__ . '/../../admin/database.php';

// Configuración de headers CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/**
 * Envía una respuesta JSON exitosa con formato estándar
 */
function sendJsonResponse($data, $meta = [], $code = 200) {
    http_response_code($code);
    
    $response = [
        'success' => true,
        'data' => $data,
        'meta' => array_merge([
            'timestamp' => date('c'),
            'version' => 'v1'
        ], $meta)
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

/**
 * Envía una respuesta de error JSON
 */
function sendJsonError($message, $code = 400, $details = []) {
    http_response_code($code);
    
    $response = [
        'success' => false,
        'error' => [
            'message' => $message,
            'code' => $code,
            'details' => $details
        ],
        'meta' => [
            'timestamp' => date('c'),
            'version' => 'v1'
        ]
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

/**
 * Obtiene parámetros de paginación
 */
function getPaginationParams() {
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, min(100, intval($_GET['limit']))) : 20;
    $offset = ($page - 1) * $limit;
    
    return [
        'page' => $page,
        'limit' => $limit,
        'offset' => $offset
    ];
}

/**
 * Calcula metadatos de paginación
 */
function getPaginationMeta($total, $page, $limit) {
    $totalPages = ceil($total / $limit);
    
    return [
        'pagination' => [
            'total_items' => (int)$total,
            'total_pages' => (int)$totalPages,
            'current_page' => (int)$page,
            'items_per_page' => (int)$limit,
            'has_next_page' => $page < $totalPages,
            'has_prev_page' => $page > 1
        ]
    ];
}

/**
 * Obtiene el ID de la URL
 */
function getIdFromUrl() {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $scriptName = basename($_SERVER['SCRIPT_NAME']);
    $pathAfterScript = str_replace('/' . $scriptName, '', $path);
    $pathParts = array_filter(explode('/', $pathAfterScript));
    
    if (!empty($pathParts)) {
        $lastPart = end($pathParts);
        if (is_numeric($lastPart)) {
            return intval($lastPart);
        }
    }
    
    return null;
}

/**
 * Valida que solo se use método GET
 */
function validateGetMethod() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        sendJsonError('Método no permitido. Solo se permiten solicitudes GET.', 405);
    }
}

/**
 * Sanitiza parámetros de búsqueda
 */
function sanitizeSearchParam($param) {
    return isset($_GET[$param]) ? trim($_GET[$param]) : null;
}

/**
 * Convierte string de etiquetas separado por comas a array
 */
function parseTagsString($tagsString) {
    if (empty($tagsString)) {
        return [];
    }
    
    return array_filter(array_map('trim', explode(',', $tagsString)));
}

/**
 * Formatea fecha a ISO 8601
 */
function formatDate($date) {
    if (!$date) return null;
    
    try {
        $dt = new DateTime($date);
        return $dt->format('c');
    } catch (Exception $e) {
        return $date;
    }
}
?>

