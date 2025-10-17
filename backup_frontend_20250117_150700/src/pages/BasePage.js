/**
 * Clase base para todas las páginas
 * Implementa el patrón MVC para páginas
 */
class BasePage {
    constructor(options = {}) {
        this.options = { ...this.defaultOptions, ...options };
        this.components = new Map();
        this.data = {};
        this.isInitialized = false;
        this.isLoading = false;
        
        this.init();
    }

    get defaultOptions() {
        return {
            autoLoad: true,
            debug: false,
            cacheData: true,
            showLoading: true
        };
    }

    /**
     * Inicializa la página
     */
    async init() {
        if (this.isInitialized) return;
        
        this.log('Inicializando página...');
        
        try {
            this.setupEventListeners();
            await this.loadData();
            this.render();
            this.isInitialized = true;
            this.log('Página inicializada correctamente');
        } catch (error) {
            this.handleError('Error al inicializar página:', error);
        }
    }

    /**
     * Carga los datos necesarios para la página
     */
    async loadData() {
        if (!this.options.autoLoad) return;
        
        this.log('Cargando datos...');
        this.setLoading(true);
        
        try {
            // Método que debe ser implementado por las páginas hijas
            await this.fetchData();
            this.log('Datos cargados correctamente');
        } catch (error) {
            this.handleError('Error al cargar datos:', error);
        } finally {
            this.setLoading(false);
        }
    }

    /**
     * Método que debe ser implementado por las páginas hijas
     */
    async fetchData() {
        // Implementar en clases hijas
    }

    /**
     * Renderiza la página
     */
    render() {
        this.log('Renderizando página...');
        this.beforeRender();
        this.renderContent();
        this.afterRender();
    }

    /**
     * Métodos que pueden ser sobrescritos por las clases hijas
     */
    beforeRender() {
        // Implementar en clases hijas si es necesario
    }
    
    renderContent() {
        // Implementar en clases hijas
    }
    
    afterRender() {
        // Implementar en clases hijas si es necesario
    }

    /**
     * Configura los event listeners
     */
    setupEventListeners() {
        // Event listeners globales
        window.addEventListener('routechange', (e) => {
            this.handleRouteChange(e.detail);
        });

        // Implementar en clases hijas
        this.setupPageEventListeners();
    }

    /**
     * Método para que las páginas hijas configuren sus event listeners
     */
    setupPageEventListeners() {
        // Implementar en clases hijas
    }

    /**
     * Maneja el cambio de ruta
     */
    handleRouteChange(routeData) {
        this.log('Cambio de ruta detectado:', routeData);
        // Implementar lógica específica si es necesario
    }

    /**
     * Actualiza los datos de la página
     */
    async updateData(newData = {}) {
        this.log('Actualizando datos...');
        this.data = { ...this.data, ...newData };
        
        if (this.options.autoLoad) {
            await this.loadData();
        } else {
            this.render();
        }
    }

    /**
     * Añade un componente a la página
     */
    addComponent(name, component) {
        this.components.set(name, component);
        this.log(`Componente '${name}' añadido`);
    }

    /**
     * Obtiene un componente por nombre
     */
    getComponent(name) {
        return this.components.get(name);
    }

    /**
     * Remueve un componente
     */
    removeComponent(name) {
        const component = this.components.get(name);
        if (component && typeof component.destroy === 'function') {
            component.destroy();
        }
        this.components.delete(name);
        this.log(`Componente '${name}' removido`);
    }

    /**
     * Establece el estado de carga
     */
    setLoading(loading) {
        this.isLoading = loading;
        const loadingElement = document.getElementById('loading');
        
        if (loadingElement) {
            loadingElement.style.display = loading ? 'block' : 'none';
        }
        
        if (loading) {
            document.body.classList.add('loading');
        } else {
            document.body.classList.remove('loading');
        }
    }

    /**
     * Maneja errores
     */
    handleError(message, error) {
        console.error(`[${this.constructor.name}] ${message}`, error);
        
        // Mostrar mensaje de error al usuario
        this.showErrorMessage(message);
    }

    /**
     * Muestra un mensaje de error
     */
    showErrorMessage(message) {
        // Crear elemento de error si no existe
        let errorElement = document.getElementById('error-message');
        
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.id = 'error-message';
            errorElement.className = 'fixed top-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg z-50';
            document.body.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        
        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            errorElement.style.display = 'none';
        }, 5000);
    }

    /**
     * Muestra un mensaje de éxito
     */
    showSuccessMessage(message) {
        let successElement = document.getElementById('success-message');
        
        if (!successElement) {
            successElement = document.createElement('div');
            successElement.id = 'success-message';
            successElement.className = 'fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg z-50';
            document.body.appendChild(successElement);
        }
        
        successElement.textContent = message;
        successElement.style.display = 'block';
        
        setTimeout(() => {
            successElement.style.display = 'none';
        }, 3000);
    }

    /**
     * Destruye la página
     */
    destroy() {
        this.log('Destruyendo página...');
        
        // Destruir todos los componentes
        for (const [name, component] of this.components) {
            this.removeComponent(name);
        }
        
        // Limpiar event listeners
        this.removeEventListeners();
        
        this.isInitialized = false;
    }

    /**
     * Remueve los event listeners
     */
    removeEventListeners() {
        // Implementar en clases hijas si es necesario
    }

    /**
     * Logging para debug
     */
    log(message, data = null) {
        if (this.options.debug) {
            console.log(`[${this.constructor.name}] ${message}`, data);
        }
    }

    /**
     * Obtiene datos del localStorage
     */
    getStoredData(key) {
        try {
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : null;
        } catch (error) {
            this.log('Error al obtener datos del localStorage:', error);
            return null;
        }
    }

    /**
     * Guarda datos en el localStorage
     */
    setStoredData(key, data) {
        try {
            localStorage.setItem(key, JSON.stringify(data));
            return true;
        } catch (error) {
            this.log('Error al guardar datos en localStorage:', error);
            return false;
        }
    }
}

// Hacer la clase disponible globalmente
window.BasePage = BasePage;
