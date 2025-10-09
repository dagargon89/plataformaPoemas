<?php
/**
 * API RESTful Pública v1 - Categorías
 * Endpoint de solo lectura para consumo externo
 * 
 * Endpoints:
 * - GET /api/v1/categorias.php - Lista todas las categorías (paginado)
 * - GET /api/v1/categorias.php/{id} - Obtiene una categoría específica con sus poemas
 * 
 * Parámetros de consulta:
 * - page: Número de página (default: 1)
 * - limit: Elementos por página (default: 20, max: 100)
 * - search: Buscar en nombre y descripción
 * - orden: Ordenar por nombre (asc|desc, default: asc)
 */

require_once __DIR__ . '/base.php';

try {
    validateGetMethod();
    
    $db = getDatabase()->getConnection();
    $id = getIdFromUrl();
    
    if ($id) {
        // Obtener una categoría específica
        getCategoria($db, $id);
    } else {
        // Obtener lista de categorías
        getCategorias($db);
    }
    
} catch (Exception $e) {
    sendJsonError('Error interno del servidor', 500, ['message' => $e->getMessage()]);
}

/**
 * Obtiene lista de categorías con paginación
 */
function getCategorias($db) {
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
            $whereConditions[] = "(c.nombre LIKE ? OR c.descripcion LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        // Contar total de registros
        $countQuery = "SELECT COUNT(*) FROM categorias c {$whereClause}";
        $countStmt = $db->prepare($countQuery);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        // Obtener categorías paginadas
        $query = "
            SELECT 
                c.id,
                c.nombre,
                c.icono,
                c.color,
                c.descripcion,
                c.fecha_creacion,
                c.fecha_actualizacion,
                COUNT(p.id) as total_poemas
            FROM categorias c
            LEFT JOIN poemas p ON c.id = p.categoria_id
            {$whereClause}
            GROUP BY c.id
            ORDER BY c.nombre {$orden}
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
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formatear respuesta
        $categorias = array_map(function($categoria) {
            return [
                'id' => (int)$categoria['id'],
                'nombre' => $categoria['nombre'],
                'icono' => $categoria['icono'],
                'color' => $categoria['color'],
                'descripcion' => $categoria['descripcion'],
                'total_poemas' => (int)$categoria['total_poemas'],
                'fechas' => [
                    'creacion' => formatDate($categoria['fecha_creacion']),
                    'actualizacion' => formatDate($categoria['fecha_actualizacion'])
                ]
            ];
        }, $categorias);
        
        // Preparar metadatos
        $meta = getPaginationMeta($total, $pagination['page'], $pagination['limit']);
        
        if ($search) {
            $meta['filters'] = ['search' => $search];
        }
        
        sendJsonResponse($categorias, $meta);
        
    } catch (PDOException $e) {
        sendJsonError('Error al obtener categorías', 500, ['database_error' => $e->getMessage()]);
    }
}

/**
 * Obtiene una categoría específica con sus poemas
 */
function getCategoria($db, $id) {
    try {
        // Obtener información de la categoría
        $query = "
            SELECT 
                c.id,
                c.nombre,
                c.icono,
                c.color,
                c.descripcion,
                c.fecha_creacion,
                c.fecha_actualizacion,
                COUNT(p.id) as total_poemas
            FROM categorias c
            LEFT JOIN poemas p ON c.id = p.categoria_id
            WHERE c.id = ?
            GROUP BY c.id
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$categoria) {
            sendJsonError('Categoría no encontrada', 404);
            return;
        }
        
        // Obtener poemas de la categoría
        $poemasQuery = "
            SELECT 
                p.id,
                p.titulo,
                p.icono,
                p.extracto,
                p.tiempo_lectura,
                p.fecha_creacion,
                a.id as autor_id,
                a.nombre as autor_nombre
            FROM poemas p
            JOIN autores a ON p.autor_id = a.id
            WHERE p.categoria_id = ?
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
                ]
            ];
        }, $poemas);
        
        // Formatear respuesta
        $response = [
            'id' => (int)$categoria['id'],
            'nombre' => $categoria['nombre'],
            'icono' => $categoria['icono'],
            'color' => $categoria['color'],
            'descripcion' => $categoria['descripcion'],
            'total_poemas' => (int)$categoria['total_poemas'],
            'poemas' => $poemas,
            'fechas' => [
                'creacion' => formatDate($categoria['fecha_creacion']),
                'actualizacion' => formatDate($categoria['fecha_actualizacion'])
            ]
        ];
        
        sendJsonResponse($response);
        
    } catch (PDOException $e) {
        sendJsonError('Error al obtener categoría', 500, ['database_error' => $e->getMessage()]);
    }
}
?>

