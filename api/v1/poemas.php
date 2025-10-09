<?php
/**
 * API RESTful Pública v1 - Poemas
 * Endpoint de solo lectura para consumo externo
 * 
 * Endpoints:
 * - GET /api/v1/poemas.php - Lista todos los poemas (paginado)
 * - GET /api/v1/poemas.php/{id} - Obtiene un poema específico
 * 
 * Parámetros de consulta:
 * - page: Número de página (default: 1)
 * - limit: Elementos por página (default: 20, max: 100)
 * - categoria: Filtrar por ID de categoría
 * - autor: Filtrar por ID de autor
 * - etiqueta: Filtrar por ID de etiqueta
 * - search: Buscar en título y contenido
 * - orden: Ordenar por fecha_creacion (asc|desc, default: desc)
 */

require_once __DIR__ . '/base.php';

try {
    validateGetMethod();
    
    $db = getDatabase()->getConnection();
    $id = getIdFromUrl();
    
    if ($id) {
        // Obtener un poema específico
        getPoema($db, $id);
    } else {
        // Obtener lista de poemas con filtros y paginación
        getPoemas($db);
    }
    
} catch (Exception $e) {
    sendJsonError('Error interno del servidor', 500, ['message' => $e->getMessage()]);
}

/**
 * Obtiene lista de poemas con filtros y paginación
 */
function getPoemas($db) {
    try {
        // Obtener parámetros de paginación
        $pagination = getPaginationParams();
        
        // Obtener parámetros de filtro
        $categoriaId = isset($_GET['categoria']) && is_numeric($_GET['categoria']) ? intval($_GET['categoria']) : null;
        $autorId = isset($_GET['autor']) && is_numeric($_GET['autor']) ? intval($_GET['autor']) : null;
        $etiquetaId = isset($_GET['etiqueta']) && is_numeric($_GET['etiqueta']) ? intval($_GET['etiqueta']) : null;
        $search = sanitizeSearchParam('search');
        $orden = isset($_GET['orden']) && strtolower($_GET['orden']) === 'asc' ? 'ASC' : 'DESC';
        
        // Construir query base
        $whereConditions = [];
        $params = [];
        
        if ($categoriaId) {
            $whereConditions[] = "p.categoria_id = ?";
            $params[] = $categoriaId;
        }
        
        if ($autorId) {
            $whereConditions[] = "p.autor_id = ?";
            $params[] = $autorId;
        }
        
        if ($etiquetaId) {
            $whereConditions[] = "EXISTS (
                SELECT 1 FROM poema_etiquetas pe 
                WHERE pe.poema_id = p.id AND pe.etiqueta_id = ?
            )";
            $params[] = $etiquetaId;
        }
        
        if ($search) {
            $whereConditions[] = "(p.titulo LIKE ? OR p.contenido LIKE ? OR p.extracto LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        // Contar total de registros
        $countQuery = "
            SELECT COUNT(DISTINCT p.id)
            FROM poemas p
            {$whereClause}
        ";
        
        $countStmt = $db->prepare($countQuery);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        // Obtener poemas paginados
        $query = "
            SELECT 
                p.id,
                p.titulo,
                p.icono,
                p.extracto,
                p.contenido,
                p.tiempo_lectura,
                p.fecha_creacion,
                p.fecha_actualizacion,
                a.id as autor_id,
                a.nombre as autor_nombre,
                a.biografia as autor_biografia,
                c.id as categoria_id,
                c.nombre as categoria_nombre,
                c.icono as categoria_icono,
                c.color as categoria_color,
                c.descripcion as categoria_descripcion
            FROM poemas p
            JOIN autores a ON p.autor_id = a.id
            JOIN categorias c ON p.categoria_id = c.id
            {$whereClause}
            ORDER BY p.fecha_creacion {$orden}
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
        $poemas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener etiquetas para cada poema
        if (!empty($poemas)) {
            $poemasIds = array_column($poemas, 'id');
            $placeholders = str_repeat('?,', count($poemasIds) - 1) . '?';
            
            $etiquetasQuery = "
                SELECT 
                    pe.poema_id,
                    e.id,
                    e.nombre
                FROM poema_etiquetas pe
                JOIN etiquetas e ON pe.etiqueta_id = e.id
                WHERE pe.poema_id IN ({$placeholders})
                ORDER BY e.nombre ASC
            ";
            
            $etiquetasStmt = $db->prepare($etiquetasQuery);
            $etiquetasStmt->execute($poemasIds);
            $etiquetasResult = $etiquetasStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Agrupar etiquetas por poema
            $etiquetasPorPoema = [];
            foreach ($etiquetasResult as $etiqueta) {
                $poemaId = $etiqueta['poema_id'];
                if (!isset($etiquetasPorPoema[$poemaId])) {
                    $etiquetasPorPoema[$poemaId] = [];
                }
                $etiquetasPorPoema[$poemaId][] = [
                    'id' => (int)$etiqueta['id'],
                    'nombre' => $etiqueta['nombre']
                ];
            }
            
            // Formatear los poemas
            $poemas = array_map(function($poema) use ($etiquetasPorPoema) {
                return formatPoemaResponse($poema, $etiquetasPorPoema[$poema['id']] ?? []);
            }, $poemas);
        }
        
        // Preparar metadatos
        $meta = getPaginationMeta($total, $pagination['page'], $pagination['limit']);
        
        // Agregar filtros aplicados a los metadatos
        $filtrosAplicados = [];
        if ($categoriaId) $filtrosAplicados['categoria'] = $categoriaId;
        if ($autorId) $filtrosAplicados['autor'] = $autorId;
        if ($etiquetaId) $filtrosAplicados['etiqueta'] = $etiquetaId;
        if ($search) $filtrosAplicados['search'] = $search;
        if (!empty($filtrosAplicados)) {
            $meta['filters'] = $filtrosAplicados;
        }
        
        sendJsonResponse($poemas, $meta);
        
    } catch (PDOException $e) {
        sendJsonError('Error al obtener poemas', 500, ['database_error' => $e->getMessage()]);
    }
}

/**
 * Obtiene un poema específico por ID
 */
function getPoema($db, $id) {
    try {
        $query = "
            SELECT 
                p.id,
                p.titulo,
                p.icono,
                p.extracto,
                p.contenido,
                p.tiempo_lectura,
                p.fecha_creacion,
                p.fecha_actualizacion,
                a.id as autor_id,
                a.nombre as autor_nombre,
                a.biografia as autor_biografia,
                c.id as categoria_id,
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
        $poema = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$poema) {
            sendJsonError('Poema no encontrado', 404);
            return;
        }
        
        // Obtener etiquetas del poema
        $etiquetasQuery = "
            SELECT 
                e.id,
                e.nombre
            FROM etiquetas e
            JOIN poema_etiquetas pe ON e.id = pe.etiqueta_id
            WHERE pe.poema_id = ?
            ORDER BY e.nombre ASC
        ";
        
        $etiquetasStmt = $db->prepare($etiquetasQuery);
        $etiquetasStmt->execute([$id]);
        $etiquetas = $etiquetasStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $etiquetas = array_map(function($etiqueta) {
            return [
                'id' => (int)$etiqueta['id'],
                'nombre' => $etiqueta['nombre']
            ];
        }, $etiquetas);
        
        // Formatear respuesta
        $poema = formatPoemaResponse($poema, $etiquetas);
        
        sendJsonResponse($poema);
        
    } catch (PDOException $e) {
        sendJsonError('Error al obtener poema', 500, ['database_error' => $e->getMessage()]);
    }
}

/**
 * Formatea la respuesta de un poema
 */
function formatPoemaResponse($poema, $etiquetas) {
    return [
        'id' => (int)$poema['id'],
        'titulo' => $poema['titulo'],
        'icono' => $poema['icono'],
        'extracto' => $poema['extracto'],
        'contenido' => $poema['contenido'],
        'tiempo_lectura' => (int)$poema['tiempo_lectura'],
        'autor' => [
            'id' => (int)$poema['autor_id'],
            'nombre' => $poema['autor_nombre'],
            'biografia' => $poema['autor_biografia']
        ],
        'categoria' => [
            'id' => (int)$poema['categoria_id'],
            'nombre' => $poema['categoria_nombre'],
            'icono' => $poema['categoria_icono'],
            'color' => $poema['categoria_color'],
            'descripcion' => $poema['categoria_descripcion']
        ],
        'etiquetas' => $etiquetas,
        'fechas' => [
            'creacion' => formatDate($poema['fecha_creacion']),
            'actualizacion' => formatDate($poema['fecha_actualizacion'])
        ]
    ];
}
?>

