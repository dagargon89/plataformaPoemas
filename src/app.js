/**
 * Aplicación principal del Sistema de Poemas - Versión Modernizada
 * Integración con GSAP y Three.js para efectos dinámicos
 * Última actualización: 2025
 */
class App {
    constructor() {
        this.config = window.AppConfig || {};
        this.currentPage = null;
        this.components = new Map();
        this.isInitialized = false;
        this.themeService = null;
        this.threeScene = null;
        this.gsapTimeline = null;
        
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
            this.initializeGSAP();
            this.initializeThreeJS();
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
     * Inicializa GSAP
     */
    initializeGSAP() {
        console.log('🎬 Inicializando GSAP...');
        
        // Configuración global de GSAP
        gsap.config({
            nullTargetWarn: false,
            trialWarn: false
        });
        
        // Timeline principal
        this.gsapTimeline = gsap.timeline();
        
        console.log('✅ GSAP inicializado');
    }

    /**
     * Inicializa Three.js
     */
    initializeThreeJS() {
        console.log('🎨 Inicializando Three.js...');
        
        const canvas = document.getElementById('three-canvas');
        if (!canvas) {
            console.log('⚠️ Canvas de Three.js no encontrado');
            return;
        }

        // Escena
        this.threeScene = new THREE.Scene();
        
        // Cámara
        const camera = new THREE.PerspectiveCamera(
            75, 
            window.innerWidth / window.innerHeight, 
            0.1, 
            1000
        );
        
        // Renderer
        const renderer = new THREE.WebGLRenderer({ 
            canvas: canvas, 
            alpha: true,
            antialias: true
        });
        
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setClearColor(0x000000, 0);
        renderer.setPixelRatio(window.devicePixelRatio);
        
        // Crear partículas flotantes
        this.createFloatingParticles();
        
        // Posicionar cámara
        camera.position.z = 5;
        
        // Función de animación
        const animate = () => {
            requestAnimationFrame(animate);
            this.animateThreeJS();
            renderer.render(this.threeScene, camera);
        };
        
        animate();
        
        // Redimensionar canvas
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });
        
        console.log('✅ Three.js inicializado');
    }

    /**
     * Crea partículas flotantes
     */
    createFloatingParticles() {
        const particlesGeometry = new THREE.BufferGeometry();
        const particlesCount = 100;
        const posArray = new Float32Array(particlesCount * 3);
        
        for (let i = 0; i < particlesCount * 3; i++) {
            posArray[i] = (Math.random() - 0.5) * 10;
        }
        
        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        
        const particlesMaterial = new THREE.PointsMaterial({
            size: 0.05,
            color: 0x6B7A40,
            transparent: true,
            opacity: 0.6,
            blending: THREE.AdditiveBlending
        });
        
        this.particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
        this.threeScene.add(this.particlesMesh);
    }

    /**
     * Anima elementos de Three.js
     */
    animateThreeJS() {
        if (this.particlesMesh) {
            this.particlesMesh.rotation.y += 0.001;
            this.particlesMesh.rotation.x += 0.0005;
        }
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

        // Event listener para scroll
        window.addEventListener('scroll', () => {
            this.handleScroll();
        });

        // Event listener para resize
        window.addEventListener('resize', () => {
            this.handleResize();
        });
    }

    /**
     * Inicializa los componentes globales
     */
    initializeComponents() {
        console.log('🔧 Inicializando componentes...');
        
        // Inicializar componentes específicos de la página
        this.initializePageComponents();
        
        console.log('✅ Componentes inicializados');
    }

    /**
     * Inicializa componentes específicos de la página
     */
    initializePageComponents() {
        const currentPage = window.location.pathname.split('/').pop();
        
        switch (currentPage) {
            case 'index.html':
            case '':
                this.initializeHomePageComponents();
                break;
            case 'libro-tradicional.html':
                this.initializeBookPageComponents();
                break;
            case 'lista-scroll.html':
                this.initializeListPageComponents();
                break;
            case 'tarjetas-poemas.html':
                this.initializeCardsPageComponents();
                break;
        }
    }

    /**
     * Inicializa componentes de la página de inicio
     */
    initializeHomePageComponents() {
        // Animación de entrada
        gsap.from("body", {
            opacity: 0,
            y: 20,
            duration: 1,
            ease: "power2.out"
        });
        
        // Animación de elementos con delay
        gsap.from(".fade-in-down", {
            opacity: 0,
            y: -20,
            duration: 1,
            delay: 0.2,
            ease: "power2.out"
        });
        
        gsap.from(".fade-in-up", {
            opacity: 0,
            y: 20,
            duration: 1,
            delay: 0.4,
            stagger: 0.2,
            ease: "power2.out"
        });

        // Efectos hover para tarjetas
        this.setupCardHoverEffects();
    }

    /**
     * Inicializa componentes de la página de libro
     */
    initializeBookPageComponents() {
        // Animación de entrada
        gsap.from("body", {
            opacity: 0,
            y: 20,
            duration: 1,
            ease: "power2.out"
        });
        
        // Animación del libro
        gsap.from(".book-container", {
            opacity: 0,
            scale: 0.9,
            duration: 1.5,
            delay: 0.3,
            ease: "power2.out"
        });

        // Efectos de página
        this.setupBookPageEffects();
    }

    /**
     * Inicializa componentes de la página de lista
     */
    initializeListPageComponents() {
        // Animación de entrada
        gsap.from("body", {
            opacity: 0,
            y: 20,
            duration: 1,
            ease: "power2.out"
        });
        
        // Animación de elementos con stagger
        gsap.from(".fade-in", {
            opacity: 0,
            y: 20,
            duration: 0.8,
            stagger: 0.2,
            ease: "power2.out"
        });

        // Efectos de scroll infinito
        this.setupInfiniteScroll();
    }

    /**
     * Inicializa componentes de la página de tarjetas
     */
    initializeCardsPageComponents() {
        // Animación de entrada
        gsap.from("body", {
            opacity: 0,
            y: 20,
            duration: 1,
            ease: "power2.out"
        });
        
        // Animación de tarjetas con stagger
        gsap.from(".grid > div", {
            opacity: 0,
            y: 30,
            scale: 0.9,
            duration: 0.8,
            stagger: 0.1,
            ease: "power2.out",
            delay: 0.3
        });

        // Efectos de tarjetas
        this.setupCardEffects();
    }

    /**
     * Configura efectos hover para tarjetas
     */
    setupCardHoverEffects() {
        const cards = document.querySelectorAll('.card-hover, .hover-lift');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                gsap.to(card, {
                    y: -12,
                    scale: 1.02,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
            
            card.addEventListener('mouseleave', () => {
                gsap.to(card, {
                    y: 0,
                    scale: 1,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });
    }

    /**
     * Configura efectos de página de libro
     */
    setupBookPageEffects() {
        const bookContainer = document.querySelector('.book-container');
        if (!bookContainer) return;

        // Efecto de hover para voltear páginas
        bookContainer.addEventListener('mouseenter', () => {
            gsap.to('.book-page-front', {
                rotationY: -180,
                duration: 1,
                ease: "power2.inOut"
            });
        });

        bookContainer.addEventListener('mouseleave', () => {
            gsap.to('.book-page-front', {
                rotationY: 0,
                duration: 1,
                ease: "power2.inOut"
            });
        });
    }

    /**
     * Configura scroll infinito
     */
    setupInfiniteScroll() {
        let isLoading = false;
        
        window.addEventListener('scroll', () => {
            if (isLoading) return;
            
            const scrollTop = window.pageYOffset;
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            
            if (scrollTop + windowHeight >= documentHeight - 100) {
                isLoading = true;
                this.loadMoreContent();
            }
        });
    }

    /**
     * Carga más contenido para scroll infinito
     */
    loadMoreContent() {
        console.log('📄 Cargando más contenido...');
        
        // Simular carga
        setTimeout(() => {
            // Aquí se cargaría más contenido
            console.log('✅ Contenido cargado');
            isLoading = false;
        }, 1000);
    }

    /**
     * Configura efectos de tarjetas
     */
    setupCardEffects() {
        const cards = document.querySelectorAll('.grid > div');
        
        cards.forEach((card, index) => {
            // Efecto de entrada con delay
            gsap.from(card, {
                opacity: 0,
                y: 30,
                scale: 0.9,
                duration: 0.8,
                delay: index * 0.1,
                ease: "power2.out"
            });
            
            // Efecto hover
            card.addEventListener('mouseenter', () => {
                gsap.to(card, {
                    y: -8,
                    scale: 1.02,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
            
            card.addEventListener('mouseleave', () => {
                gsap.to(card, {
                    y: 0,
                    scale: 1,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });
    }

    /**
     * Maneja el scroll
     */
    handleScroll() {
        const scrollTop = window.pageYOffset;
        const windowHeight = window.innerHeight;
        
        // Efecto parallax para elementos de fondo
        const parallaxElements = document.querySelectorAll('.parallax');
        parallaxElements.forEach(element => {
            const speed = element.dataset.speed || 0.5;
            const yPos = -(scrollTop * speed);
            gsap.set(element, { y: yPos });
        });
        
        // Efecto de fade in para elementos
        const fadeElements = document.querySelectorAll('.fade-on-scroll');
        fadeElements.forEach(element => {
            const elementTop = element.offsetTop;
            const elementHeight = element.offsetHeight;
            
            if (scrollTop > elementTop - windowHeight + elementHeight) {
                gsap.to(element, {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    ease: "power2.out"
                });
            }
        });
    }

    /**
     * Maneja el redimensionamiento
     */
    handleResize() {
        // Reajustar Three.js
        if (this.threeScene) {
            const camera = this.threeScene.children.find(child => child.type === 'PerspectiveCamera');
            if (camera) {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
            }
        }
        
        // Reajustar GSAP
        gsap.set("body", { clearProps: "all" });
    }

    /**
     * Carga la página actual
     */
    async loadCurrentPage() {
        const currentRoute = window.router?.getCurrentRoute();
        
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
                    console.log('⚠️ Página de tarjetas no implementada aún');
                    return;
                case 'lista':
                    console.log('⚠️ Página de lista no implementada aún');
                    return;
                case 'libro':
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
        this.showCriticalError();
    }

    /**
     * Maneja errores globales del navegador
     */
    handleGlobalError(event) {
        console.error('💥 Error global:', event.error);
        this.reportError('Global Error', event.error);
    }

    /**
     * Maneja promesas rechazadas no capturadas
     */
    handlePromiseRejection(event) {
        console.error('💥 Promesa rechazada:', event.reason);
        this.reportError('Unhandled Promise Rejection', event.reason);
    }

    /**
     * Maneja errores de páginas específicas
     */
    handlePageError(pageName, error) {
        console.error(`💥 Error en página '${pageName}':`, error);
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
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-light transition-colors">
                            🏠 Ir al Inicio
                        </button>
                        <button onclick="window.location.reload()" 
                                class="bg-secondary text-primary px-4 py-2 rounded-lg hover:bg-accent transition-colors">
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
     * Reporta errores
     */
    reportError(type, error) {
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
            components: Array.from(this.components.keys()),
            gsapVersion: gsap.version,
            threeVersion: THREE.REVISION
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
        
        // Limpiar GSAP
        if (this.gsapTimeline) {
            this.gsapTimeline.kill();
        }
        
        // Limpiar Three.js
        if (this.threeScene) {
            this.threeScene.clear();
        }
    }
}

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.app = new App();
});

// Exportar para uso global
window.App = App;