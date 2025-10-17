/**
 * Configuración de la aplicación
 * Contiene todas las configuraciones globales del sistema
 */
const AppConfig = {
    // Configuración general
    debug: false,
    version: '1.0.0',
    name: 'Sistema de Poemas Dinámico',
    
    // Configuración de la API
    api: {
        baseUrl: '/admin/api',
        endpoints: {
            poemas: '/poemas.php',
            categorias: '/categorias.php',
            autores: '/autores.php',
            etiquetas: '/etiquetas.php',
            test: '/test.php'
        },
        timeout: 10000
    },
    
    // Configuración de temas
    themes: {
        default: 'default',
        storageKey: 'poemas_theme',
        transitionDuration: 300
    },
    
    // Configuración de componentes
    components: {
        navbar: {
            autoRender: true,
            showMobileMenu: false
        },
        footer: {
            autoRender: true,
            showAdminLink: true
        },
        themeSelector: {
            autoRender: true,
            compact: true,
            showDescription: false
        }
    },
    
    // Configuración de páginas
    pages: {
        home: {
            autoLoad: true,
            showStats: true,
            cacheData: true
        }
    },
    
    // Configuración de almacenamiento
    storage: {
        prefix: 'plataforma_poemas_',
        maxSize: 5 * 1024 * 1024, // 5MB
        compression: false
    },
    
    // Configuración de animaciones
    animations: {
        enabled: true,
        duration: 300,
        easing: 'ease-in-out'
    },
    
    // Configuración de errores
    errors: {
        showInConsole: true,
        showToUser: false,
        logToServer: false
    },
    
    // Configuración de desarrollo
    development: {
        hotReload: false,
        mockData: false,
        verboseLogging: false
    }
};

// Hacer la configuración disponible globalmente
window.AppConfig = AppConfig;