<?php
/**
 * API REST para gestión de poemas
 * CRUD completo para la entidad poemas con relaciones
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
    
    // Obtener ID de la URL - múltiples métodos
    $id = null;
    
    // Método 1: Desde pathParts (para URLs como /admin/api/poemas.php/1)
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $pathParts = explode('/', trim($path, '/'));
    if (count($pathParts) >= 3 && is_numeric($pathParts[2])) {
        $id = intval($pathParts[2]);
    }
    
    // Método 2: Desde parámetro GET (para URLs como /admin/api/poemas.php?id=1)
    if (!$id && isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = intval($_GET['id']);
    }
    
    // Método 3: Desde el final de la URL (para URLs como /poemas.php/1)
    if (!$id) {
        $scriptName = basename($_SERVER['SCRIPT_NAME']);
        $pathAfterScript = str_replace('/' . $scriptName, '', $path);
        $pathAfterScript = trim($pathAfterScript, '/');
        if (is_numeric($pathAfterScript)) {
            $id = intval($pathAfterScript);
        }
    }

    switch ($method) {
        case 'GET':
            if ($id) {
                getPoema($db, $id);
            } else {
                getPoemas($db);
            }
            break;
            
        case 'POST':
            createPoema($db);
            break;
            
        case 'PUT':
            if ($id) {
                updatePoema($db, $id);
            } else {
                sendError('ID de poema requerido', 400);
            }
            break;
            
        case 'DELETE':
            if ($id) {
                deletePoema($db, $id);
            } else {
                sendError('ID de poema requerido', 400);
            }
            break;
            
        default:
            sendError('Método no permitido', 405);
    }

} catch (Exception $e) {
    sendError('Error del servidor: ' . $e->getMessage(), 500);
}

/**
 * Obtiene todos los poemas con información relacionada
 */
function getPoemas($db) {
    try {
        $query = "
            SELECT 
                p.*,
                a.nombre as autor_nombre,
                c.nombre as categoria_nombre,
                c.icono as categoria_icono,
                c.color as categoria_color,
                GROUP_CONCAT(e.nombre, ',') as etiquetas
            FROM poemas p
            JOIN autores a ON p.autor_id = a.id
            JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN poema_etiquetas pe ON p.id = pe.poema_id
            LEFT JOIN etiquetas e ON pe.etiqueta_id = e.id
            GROUP BY p.id
            ORDER BY p.fecha_creacion DESC
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $poemas = $stmt->fetchAll();
        
        // Procesar etiquetas para cada poema
        foreach ($poemas as &$poema) {
            $poema['etiquetas'] = $poema['etiquetas'] ? explode(',', $poema['etiquetas']) : [];
        }
        
        sendSuccess($poemas);
        
    } catch (PDOException $e) {
        sendError('Error al obtener poemas: ' . $e->getMessage(), 500);
    }
}

/**
 * Obtiene un poema específico con toda su información
 */
function getPoema($db, $id) {
    try {
        $query = "
            SELECT 
                p.*,
                a.nombre as autor_nombre,
                a.biografia as autor_biografia,
                c.nombre as categoria_nombre,
                c.icono as categoria_icono,
                c.color as categoria_color,
                c.descripcion as categoria_descripcion
            FROM poemas p
            JOIN autores a ON p.autor_id = a.id
            JOIN categorias c ON p.categoria_id = c.id
            WHERE p.id = ?
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $poema = $stmt->fetch();
        
        if (!$poema) {
            sendError('Poema no encontrado', 404);
            return;
        }
        
        // Obtener etiquetas del poema
        $etiquetasQuery = "
            SELECT e.id, e.nombre
            FROM etiquetas e
            JOIN poema_etiquetas pe ON e.id = pe.etiqueta_id
            WHERE pe.poema_id = ?
            ORDER BY e.nombre ASC
        ";
        
        $etiquetasStmt = $db->prepare($etiquetasQuery);
        $etiquetasStmt->execute([$id]);
        $poema['etiquetas'] = $etiquetasStmt->fetchAll();
        
        sendSuccess($poema);
        
    } catch (PDOException $e) {
        sendError('Error al obtener poema: ' . $e->getMessage(), 500);
    }
}

/**
 * Crea un nuevo poema
 */
function createPoema($db) {
    try {
        $db->beginTransaction();
        
        $data = getJsonData();
        
        // Validar datos requeridos
        $errors = validatePoemaData($data);
        if (!empty($errors)) {
            $db->rollback();
            sendError('Datos inválidos: ' . implode(', ', $errors), 400);
            return;
        }
        
        // Verificar que el autor existe
        $autorQuery = "SELECT id FROM autores WHERE id = ?";
        $autorStmt = $db->prepare($autorQuery);
        $autorStmt->execute([$data['autor_id']]);
        if (!$autorStmt->fetch()) {
            $db->rollback();
            sendError('El autor especificado no existe', 400);
            return;
        }
        
        // Verificar que la categoría existe
        $categoriaQuery = "SELECT id FROM categorias WHERE id = ?";
        $categoriaStmt = $db->prepare($categoriaQuery);
        $categoriaStmt->execute([$data['categoria_id']]);
        if (!$categoriaStmt->fetch()) {
            $db->rollback();
            sendError('La categoría especificada no existe', 400);
            return;
        }
        
        // Insertar nuevo poema
        $query = "
            INSERT INTO poemas (
                titulo, autor_id, categoria_id, icono, extracto, 
                contenido, tiempo_lectura
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $db->prepare($query);
        $stmt->execute([
            trim($data['titulo']),
            $data['autor_id'],
            $data['categoria_id'],
            isset($data['icono']) ? trim($data['icono']) : null,
            isset($data['extracto']) ? trim($data['extracto']) : null,
            trim($data['contenido']),
            isset($data['tiempo_lectura']) ? intval($data['tiempo_lectura']) : 2
        ]);
        
        $newId = $db->lastInsertId();
        
        // Insertar etiquetas si se proporcionan
        if (isset($data['etiquetas']) && is_array($data['etiquetas'])) {
            $etiquetasQuery = "INSERT INTO poema_etiquetas (poema_id, etiqueta_id) VALUES (?, ?)";
            $etiquetasStmt = $db->prepare($etiquetasQuery);
            
            foreach ($data['etiquetas'] as $etiquetaId) {
                // Verificar que la etiqueta existe
                $checkEtiqueta = "SELECT id FROM etiquetas WHERE id = ?";
                $checkStmt = $db->prepare($checkEtiqueta);
                $checkStmt->execute([$etiquetaId]);
                
                if ($checkStmt->fetch()) {
                    $etiquetasStmt->execute([$newId, $etiquetaId]);
                }
            }
        }
        
        $db->commit();
        
        // Obtener el poema creado con toda su información
        getPoema($db, $newId);
        
    } catch (PDOException $e) {
        $db->rollback();
        sendError('Error al crear poema: ' . $e->getMessage(), 500);
    }
}

/**
 * Actualiza un poema existente
 */
function updatePoema($db, $id) {
    try {
        $db->beginTransaction();
        
        // Verificar si el poema existe
        $checkQuery = "SELECT id FROM poemas WHERE id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$id]);
        
        if (!$checkStmt->fetch()) {
            $db->rollback();
            sendError('Poema no encontrado', 404);
            return;
        }
        
        $data = getJsonData();
        
        // Validar datos requeridos
        $errors = validatePoemaData($data);
        if (!empty($errors)) {
            $db->rollback();
            sendError('Datos inválidos: ' . implode(', ', $errors), 400);
            return;
        }
        
        // Verificar que el autor existe
        $autorQuery = "SELECT id FROM autores WHERE id = ?";
        $autorStmt = $db->prepare($autorQuery);
        $autorStmt->execute([$data['autor_id']]);
        if (!$autorStmt->fetch()) {
            $db->rollback();
            sendError('El autor especificado no existe', 400);
            return;
        }
        
        // Verificar que la categoría existe
        $categoriaQuery = "SELECT id FROM categorias WHERE id = ?";
        $categoriaStmt = $db->prepare($categoriaQuery);
        $categoriaStmt->execute([$data['categoria_id']]);
        if (!$categoriaStmt->fetch()) {
            $db->rollback();
            sendError('La categoría especificada no existe', 400);
            return;
        }
        
        // Actualizar poema
        $query = "
            UPDATE poemas 
            SET titulo = ?, 
                autor_id = ?, 
                categoria_id = ?, 
                icono = ?, 
                extracto = ?, 
                contenido = ?, 
                tiempo_lectura = ?, 
                fecha_actualizacion = CURRENT_TIMESTAMP 
            WHERE id = ?
        ";
        $stmt = $db->prepare($query);
        $stmt->execute([
            trim($data['titulo']),
            $data['autor_id'],
            $data['categoria_id'],
            isset($data['icono']) ? trim($data['icono']) : null,
            isset($data['extracto']) ? trim($data['extracto']) : null,
            trim($data['contenido']),
            isset($data['tiempo_lectura']) ? intval($data['tiempo_lectura']) : 2,
            $id
        ]);
        
        // Actualizar etiquetas
        // Primero eliminar todas las etiquetas existentes
        $deleteEtiquetas = "DELETE FROM poema_etiquetas WHERE poema_id = ?";
        $deleteStmt = $db->prepare($deleteEtiquetas);
        $deleteStmt->execute([$id]);
        
        // Insertar nuevas etiquetas si se proporcionan
        if (isset($data['etiquetas']) && is_array($data['etiquetas'])) {
            $etiquetasQuery = "INSERT INTO poema_etiquetas (poema_id, etiqueta_id) VALUES (?, ?)";
            $etiquetasStmt = $db->prepare($etiquetasQuery);
            
            foreach ($data['etiquetas'] as $etiquetaId) {
                // Verificar que la etiqueta existe
                $checkEtiqueta = "SELECT id FROM etiquetas WHERE id = ?";
                $checkStmt = $db->prepare($checkEtiqueta);
                $checkStmt->execute([$etiquetaId]);
                
                if ($checkStmt->fetch()) {
                    $etiquetasStmt->execute([$id, $etiquetaId]);
                }
            }
        }
        
        $db->commit();
        
        // Obtener el poema actualizado con toda su información
        getPoema($db, $id);
        
    } catch (PDOException $e) {
        $db->rollback();
        sendError('Error al actualizar poema: ' . $e->getMessage(), 500);
    }
}

/**
 * Elimina un poema
 */
function deletePoema($db, $id) {
    try {
        // Verificar si el poema existe
        $checkQuery = "SELECT id, titulo FROM poemas WHERE id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$id]);
        $poema = $checkStmt->fetch();
        
        if (!$poema) {
            sendError('Poema no encontrado', 404);
            return;
        }
        
        // Eliminar poema (las relaciones se eliminan automáticamente por CASCADE)
        $deleteQuery = "DELETE FROM poemas WHERE id = ?";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->execute([$id]);
        
        sendSuccess(null, 'Poema eliminado exitosamente');
        
    } catch (PDOException $e) {
        sendError('Error al eliminar poema: ' . $e->getMessage(), 500);
    }
}

/**
 * Valida los datos de un poema
 */
function validatePoemaData($data) {
    $errors = [];
    
    if (empty($data['titulo'])) {
        $errors[] = 'El título es requerido';
    } elseif (strlen($data['titulo']) > 200) {
        $errors[] = 'El título no puede exceder 200 caracteres';
    }
    
    if (empty($data['contenido'])) {
        $errors[] = 'El contenido es requerido';
    }
    
    if (!isset($data['autor_id']) || !is_numeric($data['autor_id'])) {
        $errors[] = 'El ID del autor es requerido y debe ser numérico';
    }
    
    if (!isset($data['categoria_id']) || !is_numeric($data['categoria_id'])) {
        $errors[] = 'El ID de la categoría es requerido y debe ser numérico';
    }
    
    if (isset($data['tiempo_lectura']) && (!is_numeric($data['tiempo_lectura']) || $data['tiempo_lectura'] < 1)) {
        $errors[] = 'El tiempo de lectura debe ser un número mayor a 0';
    }
    
    return $errors;
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
