<?php
/**
 * API REST para gestión de etiquetas
 * CRUD completo para la entidad etiquetas
 */

require_once '../database.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $db = getDatabase()->getConnection();
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $pathParts = explode('/', trim($path, '/'));
    
    // Obtener ID si está presente en la URL
    $id = null;
    if (count($pathParts) >= 3 && is_numeric($pathParts[2])) {
        $id = intval($pathParts[2]);
    }

    switch ($method) {
        case 'GET':
            if ($id) {
                getEtiqueta($db, $id);
            } else {
                getEtiquetas($db);
            }
            break;
            
        case 'POST':
            createEtiqueta($db);
            break;
            
        case 'PUT':
            if ($id) {
                updateEtiqueta($db, $id);
            } else {
                sendError('ID de etiqueta requerido', 400);
            }
            break;
            
        case 'DELETE':
            if ($id) {
                deleteEtiqueta($db, $id);
            } else {
                sendError('ID de etiqueta requerido', 400);
            }
            break;
            
        default:
            sendError('Método no permitido', 405);
    }

} catch (Exception $e) {
    sendError('Error del servidor: ' . $e->getMessage(), 500);
}

/**
 * Obtiene todas las etiquetas
 */
function getEtiquetas($db) {
    try {
        $query = "
            SELECT 
                e.*,
                COUNT(pe.poema_id) as total_poemas
            FROM etiquetas e
            LEFT JOIN poema_etiquetas pe ON e.id = pe.etiqueta_id
            GROUP BY e.id
            ORDER BY e.nombre ASC
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $etiquetas = $stmt->fetchAll();
        
        sendSuccess($etiquetas);
        
    } catch (PDOException $e) {
        sendError('Error al obtener etiquetas: ' . $e->getMessage(), 500);
    }
}

/**
 * Obtiene una etiqueta específica
 */
function getEtiqueta($db, $id) {
    try {
        $query = "
            SELECT 
                e.*,
                COUNT(pe.poema_id) as total_poemas
            FROM etiquetas e
            LEFT JOIN poema_etiquetas pe ON e.id = pe.etiqueta_id
            WHERE e.id = ?
            GROUP BY e.id
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $etiqueta = $stmt->fetch();
        
        if (!$etiqueta) {
            sendError('Etiqueta no encontrada', 404);
            return;
        }
        
        // Obtener poemas de la etiqueta
        $poemasQuery = "
            SELECT 
                p.id,
                p.titulo,
                p.extracto,
                p.icono,
                p.fecha_creacion,
                a.nombre as autor_nombre,
                c.nombre as categoria_nombre,
                c.color as categoria_color
            FROM poemas p
            JOIN poema_etiquetas pe ON p.id = pe.poema_id
            JOIN autores a ON p.autor_id = a.id
            JOIN categorias c ON p.categoria_id = c.id
            WHERE pe.etiqueta_id = ?
            ORDER BY p.fecha_creacion DESC
        ";
        
        $poemasStmt = $db->prepare($poemasQuery);
        $poemasStmt->execute([$id]);
        $etiqueta['poemas'] = $poemasStmt->fetchAll();
        
        sendSuccess($etiqueta);
        
    } catch (PDOException $e) {
        sendError('Error al obtener etiqueta: ' . $e->getMessage(), 500);
    }
}

/**
 * Crea una nueva etiqueta
 */
function createEtiqueta($db) {
    try {
        $data = getJsonData();
        
        // Validar datos requeridos
        if (empty($data['nombre'])) {
            sendError('El nombre de la etiqueta es requerido', 400);
            return;
        }
        
        // Validar longitud del nombre
        if (strlen($data['nombre']) > 50) {
            sendError('El nombre de la etiqueta no puede exceder 50 caracteres', 400);
            return;
        }
        
        // Verificar si la etiqueta ya existe
        $checkQuery = "SELECT id FROM etiquetas WHERE nombre = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$data['nombre']]);
        
        if ($checkStmt->fetch()) {
            sendError('Ya existe una etiqueta con ese nombre', 409);
            return;
        }
        
        // Insertar nueva etiqueta
        $query = "INSERT INTO etiquetas (nombre) VALUES (?)";
        $stmt = $db->prepare($query);
        $stmt->execute([trim($data['nombre'])]);
        
        $newId = $db->lastInsertId();
        
        // Obtener la etiqueta creada
        $etiquetaQuery = "SELECT * FROM etiquetas WHERE id = ?";
        $etiquetaStmt = $db->prepare($etiquetaQuery);
        $etiquetaStmt->execute([$newId]);
        $etiqueta = $etiquetaStmt->fetch();
        
        sendSuccess($etiqueta, 'Etiqueta creada exitosamente', 201);
        
    } catch (PDOException $e) {
        sendError('Error al crear etiqueta: ' . $e->getMessage(), 500);
    }
}

/**
 * Actualiza una etiqueta existente
 */
function updateEtiqueta($db, $id) {
    try {
        // Verificar si la etiqueta existe
        $checkQuery = "SELECT id FROM etiquetas WHERE id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$id]);
        
        if (!$checkStmt->fetch()) {
            sendError('Etiqueta no encontrada', 404);
            return;
        }
        
        $data = getJsonData();
        
        // Validar datos requeridos
        if (empty($data['nombre'])) {
            sendError('El nombre de la etiqueta es requerido', 400);
            return;
        }
        
        // Validar longitud del nombre
        if (strlen($data['nombre']) > 50) {
            sendError('El nombre de la etiqueta no puede exceder 50 caracteres', 400);
            return;
        }
        
        // Verificar si el nombre ya existe en otra etiqueta
        $nameCheckQuery = "SELECT id FROM etiquetas WHERE nombre = ? AND id != ?";
        $nameCheckStmt = $db->prepare($nameCheckQuery);
        $nameCheckStmt->execute([$data['nombre'], $id]);
        
        if ($nameCheckStmt->fetch()) {
            sendError('Ya existe otra etiqueta con ese nombre', 409);
            return;
        }
        
        // Actualizar etiqueta
        $query = "UPDATE etiquetas SET nombre = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([trim($data['nombre']), $id]);
        
        // Obtener la etiqueta actualizada
        $etiquetaQuery = "SELECT * FROM etiquetas WHERE id = ?";
        $etiquetaStmt = $db->prepare($etiquetaQuery);
        $etiquetaStmt->execute([$id]);
        $etiqueta = $etiquetaStmt->fetch();
        
        sendSuccess($etiqueta, 'Etiqueta actualizada exitosamente');
        
    } catch (PDOException $e) {
        sendError('Error al actualizar etiqueta: ' . $e->getMessage(), 500);
    }
}

/**
 * Elimina una etiqueta
 */
function deleteEtiqueta($db, $id) {
    try {
        // Verificar si la etiqueta existe
        $checkQuery = "SELECT id, nombre FROM etiquetas WHERE id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$id]);
        $etiqueta = $checkStmt->fetch();
        
        if (!$etiqueta) {
            sendError('Etiqueta no encontrada', 404);
            return;
        }
        
        // Verificar si tiene poemas asociados
        $poemasQuery = "
            SELECT COUNT(*) 
            FROM poema_etiquetas 
            WHERE etiqueta_id = ?
        ";
        $poemasStmt = $db->prepare($poemasQuery);
        $poemasStmt->execute([$id]);
        $poemasCount = $poemasStmt->fetchColumn();
        
        if ($poemasCount > 0) {
            sendError("No se puede eliminar la etiqueta '{$etiqueta['nombre']}' porque está asociada a {$poemasCount} poemas. Elimine primero las asociaciones.", 409);
            return;
        }
        
        // Eliminar etiqueta
        $deleteQuery = "DELETE FROM etiquetas WHERE id = ?";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->execute([$id]);
        
        sendSuccess(null, 'Etiqueta eliminada exitosamente');
        
    } catch (PDOException $e) {
        sendError('Error al eliminar etiqueta: ' . $e->getMessage(), 500);
    }
}

/**
 * Obtiene datos JSON del request
 */
function getJsonData() {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendError('Datos JSON inválidos', 400);
        exit();
    }
    
    return $data;
}

/**
 * Envía respuesta de éxito
 */
function sendSuccess($data, $message = 'Operación exitosa', $code = 200) {
    http_response_code($code);
    echo json_encode([
        'success' => true,
        'message' => $message,
        'data' => $data
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

/**
 * Envía respuesta de error
 */
function sendError($message, $code = 400) {
    http_response_code($code);
    echo json_encode([
        'success' => false,
        'message' => $message,
        'error_code' => $code
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>
