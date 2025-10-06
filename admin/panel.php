<?php
/**
 * Panel de Gestión Principal
 * CRUD completo para todas las entidades
 */

require_once 'database.php';

// Obtener sección activa
$section = isset($_GET['section']) ? $_GET['section'] : 'poemas';
$validSections = ['poemas', 'autores', 'categorias', 'etiquetas'];

if (!in_array($section, $validSections)) {
    $section = 'poemas';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Gestión - <?php echo ucfirst($section); ?> | Sistema de Poemas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Crimson+Text:wght@400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'verde': {
                            'principal': '#636B2F',
                            'claro': '#8B9A4A',
                            'oscuro': '#4A5220',
                            'muy-claro': '#F0F4E8',
                        },
                        'rosa': {
                            'principal': '#E89EB8',
                            'claro': '#F2C4D1',
                            'oscuro': '#D67A9A',
                            'muy-claro': '#FDF2F8',
                        },
                        'amarillo': {
                            'claro': '#FDF4E3',
                            'medio': '#F9E6B3',
                            'dorado': '#F4D03F',
                            'intenso': '#D4AC0D',
                        }
                    },
                    fontFamily: {
                        'crimson': ['Crimson Text', 'serif'],
                        'playfair': ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-verde-muy-claro font-crimson">
    <!-- Header -->
    <header class="bg-verde-principal text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-xl font-playfair font-bold hover:text-rosa-claro transition-colors">
                        ⚙️ Panel Admin
                    </a>
                    <span class="text-rosa-claro">/</span>
                    <span class="font-semibold"><?php echo ucfirst($section); ?></span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="panel.php" class="bg-rosa-principal hover:bg-rosa-oscuro text-white px-3 py-1 rounded transition-colors text-sm">
                        📋 Todos los Paneles
                    </a>
                    <a href="../index.html" class="bg-amarillo-dorado hover:bg-amarillo-intenso text-white px-3 py-1 rounded transition-colors text-sm">
                        🏠 Ir al Sitio
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navegación de Secciones -->
    <nav class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4">
            <div class="flex space-x-8">
                <?php foreach ($validSections as $validSection): ?>
                    <a href="panel.php?section=<?php echo $validSection; ?>" 
                       class="py-4 px-2 border-b-2 transition-colors <?php echo $section === $validSection ? 'border-verde-principal text-verde-principal font-semibold' : 'border-transparent text-gray-600 hover:text-verde-principal hover:border-verde-claro'; ?>">
                        <?php
                        $icons = [
                            'poemas' => '📝',
                            'autores' => '👤',
                            'categorias' => '📂',
                            'etiquetas' => '🏷️'
                        ];
                        echo $icons[$validSection] . ' ' . ucfirst($validSection);
                        ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="container mx-auto px-4 py-8">
        <!-- Barra de Acciones -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-playfair font-bold text-verde-oscuro">
                        <?php
                        $titles = [
                            'poemas' => '📝 Gestión de Poemas',
                            'autores' => '👤 Gestión de Autores',
                            'categorias' => '📂 Gestión de Categorías',
                            'etiquetas' => '🏷️ Gestión de Etiquetas'
                        ];
                        echo $titles[$section];
                        ?>
                    </h1>
                    <p class="text-gray-600 mt-1">
                        <?php
                        $descriptions = [
                            'poemas' => 'Crear, editar y eliminar poemas del sistema',
                            'autores' => 'Administrar información de autores',
                            'categorias' => 'Organizar poemas por categorías temáticas',
                            'etiquetas' => 'Gestionar etiquetas para clasificar poemas'
                        ];
                        echo $descriptions[$section];
                        ?>
                    </p>
                </div>
                <div>
                    <button onclick="openCreateModal()" class="bg-verde-principal hover:bg-verde-oscuro text-white px-6 py-2 rounded-lg transition-colors">
                        ➕ Nuevo <?php echo ucfirst($section); ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">🔍 Buscar</label>
                    <input type="text" id="search-input" placeholder="Buscar..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">📊 Mostrar</label>
                    <select id="items-per-page" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                        <option value="10">10 por página</option>
                        <option value="25" selected>25 por página</option>
                        <option value="50">50 por página</option>
                        <option value="100">100 por página</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">🔄 Acciones</label>
                    <button onclick="refreshData()" class="bg-rosa-principal hover:bg-rosa-oscuro text-white px-4 py-2 rounded-lg transition-colors">
                        🔄 Actualizar
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de Datos -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr id="table-header">
                            <!-- El header se carga dinámicamente -->
                        </tr>
                    </thead>
                    <tbody id="table-body" class="bg-white divide-y divide-gray-200">
                        <!-- Los datos se cargan dinámicamente -->
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Mostrando <span id="showing-start">1</span> a <span id="showing-end">25</span> 
                        de <span id="total-items">0</span> resultados
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="previousPage()" id="prev-btn" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded transition-colors" disabled>
                            ← Anterior
                        </button>
                        <span id="page-info" class="px-3 py-1 bg-verde-principal text-white rounded">Página 1</span>
                        <button onclick="nextPage()" id="next-btn" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded transition-colors">
                            Siguiente →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modales -->
    <!-- Modal de Crear/Editar -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 id="modal-title" class="text-2xl font-playfair font-bold text-verde-oscuro">
                            Nuevo <?php echo ucfirst($section); ?>
                        </h2>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                            ✕
                        </button>
                    </div>
                    
                    <form id="modal-form" class="space-y-4">
                        <div id="modal-content">
                            <!-- El contenido del formulario se carga dinámicamente -->
                        </div>
                        
                        <div class="flex justify-end space-x-4 pt-6 border-t">
                            <button type="button" onclick="closeModal()" 
                                    class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="px-6 py-2 bg-verde-principal hover:bg-verde-oscuro text-white rounded-lg transition-colors">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="text-center">
                        <div class="text-4xl mb-4">⚠️</div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Confirmar Eliminación</h2>
                        <p id="confirm-message" class="text-gray-600 mb-6">
                            ¿Estás seguro de que quieres eliminar este elemento?
                        </p>
                        <div class="flex justify-center space-x-4">
                            <button onclick="closeConfirmModal()" 
                                    class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button onclick="confirmDelete()" 
                                    class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/admin.js"></script>
    <script>
        // Variables globales
        let currentSection = '<?php echo $section; ?>';
        let currentData = [];
        let filteredData = [];
        let currentPage = 1;
        let itemsPerPage = 25;
        let currentEditingId = null;
        let deleteId = null;

        // Inicializar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            setupEventListeners();
        });

        // Configurar event listeners
        function setupEventListeners() {
            // Búsqueda
            document.getElementById('search-input').addEventListener('input', function() {
                filterData();
            });

            // Items por página
            document.getElementById('items-per-page').addEventListener('change', function() {
                itemsPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable();
            });

            // Formulario del modal
            document.getElementById('modal-form').addEventListener('submit', function(e) {
                e.preventDefault();
                saveItem();
            });
        }

        // Cargar datos desde la API
        async function loadData() {
            try {
                showLoading();
                const response = await fetch(`api/${currentSection}.php`);
                const result = await response.json();
                
                if (result.success) {
                    currentData = result.data;
                    filterData();
                } else {
                    showError('Error al cargar datos: ' + result.message);
                }
            } catch (error) {
                showError('Error de conexión: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        // Filtrar datos
        function filterData() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            
            if (searchTerm === '') {
                filteredData = [...currentData];
            } else {
                filteredData = currentData.filter(item => {
                    return Object.values(item).some(value => 
                        String(value).toLowerCase().includes(searchTerm)
                    );
                });
            }
            
            currentPage = 1;
            renderTable();
        }

        // Renderizar tabla
        function renderTable() {
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);

            renderTableHeader();
            renderTableBody(pageData);
            renderPagination();
        }

        // Renderizar header de la tabla
        function renderTableHeader() {
            const headers = {
                'poemas': ['ID', 'Título', 'Autor', 'Categoría', 'Etiquetas', 'Fecha', 'Acciones'],
                'autores': ['ID', 'Nombre', 'Biografía', 'Poemas', 'Fecha', 'Acciones'],
                'categorias': ['ID', 'Nombre', 'Icono', 'Color', 'Descripción', 'Poemas', 'Acciones'],
                'etiquetas': ['ID', 'Nombre', 'Poemas', 'Fecha', 'Acciones']
            };

            const headerRow = headers[currentSection].map(header => 
                `<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">${header}</th>`
            ).join('');

            document.getElementById('table-header').innerHTML = headerRow;
        }

        // Renderizar cuerpo de la tabla
        function renderTableBody(data) {
            const tbody = document.getElementById('table-body');
            
            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="100%" class="px-6 py-12 text-center text-gray-500">
                            <div class="text-4xl mb-4">📭</div>
                            <p>No se encontraron elementos</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = data.map(item => {
                return renderTableRow(item);
            }).join('');
        }

        // Renderizar fila de tabla
        function renderTableRow(item) {
            const actions = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button onclick="editItem(${item.id})" class="text-verde-principal hover:text-verde-oscuro mr-3">✏️ Editar</button>
                    <button onclick="deleteItem(${item.id})" class="text-red-500 hover:text-red-700">🗑️ Eliminar</button>
                </td>
            `;

            switch (currentSection) {
                case 'poemas':
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <span class="text-lg mr-2">${item.icono || '📝'}</span>
                                    <div>
                                        <div class="font-semibold">${item.titulo}</div>
                                        <div class="text-gray-500 text-xs">${item.extracto || ''}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.autor_nombre}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                      style="background-color: ${item.categoria_color || '#636B2F'}20; color: ${item.categoria_color || '#636B2F'}">
                                    ${item.categoria_icono || '📂'} ${item.categoria_nombre}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${Array.isArray(item.etiquetas) ? item.etiquetas.map(tag => `<span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs mr-1">${tag}</span>`).join('') : ''}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatDate(item.fecha_creacion)}</td>
                            ${actions}
                        </tr>
                    `;

                case 'autores':
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.nombre}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">${item.biografia || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rosa-muy-claro text-rosa-principal">
                                    ${item.total_poemas || 0} poemas
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatDate(item.fecha_creacion)}</td>
                            ${actions}
                        </tr>
                    `;

                case 'categorias':
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.nombre}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-2xl">${item.icono || '📂'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full mr-2" style="background-color: ${item.color || '#636B2F'}"></div>
                                    <span class="text-xs">${item.color || '#636B2F'}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">${item.descripcion || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amarillo-claro text-amarillo-intenso">
                                    ${item.total_poemas || 0} poemas
                                </span>
                            </td>
                            ${actions}
                        </tr>
                    `;

                case 'etiquetas':
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span class="inline-block bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm">${item.nombre}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-verde-muy-claro text-verde-principal">
                                    ${item.total_poemas || 0} poemas
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatDate(item.fecha_creacion)}</td>
                            ${actions}
                        </tr>
                    `;

                default:
                    return '';
            }
        }

        // Renderizar paginación
        function renderPagination() {
            const totalItems = filteredData.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage + 1;
            const endIndex = Math.min(currentPage * itemsPerPage, totalItems);

            // Actualizar información
            document.getElementById('showing-start').textContent = totalItems > 0 ? startIndex : 0;
            document.getElementById('showing-end').textContent = endIndex;
            document.getElementById('total-items').textContent = totalItems;
            document.getElementById('page-info').textContent = `Página ${currentPage} de ${totalPages}`;

            // Actualizar botones
            document.getElementById('prev-btn').disabled = currentPage === 1;
            document.getElementById('next-btn').disabled = currentPage === totalPages;
        }

        // Navegación de páginas
        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        }

        function nextPage() {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        }

        // Abrir modal para crear
        function openCreateModal() {
            currentEditingId = null;
            document.getElementById('modal-title').textContent = `Nuevo ${currentSection.charAt(0).toUpperCase() + currentSection.slice(1)}`;
            loadModalForm();
            document.getElementById('modal').classList.remove('hidden');
        }

        // Editar elemento
        function editItem(id) {
            currentEditingId = id;
            document.getElementById('modal-title').textContent = `Editar ${currentSection.charAt(0).toUpperCase() + currentSection.slice(1)}`;
            loadModalForm(id);
            document.getElementById('modal').classList.remove('hidden');
        }

        // Cargar formulario del modal
        async function loadModalForm(id = null) {
            const modalContent = document.getElementById('modal-content');
            
            if (id) {
                // Cargar datos del elemento a editar
                const item = currentData.find(item => item.id === id);
                if (item) {
                    modalContent.innerHTML = generateFormHTML(item);
                }
            } else {
                // Formulario vacío para crear
                modalContent.innerHTML = generateFormHTML();
            }
        }

        // Generar HTML del formulario
        function generateFormHTML(item = null) {
            const getValue = (field) => item ? (item[field] || '') : '';
            
            switch (currentSection) {
                case 'poemas':
                    return `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
                                <input type="text" name="titulo" value="${getValue('titulo')}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Icono</label>
                                <input type="text" name="icono" value="${getValue('icono')}" placeholder="📝"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Autor *</label>
                                <select name="autor_id" required id="autor-select"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                                    <option value="">Seleccionar autor...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                                <select name="categoria_id" required id="categoria-select"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                                    <option value="">Seleccionar categoría...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tiempo de Lectura (min)</label>
                                <input type="number" name="tiempo_lectura" value="${getValue('tiempo_lectura') || 2}" min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Etiquetas</label>
                                <select name="etiquetas[]" multiple id="etiquetas-select"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Extracto</label>
                            <textarea name="extracto" rows="2" placeholder="Breve descripción del poema..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">${getValue('extracto')}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contenido *</label>
                            <textarea name="contenido" rows="8" required placeholder="Contenido completo del poema..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">${getValue('contenido')}</textarea>
                        </div>
                    `;

                case 'autores':
                    return `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                            <input type="text" name="nombre" value="${getValue('nombre')}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Biografía</label>
                            <textarea name="biografia" rows="4" placeholder="Información sobre el autor..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">${getValue('biografia')}</textarea>
                        </div>
                    `;

                case 'categorias':
                    return `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                                <input type="text" name="nombre" value="${getValue('nombre')}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Icono</label>
                                <input type="text" name="icono" value="${getValue('icono')}" placeholder="📂"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                                <input type="color" name="color" value="${getValue('color') || '#636B2F'}"
                                       class="w-full h-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                                <textarea name="descripcion" rows="2" placeholder="Descripción de la categoría..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">${getValue('descripcion')}</textarea>
                            </div>
                        </div>
                    `;

                case 'etiquetas':
                    return `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                            <input type="text" name="nombre" value="${getValue('nombre')}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-verde-principal focus:border-transparent">
                        </div>
                    `;

                default:
                    return '';
            }
        }

        // Cerrar modal
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            currentEditingId = null;
        }

        // Guardar elemento
        async function saveItem() {
            try {
                const formData = new FormData(document.getElementById('modal-form'));
                const data = Object.fromEntries(formData.entries());
                
                // Procesar datos específicos
                if (currentSection === 'poemas') {
                    data.etiquetas = Array.isArray(data.etiquetas) ? data.etiquetas.map(id => parseInt(id)) : [];
                    data.autor_id = parseInt(data.autor_id);
                    data.categoria_id = parseInt(data.categoria_id);
                    data.tiempo_lectura = parseInt(data.tiempo_lectura);
                }

                const url = currentEditingId ? 
                    `api/${currentSection}.php/${currentEditingId}` : 
                    `api/${currentSection}.php`;
                
                const method = currentEditingId ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    closeModal();
                    loadData();
                    showSuccess(result.message || 'Elemento guardado exitosamente');
                } else {
                    showError('Error al guardar: ' + result.message);
                }
            } catch (error) {
                showError('Error de conexión: ' + error.message);
            }
        }

        // Eliminar elemento
        function deleteItem(id) {
            deleteId = id;
            document.getElementById('confirm-message').textContent = 
                `¿Estás seguro de que quieres eliminar este ${currentSection.slice(0, -1)}? Esta acción no se puede deshacer.`;
            document.getElementById('confirm-modal').classList.remove('hidden');
        }

        // Confirmar eliminación
        async function confirmDelete() {
            try {
                const response = await fetch(`api/${currentSection}.php/${deleteId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    closeConfirmModal();
                    loadData();
                    showSuccess(result.message || 'Elemento eliminado exitosamente');
                } else {
                    showError('Error al eliminar: ' + result.message);
                }
            } catch (error) {
                showError('Error de conexión: ' + error.message);
            }
        }

        // Cerrar modal de confirmación
        function closeConfirmModal() {
            document.getElementById('confirm-modal').classList.add('hidden');
            deleteId = null;
        }

        // Actualizar datos
        function refreshData() {
            loadData();
        }

        // Formatear fecha
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES');
        }

        // Mostrar mensajes
        function showSuccess(message) {
            // Implementar notificación de éxito
            alert('✅ ' + message);
        }

        function showError(message) {
            // Implementar notificación de error
            alert('❌ ' + message);
        }

        function showLoading() {
            // Implementar indicador de carga
        }

        function hideLoading() {
            // Ocultar indicador de carga
        }

        // Cargar opciones de selects cuando sea necesario
        async function loadSelectOptions() {
            if (currentSection === 'poemas') {
                // Cargar autores
                const autoresResponse = await fetch('api/autores.php');
                const autoresData = await autoresResponse.json();
                if (autoresData.success) {
                    const autorSelect = document.getElementById('autor-select');
                    autorSelect.innerHTML = '<option value="">Seleccionar autor...</option>' +
                        autoresData.data.map(autor => `<option value="${autor.id}">${autor.nombre}</option>`).join('');
                }

                // Cargar categorías
                const categoriasResponse = await fetch('api/categorias.php');
                const categoriasData = await categoriasResponse.json();
                if (categoriasData.success) {
                    const categoriaSelect = document.getElementById('categoria-select');
                    categoriaSelect.innerHTML = '<option value="">Seleccionar categoría...</option>' +
                        categoriasData.data.map(cat => `<option value="${cat.id}">${cat.icono} ${cat.nombre}</option>`).join('');
                }

                // Cargar etiquetas
                const etiquetasResponse = await fetch('api/etiquetas.php');
                const etiquetasData = await etiquetasResponse.json();
                if (etiquetasData.success) {
                    const etiquetasSelect = document.getElementById('etiquetas-select');
                    etiquetasSelect.innerHTML = etiquetasData.data.map(etiqueta => 
                        `<option value="${etiqueta.id}">${etiqueta.nombre}</option>`
                    ).join('');
                }
            }
        }

        // Cargar opciones cuando se abra el modal
        document.addEventListener('click', function(e) {
            if (e.target.onclick && e.target.onclick.toString().includes('openCreateModal')) {
                setTimeout(loadSelectOptions, 100);
            }
        });
    </script>
</body>
</html>
