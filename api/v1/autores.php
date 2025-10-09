<?php
/**
 * API RESTful Pública v1 - Autores
 * Endpoint de solo lectura para consumo externo
 * 
 * Endpoints:
 * - GET /api/v1/autores.php - Lista todos los autores (paginado)
 * - GET /api/v1/autores.php/{id} - Obtiene un autor específico con sus poemas
 * 
 * Parámetros de consulta:
 * - page: Número de página (default: 1)
 * - limit: Elementos por página (default: 20, max: 100)
 * - search: Buscar en nombre y biografía
 * - orden: Ordenar por nombre (asc|desc, default: asc)
 */

require_once __DIR__ . '/base.php';

try {
    validateGetMethod();
    
    $db = getDatabase()->getConnection();
    $id = getIdFromUrl();
    
    if ($id) {
        // Obtener un autor específico
        getAutor($db, $id);
    } else {
        // Obtener lista de autores
        getAutores($db);
    }
    
} catch (Exception $e) {
    sendJsonError('Error interno del servidor', 500, ['message' => $e->getMessage()]);
}

/**
 * Obtiene lista de autores con paginación
 */
function getAutores($db) {
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
            $whereConditions[] = "(a.nombre LIKE ? OR a.biografia LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        // Contar total de registros
        $countQuery = "SELECT COUNT(*) FROM autores a {$whereClause}";
        $countStmt = $db->prepare($countQuery);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        // Obtener autores paginados
        $query = "
            SELECT 
                a.id,
                a.nombre,
                a.biografia,
                a.fecha_creacion,
                a.fecha_actualizacion,
                COUNT(p.id) as total_poemas
            FROM autores a
            LEFT JOIN poemas p ON a.id = p.autor_id
            {$whereClause}
            GROUP BY a.id
            ORDER BY a.nombre {$orden}
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
        $autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formatear respuesta
        $autores = array_map(function($autor) {
            return [
                'id' => (int)$autor['id'],
                'nombre' => $autor['nombre'],
                'biografia' => $autor['biografia'],
                'total_poemas' => (int)$autor['total_poemas'],
                'fechas' => [
                    'creacion' => formatDate($autor['fecha_creacion']),
                    'actualizacion' => formatDate($autor['fecha_actualizacion'])
                ]
            ];
        }, $autores);
        
        // Preparar metadatos
        $meta = getPaginationMeta($total, $pagination['page'], $pagination['limit']);
        
        if ($search) {
            $meta['filters'] = ['search' => $search];
        }
        
        sendJsonResponse($autores, $meta);
        
    } catch (PDOException $e) {
        sendJsonError('Error al obtener autores', 500, ['database_error' => $e->getMessage()]);
    }
}

/**
 * Obtiene un autor específico con sus poemas
 */
function getAutor($db, $id) {
    try {
        // Obtener información del autor
        $query = "
            SELECT 
                a.id,
                a.nombre,
                a.biografia,
                a.fecha_creacion,
                a.fecha_actualizacion,
                COUNT(p.id) as total_poemas
            FROM autores a
            LEFT JOIN poemas p ON a.id = p.autor_id
            WHERE a.id = ?
            GROUP BY a.id
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $autor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$autor) {
            sendJsonError('Autor no encontrado', 404);
            return;
        }
        
        // Obtener poemas del autor
        $poemasQuery = "
            SELECT 
                p.id,
                p.titulo,
                p.icono,
                p.extracto,
                p.tiempo_lectura,
                p.fecha_creacion,
                c.id as categoria_id,
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
            'id' => (int)$autor['id'],
            'nombre' => $autor['nombre'],
            'biografia' => $autor['biografia'],
            'total_poemas' => (int)$autor['total_poemas'],
            'poemas' => $poemas,
            'fechas' => [
                'creacion' => formatDate($autor['fecha_creacion']),
                'actualizacion' => formatDate($autor['fecha_actualizacion'])
            ]
        ];
        
        sendJsonResponse($response);
        
    } catch (PDOException $e) {
        sendJsonError('Error al obtener autor', 500, ['database_error' => $e->getMessage()]);
    }
}
?>

