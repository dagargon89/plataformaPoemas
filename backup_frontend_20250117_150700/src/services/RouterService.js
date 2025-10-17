/**
 * Servicio de enrutamiento
 * Maneja la navegación y el estado de la aplicación
 */
class RouterService {
    constructor() {
        this.routes = new Map();
        this.currentRoute = null;
        this.history = [];
        this.maxHistorySize = 10;
        
        this.init();
    }

    /**
     * Inicializa el router
     */
    init() {
        this.setupDefaultRoutes();
        this.setupPopstateListener();
        this.navigateToCurrentRoute();
    }

    /**
     * Configura las rutas por defecto
     */
    setupDefaultRoutes() {
        this.addRoute('index', {
            path: '/',
            component: 'HomePage',
            title: 'Sistema de Poemas - Inicio'
        });

        this.addRoute('tarjetas', {
            path: '/tarjetas-poemas.html',
            component: 'TarjetasPage',
            title: 'Vista Tarjetas - Poemas'
        });

        this.addRoute('lista', {
            path: '/lista-scroll.html',
            component: 'ListaPage',
            title: 'Vista Lista - Poemas'
        });

        this.addRoute('libro', {
            path: '/libro-tradicional.html',
            component: 'LibroPage',
            title: 'Vista Libro - Poemas'
        });

        this.addRoute('admin', {
            path: '/admin/',
            component: null,
            title: 'Panel Administrativo'
        });
    }

    /**
     * Añade una nueva ruta
     */
    addRoute(name, route) {
        this.routes.set(name, {
            name,
            ...route,
            params: route.params || {}
        });
    }

    /**
     * Navega a una ruta específica
     */
    navigateTo(routeName, params = {}) {
        const route = this.routes.get(routeName);
        
        if (!route) {
            console.error(`Ruta '${routeName}' no encontrada`);
            return false;
        }

        // Añadir a historial
        this.addToHistory(this.currentRoute);
        
        // Actualizar ruta actual
        this.currentRoute = { ...route, params };
        
        // Actualizar título
        document.title = route.title;
        
        // Emitir evento de cambio de ruta
        this.emitRouteChange(route);
        
        return true;
    }

    /**
     * Navega usando el navegador
     */
    navigateToUrl(url) {
        window.location.href = url;
    }

    /**
     * Navega a la ruta actual
     */
    navigateToCurrentRoute() {
        const currentPath = window.location.pathname;
        const currentRouteName = this.getRouteNameByPath(currentPath);
        
        if (currentRouteName) {
            this.navigateTo(currentRouteName);
        }
    }

    /**
     * Obtiene el nombre de ruta por path
     */
    getRouteNameByPath(path) {
        for (const [name, route] of this.routes) {
            if (route.path === path || path.includes(route.path)) {
                return name;
            }
        }
        return null;
    }

    /**
     * Obtiene la ruta actual
     */
    getCurrentRoute() {
        return this.currentRoute;
    }

    /**
     * Obtiene parámetros de la ruta actual
     */
    getCurrentParams() {
        return this.currentRoute ? this.currentRoute.params : {};
    }

    /**
     * Añade una entrada al historial
     */
    addToHistory(route) {
        if (route) {
            this.history.unshift(route);
            
            // Mantener tamaño máximo del historial
            if (this.history.length > this.maxHistorySize) {
                this.history = this.history.slice(0, this.maxHistorySize);
            }
        }
    }

    /**
     * Navega hacia atrás
     */
    goBack() {
        if (this.history.length > 0) {
            const previousRoute = this.history.shift();
            this.navigateTo(previousRoute.name, previousRoute.params);
        } else {
            window.history.back();
        }
    }

    /**
     * Obtiene el historial
     */
    getHistory() {
        return [...this.history];
    }

    /**
     * Configura el listener de popstate
     */
    setupPopstateListener() {
        window.addEventListener('popstate', (e) => {
            this.navigateToCurrentRoute();
        });
    }

    /**
     * Emite evento de cambio de ruta
     */
    emitRouteChange(route) {
        const event = new CustomEvent('routechange', {
            detail: {
                route,
                params: this.getCurrentParams()
            }
        });
        window.dispatchEvent(event);
    }

    /**
     * Obtiene todas las rutas disponibles
     */
    getAllRoutes() {
        return Array.from(this.routes.values());
    }

    /**
     * Verifica si una ruta existe
     */
    hasRoute(routeName) {
        return this.routes.has(routeName);
    }

    /**
     * Obtiene información de una ruta
     */
    getRoute(routeName) {
        return this.routes.get(routeName);
    }
}

// Hacer la clase disponible globalmente
window.RouterService = RouterService;

// Instancia singleton del router
window.router = new RouterService();
