<?php
/**
 * Panel Administrativo - Página Principal
 * Sistema de Poemas Dinámico
 */

require_once 'database.php';

// Verificar estado de la base de datos
$db = new Database();
$dbStatus = $db->checkDatabaseStatus();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Sistema de Poemas</title>
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
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-playfair font-bold">⚙️ Panel Administrativo</h1>
                    <p class="text-rosa-claro mt-1">Sistema de Poemas Dinámico</p>
                </div>
                <div class="text-right">
                    <a href="../index.html" class="bg-rosa-principal hover:bg-rosa-oscuro text-white px-4 py-2 rounded-lg transition-colors">
                        🏠 Ir al Sitio
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="container mx-auto px-4 py-8">
        <!-- Estado del Sistema -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-playfair font-bold text-verde-oscuro mb-4">
                📊 Estado del Sistema
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Base de Datos -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-700">Base de Datos</h3>
                            <p class="text-sm text-gray-600">SQLite</p>
                        </div>
                        <div class="text-2xl">
                            <?php if ($dbStatus['database_exists']): ?>
                                ✅
                            <?php else: ?>
                                ❌
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tablas -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-700">Tablas</h3>
                            <p class="text-sm text-gray-600">Estructura</p>
                        </div>
                        <div class="text-2xl">
                            <?php if ($dbStatus['tables_created']): ?>
                                ✅
                            <?php else: ?>
                                ❌
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Datos -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-700">Datos</h3>
                            <p class="text-sm text-gray-600">Contenido</p>
                        </div>
                        <div class="text-2xl">
                            <?php if ($dbStatus['data_inserted']): ?>
                                ✅
                            <?php else: ?>
                                ❌
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Claves Foráneas -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-700">Relaciones</h3>
                            <p class="text-sm text-gray-600">Foreign Keys</p>
                        </div>
                        <div class="text-2xl">
                            <?php if ($dbStatus['foreign_keys_enabled']): ?>
                                ✅
                            <?php else: ?>
                                ❌
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones de Base de Datos -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex flex-wrap gap-4">
                    <?php if (!$dbStatus['database_exists'] || !$dbStatus['tables_created'] || !$dbStatus['data_inserted']): ?>
                        <button onclick="runMigration()" class="bg-verde-principal hover:bg-verde-oscuro text-white px-4 py-2 rounded-lg transition-colors">
                            🚀 Ejecutar Migración
                        </button>
                    <?php endif; ?>
                    
                    <button onclick="checkDatabaseStatus()" class="bg-rosa-principal hover:bg-rosa-oscuro text-white px-4 py-2 rounded-lg transition-colors">
                        🔄 Verificar Estado
                    </button>
                    
                    <a href="api/test.php" class="bg-amarillo-dorado hover:bg-amarillo-intenso text-white px-4 py-2 rounded-lg transition-colors">
                        🧪 Probar API
                    </a>
                </div>
            </div>
        </div>

        <!-- Panel de Gestión -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Gestión de Poemas -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="openManagement('poemas')">
                <div class="text-center">
                    <div class="text-4xl mb-4">📝</div>
                    <h3 class="text-xl font-playfair font-semibold text-verde-oscuro mb-2">
                        Poemas
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Gestionar poemas, crear, editar y eliminar
                    </p>
                    <div class="bg-verde-muy-claro rounded-lg p-2">
                        <span class="text-verde-principal font-semibold" id="poemas-count">-</span>
                        <span class="text-sm text-gray-600"> poemas</span>
                    </div>
                </div>
            </div>

            <!-- Gestión de Autores -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="openManagement('autores')">
                <div class="text-center">
                    <div class="text-4xl mb-4">👤</div>
                    <h3 class="text-xl font-playfair font-semibold text-verde-oscuro mb-2">
                        Autores
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Administrar información de autores
                    </p>
                    <div class="bg-rosa-muy-claro rounded-lg p-2">
                        <span class="text-rosa-principal font-semibold" id="autores-count">-</span>
                        <span class="text-sm text-gray-600"> autores</span>
                    </div>
                </div>
            </div>

            <!-- Gestión de Categorías -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="openManagement('categorias')">
                <div class="text-center">
                    <div class="text-4xl mb-4">📂</div>
                    <h3 class="text-xl font-playfair font-semibold text-verde-oscuro mb-2">
                        Categorías
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Organizar poemas por categorías
                    </p>
                    <div class="bg-amarillo-claro rounded-lg p-2">
                        <span class="text-amarillo-intenso font-semibold" id="categorias-count">-</span>
                        <span class="text-sm text-gray-600"> categorías</span>
                    </div>
                </div>
            </div>

            <!-- Gestión de Etiquetas -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="openManagement('etiquetas')">
                <div class="text-center">
                    <div class="text-4xl mb-4">🏷️</div>
                    <h3 class="text-xl font-playfair font-semibold text-verde-oscuro mb-2">
                        Etiquetas
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Etiquetar poemas para mejor organización
                    </p>
                    <div class="bg-verde-muy-claro rounded-lg p-2">
                        <span class="text-verde-principal font-semibold" id="etiquetas-count">-</span>
                        <span class="text-sm text-gray-600"> etiquetas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Gestión Dinámico -->
        <div id="management-panel" class="mt-8 hidden">
            <!-- El contenido se carga dinámicamente -->
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-verde-oscuro text-white py-6 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-rosa-claro">
                © <?php echo date('Y'); ?> Sistema de Poemas Dinámico | Panel Administrativo v1.0.0
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="js/admin.js"></script>
    <script>
        // Cargar estadísticas al iniciar
        document.addEventListener('DOMContentLoaded', function() {
            loadStatistics();
        });

        // Ejecutar migración
        async function runMigration() {
            try {
                const response = await fetch('migrate.php');
                const result = await response.json();
                
                if (result.success) {
                    alert('✅ Migración completada exitosamente');
                    location.reload();
                } else {
                    alert('❌ Error en la migración: ' + result.message);
                }
            } catch (error) {
                alert('❌ Error al ejecutar migración: ' + error.message);
            }
        }

        // Verificar estado de la base de datos
        async function checkDatabaseStatus() {
            location.reload();
        }

        // Abrir panel de gestión
        function openManagement(type) {
            window.location.href = `panel.php?section=${type}`;
        }

        // Cargar estadísticas
        async function loadStatistics() {
            try {
                const endpoints = ['poemas', 'autores', 'categorias', 'etiquetas'];
                
                for (const endpoint of endpoints) {
                    const response = await fetch(`api/${endpoint}.php`);
                    const data = await response.json();
                    
                    if (data.success && data.data) {
                        const count = Array.isArray(data.data) ? data.data.length : 0;
                        document.getElementById(`${endpoint}-count`).textContent = count;
                    }
                }
            } catch (error) {
                console.error('Error al cargar estadísticas:', error);
            }
        }
    </script>
</body>
</html>
