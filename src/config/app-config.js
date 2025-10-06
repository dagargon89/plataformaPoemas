/**
 * Configuración principal de la aplicación
 */
export const AppConfig = {
    // Configuración general
    appName: 'Sistema de Poemas Dinámico',
    version: '1.0.0',
    
    // Rutas de la API
    api: {
        baseUrl: '/admin/api',
        endpoints: {
            poemas: '/poemas.php',
            autores: '/autores.php',
            categorias: '/categorias.php',
            etiquetas: '/etiquetas.php'
        }
    },
    
    // Configuración de vistas
    views: {
        tarjetas: 'tarjetas-poemas.html',
        lista: 'lista-scroll.html',
        libro: 'libro-tradicional.html'
    },
    
    // Configuración de estilos
    theme: {
        colors: {
            verde: {
                principal: '#636B2F',
                claro: '#8B9A4A',
                oscuro: '#4A5220',
                muyClaro: '#F0F4E8'
            },
            rosa: {
                principal: '#E89EB8',
                claro: '#F2C4D1',
                oscuro: '#D67A9A',
                muyClaro: '#FDF2F8'
            },
            amarillo: {
                claro: '#FDF4E3',
                medio: '#F9E6B3',
                dorado: '#F4D03F',
                intenso: '#D4AC0D'
            }
        },
        fonts: {
            primary: 'Crimson Text',
            heading: 'Playfair Display'
        }
    },
    
    // Configuración de animaciones
    animations: {
        duration: {
            fast: '0.2s',
            normal: '0.3s',
            slow: '0.5s'
        },
        easing: 'ease-in-out'
    },
    
    // Configuración de storage
    storage: {
        prefix: 'poemas_',
        keys: {
            currentView: 'current_view',
            userPreferences: 'user_preferences',
            lastReadPoem: 'last_read_poem'
        }
    }
};
