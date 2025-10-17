/**
 * Servicio de almacenamiento local
 * Maneja localStorage y sessionStorage de forma segura
 */
class StorageService {
    constructor() {
        this.storage = window.localStorage;
        this.sessionStorage = window.sessionStorage;
        this.prefix = 'plataforma_poemas_';
    }

    /**
     * Genera una clave con prefijo
     * @param {string} key - Clave original
     * @returns {string} Clave con prefijo
     */
    getKey(key) {
        return `${this.prefix}${key}`;
    }

    /**
     * Almacena un valor en localStorage
     * @param {string} key - Clave
     * @param {*} value - Valor a almacenar
     * @returns {boolean} True si se almacenó correctamente
     */
    setItem(key, value) {
        try {
            const serializedValue = JSON.stringify(value);
            this.storage.setItem(this.getKey(key), serializedValue);
            return true;
        } catch (error) {
            console.error('Error al almacenar en localStorage:', error);
            return false;
        }
    }

    /**
     * Obtiene un valor de localStorage
     * @param {string} key - Clave
     * @param {*} defaultValue - Valor por defecto
     * @returns {*} Valor almacenado o valor por defecto
     */
    getItem(key, defaultValue = null) {
        try {
            const item = this.storage.getItem(this.getKey(key));
            return item ? JSON.parse(item) : defaultValue;
        } catch (error) {
            console.error('Error al obtener de localStorage:', error);
            return defaultValue;
        }
    }

    /**
     * Elimina un elemento de localStorage
     * @param {string} key - Clave
     * @returns {boolean} True si se eliminó correctamente
     */
    removeItem(key) {
        try {
            this.storage.removeItem(this.getKey(key));
            return true;
        } catch (error) {
            console.error('Error al eliminar de localStorage:', error);
            return false;
        }
    }

    /**
     * Limpia todo el almacenamiento con prefijo
     * @returns {boolean} True si se limpió correctamente
     */
    clear() {
        try {
            const keys = Object.keys(this.storage);
            keys.forEach(key => {
                if (key.startsWith(this.prefix)) {
                    this.storage.removeItem(key);
                }
            });
            return true;
        } catch (error) {
            console.error('Error al limpiar localStorage:', error);
            return false;
        }
    }

    /**
     * Almacena un valor en sessionStorage
     * @param {string} key - Clave
     * @param {*} value - Valor a almacenar
     * @returns {boolean} True si se almacenó correctamente
     */
    setSessionItem(key, value) {
        try {
            const serializedValue = JSON.stringify(value);
            this.sessionStorage.setItem(this.getKey(key), serializedValue);
            return true;
        } catch (error) {
            console.error('Error al almacenar en sessionStorage:', error);
            return false;
        }
    }

    /**
     * Obtiene un valor de sessionStorage
     * @param {string} key - Clave
     * @param {*} defaultValue - Valor por defecto
     * @returns {*} Valor almacenado o valor por defecto
     */
    getSessionItem(key, defaultValue = null) {
        try {
            const item = this.sessionStorage.getItem(this.getKey(key));
            return item ? JSON.parse(item) : defaultValue;
        } catch (error) {
            console.error('Error al obtener de sessionStorage:', error);
            return defaultValue;
        }
    }

    /**
     * Verifica si existe una clave
     * @param {string} key - Clave
     * @returns {boolean} True si existe
     */
    hasItem(key) {
        return this.storage.getItem(this.getKey(key)) !== null;
    }

    /**
     * Obtiene todas las claves con prefijo
     * @returns {Array} Array de claves
     */
    getKeys() {
        const keys = [];
        for (let i = 0; i < this.storage.length; i++) {
            const key = this.storage.key(i);
            if (key && key.startsWith(this.prefix)) {
                keys.push(key.replace(this.prefix, ''));
            }
        }
        return keys;
    }

    /**
     * Obtiene el tamaño del almacenamiento usado
     * @returns {number} Tamaño en bytes
     */
    getStorageSize() {
        let total = 0;
        for (let key in this.storage) {
            if (this.storage.hasOwnProperty(key)) {
                total += this.storage[key].length + key.length;
            }
        }
        return total;
    }
}

// Crear instancia global
window.StorageService = new StorageService();
