/**
 * Configuración de la aplicación - Sistema de Poemas Dinámico
 * Versión modernizada con GSAP y Three.js
 */

window.AppConfig = {
    // Información básica de la aplicación
    appName: 'Para Ti, Mi Poesía',
    version: '2.0.0',
    description: 'Un santuario digital donde los versos florecen y las emociones encuentran su voz',
    
    // Configuración de desarrollo
    debug: true,
    environment: 'development',
    
    // Configuración de la API
    api: {
        baseUrl: '/api/v1',
        timeout: 10000,
        retries: 3
    },
    
    // Configuración de temas
    themes: {
        default: 'light',
        available: ['light', 'dark'],
        autoDetect: true,
        transition: true
    },
    
    // Configuración de animaciones
    animations: {
        enabled: true,
        duration: {
            fast: 0.2,
            normal: 0.3,
            slow: 0.5
        },
        easing: 'power2.out',
        stagger: 0.1
    },
    
    // Configuración de GSAP
    gsap: {
        enabled: true,
        version: '3.12.2',
        plugins: ['ScrollTrigger', 'TextPlugin', 'MorphSVGPlugin'],
        defaults: {
            duration: 0.3,
            ease: 'power2.out'
        }
    },
    
    // Configuración de Three.js
    threejs: {
        enabled: true,
        version: 'r128',
        antialias: true,
        alpha: true,
        particles: {
            count: 100,
            size: 0.05,
            color: 0x6B7A40,
            opacity: 0.6
        }
    },
    
    // Configuración de rutas
    routes: {
        home: 'index.html',
        libro: 'libro-tradicional.html',
        lista: 'lista-scroll.html',
        tarjetas: 'tarjetas-poemas.html'
    },
    
    // Configuración de colores
    colors: {
        primary: '#6B7A40',
        primaryLight: '#8A9A5B',
        secondary: '#FADADD',
        accent: '#FFFACD',
        backgroundLight: '#FDFCF7',
        backgroundDark: '#2C3531',
        textLight: '#36454F',
        textDark: '#EAEAEA'
    },
    
    // Configuración de fuentes
    fonts: {
        display: 'Playfair Display',
        body: 'Lora',
        fallback: 'serif'
    },
    
    // Configuración de breakpoints
    breakpoints: {
        xs: '480px',
        sm: '640px',
        md: '768px',
        lg: '1024px',
        xl: '1280px',
        '2xl': '1536px'
    },
    
    // Configuración de scroll
    scroll: {
        smooth: true,
        infinite: true,
        threshold: 100,
        parallax: true
    },
    
    // Configuración de efectos
    effects: {
        particles: true,
        floatingHearts: true,
        romanticGlow: true,
        glassmorphism: true,
        backdropBlur: true
    },
    
    // Configuración de performance
    performance: {
        lazyLoading: true,
        imageOptimization: true,
        codeSplitting: true,
        caching: true
    },
    
    // Configuración de accesibilidad
    accessibility: {
        focusVisible: true,
        keyboardNavigation: true,
        screenReader: true,
        highContrast: false
    },
    
    // Configuración de SEO
    seo: {
        title: 'Para Ti, Mi Poesía - Santuario Digital de Versos',
        description: 'Un santuario digital donde los versos florecen y las emociones encuentran su voz. Explora poemas de amor, naturaleza y melancolía.',
        keywords: ['poesía', 'poemas', 'versos', 'amor', 'naturaleza', 'melancolía', 'literatura'],
        ogImage: '/images/og-image.jpg',
        twitterCard: 'summary_large_image'
    },
    
    // Configuración de analytics
    analytics: {
        enabled: false,
        trackingId: null,
        events: {
            pageView: true,
            scroll: true,
            click: true,
            hover: true
        }
    },
    
    // Configuración de errores
    errorHandling: {
        showUserFriendly: true,
        logToConsole: true,
        reportErrors: false,
        fallbackPage: 'index.html'
    },
    
    // Configuración de cache
    cache: {
        enabled: true,
        strategy: 'stale-while-revalidate',
        maxAge: 3600000, // 1 hora
        maxEntries: 100
    },
    
    // Configuración de notificaciones
    notifications: {
        enabled: true,
        position: 'top-right',
        duration: 5000,
        types: ['success', 'error', 'warning', 'info']
    },
    
    // Configuración de validación
    validation: {
        enabled: true,
        realTime: true,
        showErrors: true,
        debounce: 300
    },
    
    // Configuración de internacionalización
    i18n: {
        defaultLanguage: 'es',
        supportedLanguages: ['es', 'en'],
        fallbackLanguage: 'es',
        loadPath: '/locales/{{lng}}.json'
    },
    
    // Configuración de PWA
    pwa: {
        enabled: false,
        name: 'Para Ti, Mi Poesía',
        shortName: 'Poemas',
        description: 'Santuario Digital de Versos',
        themeColor: '#6B7A40',
        backgroundColor: '#FDFCF7',
        display: 'standalone',
        orientation: 'portrait'
    },
    
    // Configuración de desarrollo
    development: {
        hotReload: false,
        sourceMaps: true,
        verbose: true,
        mockData: true
    },
    
    // Configuración de producción
    production: {
        minify: true,
        compress: true,
        optimize: true,
        cdn: true
    }
};

// Configuración específica por entorno
if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    // Configuración de desarrollo
    window.AppConfig.debug = true;
    window.AppConfig.environment = 'development';
    window.AppConfig.api.baseUrl = 'http://localhost/api/v1';
} else {
    // Configuración de producción
    window.AppConfig.debug = false;
    window.AppConfig.environment = 'production';
    window.AppConfig.api.baseUrl = '/api/v1';
}

// Configuración específica por dispositivo
if (window.innerWidth < 768) {
    // Configuración para móviles
    window.AppConfig.threejs.particles.count = 50;
    window.AppConfig.threejs.particles.size = 0.03;
    window.AppConfig.animations.duration.fast = 0.15;
    window.AppConfig.animations.duration.normal = 0.25;
    window.AppConfig.animations.duration.slow = 0.4;
} else if (window.innerWidth < 1024) {
    // Configuración para tablets
    window.AppConfig.threejs.particles.count = 75;
    window.AppConfig.threejs.particles.size = 0.04;
} else {
    // Configuración para desktop
    window.AppConfig.threejs.particles.count = 100;
    window.AppConfig.threejs.particles.size = 0.05;
}

// Configuración específica por preferencias del usuario
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    // Reducir animaciones para usuarios que prefieren menos movimiento
    window.AppConfig.animations.enabled = false;
    window.AppConfig.effects.particles = false;
    window.AppConfig.effects.floatingHearts = false;
    window.AppConfig.effects.romanticGlow = false;
}

if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    // Modo oscuro por defecto si el usuario lo prefiere
    window.AppConfig.themes.default = 'dark';
}

// Configuración específica por conexión
if (navigator.connection) {
    const connection = navigator.connection;
    
    if (connection.effectiveType === 'slow-2g' || connection.effectiveType === '2g') {
        // Conexión lenta - reducir efectos
        window.AppConfig.threejs.enabled = false;
        window.AppConfig.effects.particles = false;
        window.AppConfig.effects.floatingHearts = false;
        window.AppConfig.threejs.particles.count = 25;
    } else if (connection.effectiveType === '3g') {
        // Conexión media - reducir partículas
        window.AppConfig.threejs.particles.count = 50;
    }
}

// Exportar configuración
console.log('⚙️ Configuración de la aplicación cargada:', window.AppConfig);