/**
 * Clase base para todos los componentes
 * Implementa el patrón MVC para componentes reutilizables
 */
class BaseComponent {
    constructor(elementId, options = {}) {
        this.elementId = elementId;
        this.element = document.getElementById(elementId);
        this.options = { ...this.defaultOptions, ...options };
        this.isInitialized = false;
        
        if (this.element) {
            this.init();
        } else {
            console.warn(`Elemento con ID '${elementId}' no encontrado`);
        }
    }

    get defaultOptions() {
        return {
            autoRender: true,
            debug: false
        };
    }

    /**
     * Inicializa el componente
     */
    init() {
        if (this.isInitialized) return;
        
        this.log('Inicializando componente...');
        this.setupEventListeners();
        this.render();
        this.isInitialized = true;
        this.log('Componente inicializado');
    }

    /**
     * Renderiza el componente
     */
    render() {
        if (!this.options.autoRender) return;
        
        this.log('Renderizando componente...');
        this.beforeRender();
        this.renderContent();
        this.afterRender();
    }

    /**
     * Métodos que pueden ser sobrescritos por las clases hijas
     */
    beforeRender() {}
    
    renderContent() {
        // Implementar en clases hijas
    }
    
    afterRender() {}

    /**
     * Configura los event listeners
     */
    setupEventListeners() {
        // Implementar en clases hijas
    }

    /**
     * Actualiza el componente
     */
    update(newData = {}) {
        this.log('Actualizando componente...');
        this.data = { ...this.data, ...newData };
        this.render();
    }

    /**
     * Destruye el componente
     */
    destroy() {
        this.log('Destruyendo componente...');
        this.removeEventListeners();
        this.element.innerHTML = '';
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
     * Utilidades para manipulación del DOM
     */
    querySelector(selector) {
        return this.element.querySelector(selector);
    }

    querySelectorAll(selector) {
        return this.element.querySelectorAll(selector);
    }

    addClass(className) {
        this.element.classList.add(className);
    }

    removeClass(className) {
        this.element.classList.remove(className);
    }

    toggleClass(className) {
        this.element.classList.toggle(className);
    }

    setHTML(html) {
        this.element.innerHTML = html;
    }

    appendHTML(html) {
        this.element.insertAdjacentHTML('beforeend', html);
    }

    /**
     * Emite eventos personalizados
     */
    emit(eventName, detail = {}) {
        const event = new CustomEvent(eventName, {
            detail,
            bubbles: true
        });
        this.element.dispatchEvent(event);
    }

    /**
     * Escucha eventos personalizados
     */
    on(eventName, selector, callback) {
        if (typeof selector === 'function') {
            // Si solo se pasa eventName y callback
            this.element.addEventListener(eventName, selector);
        } else {
            // Si se pasa eventName, selector y callback
            this.element.addEventListener(eventName, (e) => {
                if (e.target.matches(selector)) {
                    callback(e);
                }
            });
        }
    }

    /**
     * Busca un template por ID
     */
    getTemplate(templateId) {
        const template = document.getElementById(templateId);
        if (!template) {
            console.warn(`Template '${templateId}' no encontrado`);
            return '';
        }
        return template.innerHTML;
    }
}

// Hacer la clase disponible globalmente
window.BaseComponent = BaseComponent;
