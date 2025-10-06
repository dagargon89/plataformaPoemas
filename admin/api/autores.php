<?php
/**
 * API REST para gestión de autores
 * CRUD completo para la entidad autores
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
                getAutor($db, $id);
            } else {
                getAutores($db);
            }
            break;
            
        case 'POST':
            createAutor($db);
            break;
            
        case 'PUT':
            if ($id) {
                updateAutor($db, $id);
            } else {
                sendError('ID de autor requerido', 400);
            }
            break;
            
        case 'DELETE':
            if ($id) {
                deleteAutor($db, $id);
            } else {
                sendError('ID de autor requerido', 400);
            }
            break;
            
        default:
            sendError('Método no permitido', 405);
    }

} catch (Exception $e) {
    sendError('Error del servidor: ' . $e->getMessage(), 500);
}

/**
 * Obtiene todos los autores
 */
function getAutores($db) {
    try {
        $query = "
            SELECT 
                a.*,
                COUNT(p.id) as total_poemas
            FROM autores a
            LEFT JOIN poemas p ON a.id = p.autor_id
            GROUP BY a.id
            ORDER BY a.nombre ASC
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $autores = $stmt->fetchAll();
        
        sendSuccess($autores);
        
    } catch (PDOException $e) {
        sendError('Error al obtener autores: ' . $e->getMessage(), 500);
    }
}

/**
 * Obtiene un autor específico
 */
function getAutor($db, $id) {
    try {
        $query = "
            SELECT 
                a.*,
                COUNT(p.id) as total_poemas
            FROM autores a
            LEFT JOIN poemas p ON a.id = p.autor_id
            WHERE a.id = ?
            GROUP BY a.id
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $autor = $stmt->fetch();
        
        if (!$autor) {
            sendError('Autor no encontrado', 404);
            return;
        }
        
        // Obtener poemas del autor
        $poemasQuery = "
            SELECT 
                p.id,
                p.titulo,
                p.extracto,
                p.fecha_creacion,
                c.nombre as categoria_nombre,
                c.icono as categoria_icono,
                c.color as categoria_color
            FROM poemas p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE p.autor_id = ?
            ORDER BY p.fecha_creacion DESC
        ";
        
        $poemasStmt = $db->prepare($poemasQuery);
        $poemasStmt->execute([$id]);
        $autor['poemas'] = $poemasStmt->fetchAll();
        
        sendSuccess($autor);
        
    } catch (PDOException $e) {
        sendError('Error al obtener autor: ' . $e->getMessage(), 500);
    }
}

/**
 * Crea un nuevo autor
 */
function createAutor($db) {
    try {
        $data = getJsonData();
        
        // Validar datos requeridos
        if (empty($data['nombre'])) {
            sendError('El nombre del autor es requerido', 400);
            return;
        }
        
        // Validar longitud del nombre
        if (strlen($data['nombre']) > 100) {
            sendError('El nombre del autor no puede exceder 100 caracteres', 400);
            return;
        }
        
        // Verificar si el autor ya existe
        $checkQuery = "SELECT id FROM autores WHERE nombre = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$data['nombre']]);
        
        if ($checkStmt->fetch()) {
            sendError('Ya existe un autor con ese nombre', 409);
            return;
        }
        
        // Insertar nuevo autor
        $query = "INSERT INTO autores (nombre, biografia) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            trim($data['nombre']),
            isset($data['biografia']) ? trim($data['biografia']) : null
        ]);
        
        $newId = $db->lastInsertId();
        
        // Obtener el autor creado
        $autorQuery = "SELECT * FROM autores WHERE id = ?";
        $autorStmt = $db->prepare($autorQuery);
        $autorStmt->execute([$newId]);
        $autor = $autorStmt->fetch();
        
        sendSuccess($autor, 'Autor creado exitosamente', 201);
        
    } catch (PDOException $e) {
        sendError('Error al crear autor: ' . $e->getMessage(), 500);
    }
}

/**
 * Actualiza un autor existente
 */
function updateAutor($db, $id) {
    try {
        // Verificar si el autor existe
        $checkQuery = "SELECT id FROM autores WHERE id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$id]);
        
        if (!$checkStmt->fetch()) {
            sendError('Autor no encontrado', 404);
            return;
        }
        
        $data = getJsonData();
        
        // Validar datos requeridos
        if (empty($data['nombre'])) {
            sendError('El nombre del autor es requerido', 400);
            return;
        }
        
        // Validar longitud del nombre
        if (strlen($data['nombre']) > 100) {
            sendError('El nombre del autor no puede exceder 100 caracteres', 400);
            return;
        }
        
        // Verificar si el nombre ya existe en otro autor
        $nameCheckQuery = "SELECT id FROM autores WHERE nombre = ? AND id != ?";
        $nameCheckStmt = $db->prepare($nameCheckQuery);
        $nameCheckStmt->execute([$data['nombre'], $id]);
        
        if ($nameCheckStmt->fetch()) {
            sendError('Ya existe otro autor con ese nombre', 409);
            return;
        }
        
        // Actualizar autor
        $query = "
            UPDATE autores 
            SET nombre = ?, 
                biografia = ?, 
                fecha_actualizacion = CURRENT_TIMESTAMP 
            WHERE id = ?
        ";
        $stmt = $db->prepare($query);
        $stmt->execute([
            trim($data['nombre']),
            isset($data['biografia']) ? trim($data['biografia']) : null,
            $id
        ]);
        
        // Obtener el autor actualizado
        $autorQuery = "SELECT * FROM autores WHERE id = ?";
        $autorStmt = $db->prepare($autorQuery);
        $autorStmt->execute([$id]);
        $autor = $autorStmt->fetch();
        
        sendSuccess($autor, 'Autor actualizado exitosamente');
        
    } catch (PDOException $e) {
        sendError('Error al actualizar autor: ' . $e->getMessage(), 500);
    }
}

/**
 * Elimina un autor
 */
function deleteAutor($db, $id) {
    try {
        // Verificar si el autor existe
        $checkQuery = "SELECT id, nombre FROM autores WHERE id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$id]);
        $autor = $checkStmt->fetch();
        
        if (!$autor) {
            sendError('Autor no encontrado', 404);
            return;
        }
        
        // Verificar si tiene poemas asociados
        $poemasQuery = "SELECT COUNT(*) FROM poemas WHERE autor_id = ?";
        $poemasStmt = $db->prepare($poemasQuery);
        $poemasStmt->execute([$id]);
        $poemasCount = $poemasStmt->fetchColumn();
        
        if ($poemasCount > 0) {
            sendError("No se puede eliminar el autor '{$autor['nombre']}' porque tiene {$poemasCount} poemas asociados. Elimine primero los poemas.", 409);
            return;
        }
        
        // Eliminar autor
        $deleteQuery = "DELETE FROM autores WHERE id = ?";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->execute([$id]);
        
        sendSuccess(null, 'Autor eliminado exitosamente');
        
    } catch (PDOException $e) {
        sendError('Error al eliminar autor: ' . $e->getMessage(), 500);
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
