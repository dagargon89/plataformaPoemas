<?php
/**
 * API RESTful Pública v1 - Etiquetas
 * Endpoint de solo lectura para consumo externo
 * 
 * Endpoints:
 * - GET /api/v1/etiquetas.php - Lista todas las etiquetas (paginado)
 * - GET /api/v1/etiquetas.php/{id} - Obtiene una etiqueta específica con sus poemas
 * 
 * Parámetros de consulta:
 * - page: Número de página (default: 1)
 * - limit: Elementos por página (default: 20, max: 100)
 * - search: Buscar en nombre
 * - orden: Ordenar por nombre (asc|desc, default: asc)
 */

require_once __DIR__ . '/base.php';

try {
    validateGetMethod();
    
    $db = getDatabase()->getConnection();
    $id = getIdFromUrl();
    
    if ($id) {
        // Obtener una etiqueta específica
        getEtiqueta($db, $id);
    } else {
        // Obtener lista de etiquetas
        getEtiquetas($db);
    }
    
} catch (Exception $e) {
    sendJsonError('Error interno del servidor', 500, ['message' => $e->getMessage()]);
}

/**
 * Obtiene lista de etiquetas con paginación
 */
function getEtiquetas($db) {
    try {
        // Obtener parámetros de paginación
        $pagination = getPaginationParams();
        
        // Obtener parámetros de filtro
        $search = sanitizeSearchParam('search');
        $orden = isset($_GET['orden']) && strtolower($_GET['orden']) === 'desc' ? 'DESC' : 'ASC';
        
        // Construir query
        $whereConditions = [];
        $params = [];
        
        if ($search) {
            $whereConditions[] = "e.nombre LIKE ?";
            $params[] = "%{$search}%";
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        // Contar total de registros
        $countQuery = "SELECT COUNT(*) FROM etiquetas e {$whereClause}";
        $countStmt = $db->prepare($countQuery);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        // Obtener etiquetas paginadas
        $query = "
            SELECT 
                e.id,
                e.nombre,
                e.fecha_creacion,
                COUNT(pe.poema_id) as total_poemas
            FROM etiquetas e
            LEFT JOIN poema_etiquetas pe ON e.id = pe.etiqueta_id
            {$whereClause}
            GROUP BY e.id
            ORDER BY e.nombre {$orden}
            LIMIT :limit OFFSET :offset
        ";
        
        $stmt = $db->prepare($query);
        
        // Bind de parámetros de filtros
        $paramIndex = 1;
        foreach ($params as $param) {
            $stmt->bindValue($paramIndex++, $param);
        }
        
        // Bind de parámetros de paginación como enteros
        $stmt->bindValue(':limit', $pagination['limit'], PDO::PARAM_INT);
        $stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
        
        $stmt->execute();
        $etiquetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formatear respuesta
        $etiquetas = array_map(function($etiqueta) {
            return [
                'id' => (int)$etiqueta['id'],
                'nombre' => $etiqueta['nombre'],
                'total_poemas' => (int)$etiqueta['total_poemas'],
                'fecha_creacion' => formatDate($etiqueta['fecha_creacion'])
            ];
        }, $etiquetas);
        
        // Preparar metadatos
        $meta = getPaginationMeta($total, $pagination['page'], $pagination['limit']);
        
        if ($search) {
            $meta['filters'] = ['search' => $search];
        }
        
        sendJsonResponse($etiquetas, $meta);
        
    } catch (PDOException $e) {
        sendJsonError('Error al obtener etiquetas', 500, ['database_error' => $e->getMessage()]);
    }
}

/**
 * Obtiene una etiqueta específica con sus poemas
 */
function getEtiqueta($db, $id) {
    try {
        // Obtener información de la etiqueta
        $query = "
            SELECT 
                e.id,
                e.nombre,
                e.fecha_creacion,
                COUNT(pe.poema_id) as total_poemas
            FROM etiquetas e
            LEFT JOIN poema_etiquetas pe ON e.id = pe.etiqueta_id
            WHERE e.id = ?
            GROUP BY e.id
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $etiqueta = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$etiqueta) {
            sendJsonError('Etiqueta no encontrada', 404);
            return;
        }
        
        // Obtener poemas de la etiqueta
        $poemasQuery = "
            SELECT 
                p.id,
                p.titulo,
                p.icono,
                p.extracto,
                p.tiempo_lectura,
                p.fecha_creacion,
                a.id as autor_id,
                a.nombre as autor_nombre,
                c.id as categoria_id,
                c.nombre as categoria_nombre,
                c.icono as categoria_icono,
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
        $poemas = $poemasStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formatear poemas
        $poemas = array_map(function($poema) {
            return [
                'id' => (int)$poema['id'],
                'titulo' => $poema['titulo'],
                'icono' => $poema['icono'],
                'extracto' => $poema['extracto'],
                'tiempo_lectura' => (int)$poema['tiempo_lectura'],
                'fecha_creacion' => formatDate($poema['fecha_creacion']),
                'autor' => [
                    'id' => (int)$poema['autor_id'],
                    'nombre' => $poema['autor_nombre']
                ],
                'categoria' => [
                    'id' => (int)$poema['categoria_id'],
                    'nombre' => $poema['categoria_nombre'],
                    'icono' => $poema['categoria_icono'],
                    'color' => $poema['categoria_color']
                ]
            ];
        }, $poemas);
        
        // Formatear respuesta
        $response = [
            'id' => (int)$etiqueta['id'],
            'nombre' => $etiqueta['nombre'],
            'total_poemas' => (int)$etiqueta['total_poemas'],
            'poemas' => $poemas,
            'fecha_creacion' => formatDate($etiqueta['fecha_creacion'])
        ];
        
        sendJsonResponse($response);
        
    } catch (PDOException $e) {
        sendJsonError('Error al obtener etiqueta', 500, ['database_error' => $e->getMessage()]);
    }
}
?>

