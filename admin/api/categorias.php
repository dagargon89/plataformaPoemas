<?php
/**
 * API REST para gestión de categorías
 * CRUD completo para la entidad categorias
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
                getCategoria($db, $id);
            } else {
                getCategorias($db);
            }
            break;
            
        case 'POST':
            createCategoria($db);
            break;
            
        case 'PUT':
            if ($id) {
                updateCategoria($db, $id);
            } else {
                sendError('ID de categoría requerido', 400);
            }
            break;
            
        case 'DELETE':
            if ($id) {
                deleteCategoria($db, $id);
            } else {
                sendError('ID de categoría requerido', 400);
            }
            break;
            
        default:
            sendError('Método no permitido', 405);
    }

} catch (Exception $e) {
    sendError('Error del servidor: ' . $e->getMessage(), 500);
}

/**
 * Obtiene todas las categorías
 */
function getCategorias($db) {
    try {
        $query = "
            SELECT 
                c.*,
                COUNT(p.id) as total_poemas
            FROM categorias c
            LEFT JOIN poemas p ON c.id = p.categoria_id
            GROUP BY c.id
            ORDER BY c.nombre ASC
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $categorias = $stmt->fetchAll();
        
        sendSuccess($categorias);
        
    } catch (PDOException $e) {
        sendError('Error al obtener categorías: ' . $e->getMessage(), 500);
    }
}

/**
 * Obtiene una categoría específica
 */
function getCategoria($db, $id) {
    try {
        $query = "
            SELECT 
                c.*,
                COUNT(p.id) as total_poemas
            FROM categorias c
            LEFT JOIN poemas p ON c.id = p.categoria_id
            WHERE c.id = ?
            GROUP BY c.id
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $categoria = $stmt->fetch();
        
        if (!$categoria) {
            sendError('Categoría no encontrada', 404);
            return;
        }
        
        // Obtener poemas de la categoría
        $poemasQuery = "
            SELECT 
                p.id,
                p.titulo,
                p.extracto,
                p.icono,
                p.fecha_creacion,
                a.nombre as autor_nombre
            FROM poemas p
            JOIN autores a ON p.autor_id = a.id
            WHERE p.categoria_id = ?
            ORDER BY p.fecha_creacion DESC
        ";
        
        $poemasStmt = $db->prepare($poemasQuery);
        $poemasStmt->execute([$id]);
        $categoria['poemas'] = $poemasStmt->fetchAll();
        
        sendSuccess($categoria);
        
    } catch (PDOException $e) {
        sendError('Error al obtener categoría: ' . $e->getMessage(), 500);
    }
}

/**
 * Crea una nueva categoría
 */
function createCategoria($db) {
    try {
        $data = getJsonData();
        
        // Validar datos requeridos
        if (empty($data['nombre'])) {
            sendError('El nombre de la categoría es requerido', 400);
            return;
        }
        
        // Validar longitud del nombre
        if (strlen($data['nombre']) > 50) {
            sendError('El nombre de la categoría no puede exceder 50 caracteres', 400);
            return;
        }
        
        // Verificar si la categoría ya existe
        $checkQuery = "SELECT id FROM categorias WHERE nombre = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$data['nombre']]);
        
        if ($checkStmt->fetch()) {
            sendError('Ya existe una categoría con ese nombre', 409);
            return;
        }
        
        // Validar color si se proporciona
        if (isset($data['color']) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $data['color'])) {
            sendError('El color debe estar en formato hexadecimal (#RRGGBB)', 400);
            return;
        }
        
        // Insertar nueva categoría
        $query = "INSERT INTO categorias (nombre, icono, color, descripcion) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            trim($data['nombre']),
            isset($data['icono']) ? trim($data['icono']) : null,
            isset($data['color']) ? trim($data['color']) : null,
            isset($data['descripcion']) ? trim($data['descripcion']) : null
        ]);
        
        $newId = $db->lastInsertId();
        
        // Obtener la categoría creada
        $categoriaQuery = "SELECT * FROM categorias WHERE id = ?";
        $categoriaStmt = $db->prepare($categoriaQuery);
        $categoriaStmt->execute([$newId]);
        $categoria = $categoriaStmt->fetch();
        
        sendSuccess($categoria, 'Categoría creada exitosamente', 201);
        
    } catch (PDOException $e) {
        sendError('Error al crear categoría: ' . $e->getMessage(), 500);
    }
}

/**
 * Actualiza una categoría existente
 */
function updateCategoria($db, $id) {
    try {
        // Verificar si la categoría existe
        $checkQuery = "SELECT id FROM categorias WHERE id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$id]);
        
        if (!$checkStmt->fetch()) {
            sendError('Categoría no encontrada', 404);
            return;
        }
        
        $data = getJsonData();
        
        // Validar datos requeridos
        if (empty($data['nombre'])) {
            sendError('El nombre de la categoría es requerido', 400);
            return;
        }
        
        // Validar longitud del nombre
        if (strlen($data['nombre']) > 50) {
            sendError('El nombre de la categoría no puede exceder 50 caracteres', 400);
            return;
        }
        
        // Verificar si el nombre ya existe en otra categoría
        $nameCheckQuery = "SELECT id FROM categorias WHERE nombre = ? AND id != ?";
        $nameCheckStmt = $db->prepare($nameCheckQuery);
        $nameCheckStmt->execute([$data['nombre'], $id]);
        
        if ($nameCheckStmt->fetch()) {
            sendError('Ya existe otra categoría con ese nombre', 409);
            return;
        }
        
        // Validar color si se proporciona
        if (isset($data['color']) && !empty($data['color']) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $data['color'])) {
            sendError('El color debe estar en formato hexadecimal (#RRGGBB)', 400);
            return;
        }
        
        // Actualizar categoría
        $query = "
            UPDATE categorias 
            SET nombre = ?, 
                icono = ?, 
                color = ?, 
                descripcion = ?, 
                fecha_actualizacion = CURRENT_TIMESTAMP 
            WHERE id = ?
        ";
        $stmt = $db->prepare($query);
        $stmt->execute([
            trim($data['nombre']),
            isset($data['icono']) ? trim($data['icono']) : null,
            isset($data['color']) ? trim($data['color']) : null,
            isset($data['descripcion']) ? trim($data['descripcion']) : null,
            $id
        ]);
        
        // Obtener la categoría actualizada
        $categoriaQuery = "SELECT * FROM categorias WHERE id = ?";
        $categoriaStmt = $db->prepare($categoriaQuery);
        $categoriaStmt->execute([$id]);
        $categoria = $categoriaStmt->fetch();
        
        sendSuccess($categoria, 'Categoría actualizada exitosamente');
        
    } catch (PDOException $e) {
        sendError('Error al actualizar categoría: ' . $e->getMessage(), 500);
    }
}

/**
 * Elimina una categoría
 */
function deleteCategoria($db, $id) {
    try {
        // Verificar si la categoría existe
        $checkQuery = "SELECT id, nombre FROM categorias WHERE id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$id]);
        $categoria = $checkStmt->fetch();
        
        if (!$categoria) {
            sendError('Categoría no encontrada', 404);
            return;
        }
        
        // Verificar si tiene poemas asociados
        $poemasQuery = "SELECT COUNT(*) FROM poemas WHERE categoria_id = ?";
        $poemasStmt = $db->prepare($poemasQuery);
        $poemasStmt->execute([$id]);
        $poemasCount = $poemasStmt->fetchColumn();
        
        if ($poemasCount > 0) {
            sendError("No se puede eliminar la categoría '{$categoria['nombre']}' porque tiene {$poemasCount} poemas asociados. Elimine primero los poemas.", 409);
            return;
        }
        
        // Eliminar categoría
        $deleteQuery = "DELETE FROM categorias WHERE id = ?";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->execute([$id]);
        
        sendSuccess(null, 'Categoría eliminada exitosamente');
        
    } catch (PDOException $e) {
        sendError('Error al eliminar categoría: ' . $e->getMessage(), 500);
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
