/**
 * Servicio de gestión de estado de la aplicación
 * Maneja el estado global y la comunicación entre componentes
 */
class StateService {
    constructor() {
        this.state = {
            currentTheme: 'default',
            currentView: 'home',
            userPreferences: {},
            isLoading: false,
            error: null
        };
        this.listeners = new Map();
    }

    /**
     * Obtiene el estado actual
     * @returns {Object} Estado actual
     */
    getState() {
        return { ...this.state };
    }

    /**
     * Actualiza el estado
     * @param {Object} newState - Nuevo estado a aplicar
     */
    setState(newState) {
        const prevState = { ...this.state };
        this.state = { ...this.state, ...newState };
        this.notifyListeners(prevState, this.state);
    }

    /**
     * Suscribe un listener a cambios de estado
     * @param {string} key - Clave del listener
     * @param {Function} callback - Función callback
     */
    subscribe(key, callback) {
        this.listeners.set(key, callback);
    }

    /**
     * Desuscribe un listener
     * @param {string} key - Clave del listener
     */
    unsubscribe(key) {
        this.listeners.delete(key);
    }

    /**
     * Notifica a todos los listeners sobre cambios de estado
     * @param {Object} prevState - Estado anterior
     * @param {Object} newState - Estado nuevo
     */
    notifyListeners(prevState, newState) {
        this.listeners.forEach(callback => {
            try {
                callback(newState, prevState);
            } catch (error) {
                console.error('Error en listener de estado:', error);
            }
        });
    }

    /**
     * Obtiene un valor específico del estado
     * @param {string} key - Clave del valor
     * @returns {*} Valor del estado
     */
    get(key) {
        return this.state[key];
    }

    /**
     * Establece un valor específico del estado
     * @param {string} key - Clave del valor
     * @param {*} value - Valor a establecer
     */
    set(key, value) {
        this.setState({ [key]: value });
    }

    /**
     * Limpia el estado
     */
    clear() {
        this.state = {
            currentTheme: 'default',
            currentView: 'home',
            userPreferences: {},
            isLoading: false,
            error: null
        };
        this.notifyListeners({}, this.state);
    }
}

// Crear instancia global
window.StateService = new StateService();
