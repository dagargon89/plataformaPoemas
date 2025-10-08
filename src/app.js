/**
 * Aplicación principal del Sistema de Poemas
 * Punto de entrada y coordinador principal
 * Última actualización: Navbar removido - 2024
 */
class App {
    constructor() {
        this.config = window.AppConfig || {};
        this.currentPage = null;
        this.components = new Map();
        this.isInitialized = false;
        this.themeService = null;
        
        this.init();
    }

    /**
     * Inicializa la aplicación
     */
    async init() {
        if (this.isInitialized) return;
        
        console.log('🚀 Iniciando Sistema de Poemas Dinámico...');
        
        try {
            this.initializeThemeService();
            this.setupGlobalEventListeners();
            this.initializeComponents();
            await this.loadCurrentPage();
            this.isInitialized = true;
            
            console.log('✅ Aplicación inicializada correctamente');
        } catch (error) {
            console.error('❌ Error al inicializar la aplicación:', error);
            this.handleAppError(error);
        }
    }

    /**
     * Inicializa el servicio de temas
     */
    initializeThemeService() {
        console.log('🎨 Inicializando servicio de temas...');
        this.themeService = window.themeService || new window.ThemeService();
        console.log('✅ Servicio de temas inicializado');
    }

    /**
     * Configura event listeners globales
     */
    setupGlobalEventListeners() {
        // Event listener para cambios de ruta
        window.addEventListener('routechange', (e) => {
            this.handleRouteChange(e.detail);
        });

        // Event listener para errores globales
        window.addEventListener('error', (e) => {
            this.handleGlobalError(e);
        });

        // Event listener para errores de promesas no capturadas
        window.addEventListener('unhandledrejection', (e) => {
            this.handlePromiseRejection(e);
        });

        // Event listener para el evento beforeunload
        window.addEventListener('beforeunload', () => {
            this.cleanup();
        });
    }

    /**
     * Inicializa los componentes globales
     */
    initializeComponents() {
        console.log('🔧 Inicializando componentes...');
        
        // Navbar removido - no necesario en página principal
        // Verificación de seguridad: si NavbarComponent existe, no lo usar
        if (typeof window.NavbarComponent !== 'undefined') {
            console.log('⚠️ NavbarComponent encontrado pero no se usará en página principal');
        }
        
        // Inicializar Footer
        if (typeof window.FooterComponent !== 'undefined') {
            const footer = new window.FooterComponent('footer', {
                debug: this.config.debug || false
            });
            this.components.set('footer', footer);
        } else {
            console.warn('⚠️ FooterComponent no encontrado');
        }

        // Selector de Temas desactivado por solicitud del usuario
        // console.log('🎨 Selector de temas desactivado');
        
        console.log('✅ Componentes inicializados');
    }

    /**
     * Carga la página actual
     */
    async loadCurrentPage() {
        const currentRoute = window.router.getCurrentRoute();
        
        if (currentRoute) {
            await this.loadPage(currentRoute);
        } else {
            // Si no hay ruta específica, cargar página de inicio
            await this.loadPage({ name: 'index', component: 'HomePage' });
        }
    }

    /**
     * Carga una página específica
     */
    async loadPage(route) {
        console.log(`📄 Cargando página: ${route.name}`);
        
        try {
            // Limpiar página anterior
            if (this.currentPage) {
                this.currentPage.destroy();
            }
            
            // Determinar qué página cargar
            let pageClass = null;
            
            switch (route.name) {
                case 'index':
                    pageClass = window.HomePage;
                    break;
                case 'tarjetas':
                    // pageClass = TarjetasPage; // Se implementará después
                    console.log('⚠️ Página de tarjetas no implementada aún');
                    return;
                case 'lista':
                    // pageClass = ListaPage; // Se implementará después
                    console.log('⚠️ Página de lista no implementada aún');
                    return;
                case 'libro':
                    // pageClass = LibroPage; // Se implementará después
                    console.log('⚠️ Página de libro no implementada aún');
                    return;
                default:
                    console.warn(`⚠️ Página '${route.name}' no encontrada`);
                    return;
            }
            
            if (pageClass) {
                // Crear instancia de la página
                this.currentPage = new pageClass({
                    debug: this.config.debug || false,
                    ...route.params
                });
                
                console.log(`✅ Página '${route.name}' cargada correctamente`);
            }
            
        } catch (error) {
            console.error(`❌ Error al cargar página '${route.name}':`, error);
            this.handlePageError(route.name, error);
        }
    }

    /**
     * Maneja cambios de ruta
     */
    handleRouteChange(routeData) {
        console.log('🔄 Cambio de ruta:', routeData);
        this.loadPage(routeData.route);
    }

    /**
     * Maneja errores globales de la aplicación
     */
    handleAppError(error) {
        console.error('💥 Error crítico de la aplicación:', error);
        
        // Mostrar mensaje de error al usuario
        this.showCriticalError();
    }

    /**
     * Maneja errores globales del navegador
     */
    handleGlobalError(event) {
        console.error('💥 Error global:', event.error);
        
        // Reportar error si es necesario
        this.reportError('Global Error', event.error);
    }

    /**
     * Maneja promesas rechazadas no capturadas
     */
    handlePromiseRejection(event) {
        console.error('💥 Promesa rechazada:', event.reason);
        
        // Reportar error si es necesario
        this.reportError('Unhandled Promise Rejection', event.reason);
    }

    /**
     * Maneja errores de páginas específicas
     */
    handlePageError(pageName, error) {
        console.error(`💥 Error en página '${pageName}':`, error);
        
        // Mostrar página de error
        this.showErrorPage(pageName, error);
    }

    /**
     * Muestra error crítico
     */
    showCriticalError() {
        const errorHTML = `
            <div class="min-h-screen flex items-center justify-center bg-red-50">
                <div class="text-center p-8">
                    <div class="text-6xl mb-4">💥</div>
                    <h1 class="text-2xl font-bold text-red-800 mb-4">
                        Error Crítico del Sistema
                    </h1>
                    <p class="text-red-600 mb-6">
                        Ha ocurrido un error inesperado. Por favor, recarga la página.
                    </p>
                    <button onclick="window.location.reload()" 
                            class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        🔄 Recargar Página
                    </button>
                </div>
            </div>
        `;
        
        document.body.innerHTML = errorHTML;
    }

    /**
     * Muestra página de error para una página específica
     */
    showErrorPage(pageName, error) {
        const errorHTML = `
            <div class="container mx-auto px-4 py-8">
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <div class="text-4xl mb-4">⚠️</div>
                    <h2 class="text-xl font-bold text-red-800 mb-2">
                        Error al cargar la página
                    </h2>
                    <p class="text-red-600 mb-4">
                        No se pudo cargar la página "${pageName}". 
                        Por favor, intenta nuevamente.
                    </p>
                    <div class="space-x-4">
                        <button onclick="window.location.href='index.html'" 
                                class="bg-verde-principal text-white px-4 py-2 rounded-lg hover:bg-verde-oscuro transition-colors">
                            🏠 Ir al Inicio
                        </button>
                        <button onclick="window.location.reload()" 
                                class="bg-rosa-principal text-white px-4 py-2 rounded-lg hover:bg-rosa-oscuro transition-colors">
                            🔄 Recargar
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        const main = document.querySelector('main');
        if (main) {
            main.innerHTML = errorHTML;
        }
    }

    /**
     * Reporta errores (para futuras implementaciones)
     */
    reportError(type, error) {
        // Aquí se podría implementar un sistema de reporte de errores
        console.log(`📊 Error reportado: ${type}`, error);
    }

    /**
     * Obtiene un componente por nombre
     */
    getComponent(name) {
        return this.components.get(name);
    }

    /**
     * Actualiza la configuración de la aplicación
     */
    updateConfig(newConfig) {
        this.config = { ...this.config, ...newConfig };
        console.log('⚙️ Configuración actualizada:', newConfig);
    }

    /**
     * Obtiene información de la aplicación
     */
    getAppInfo() {
        return {
            name: this.config.appName,
            version: this.config.version,
            initialized: this.isInitialized,
            currentPage: this.currentPage ? this.currentPage.constructor.name : null,
            components: Array.from(this.components.keys())
        };
    }

    /**
     * Limpia recursos antes de cerrar la aplicación
     */
    cleanup() {
        console.log('🧹 Limpiando aplicación...');
        
        // Destruir página actual
        if (this.currentPage) {
            this.currentPage.destroy();
        }
        
        // Destruir componentes
        for (const [name, component] of this.components) {
            if (typeof component.destroy === 'function') {
                component.destroy();
            }
        }
        
        this.components.clear();
        this.isInitialized = false;
    }
}

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.app = new App();
});

// Exportar para uso global si es necesario
window.App = App;
