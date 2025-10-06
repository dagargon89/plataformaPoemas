/**
 * JavaScript para el Panel Administrativo
 * Funciones auxiliares y utilidades
 */

// Utilidades globales para el panel administrativo
window.AdminUtils = {
    
    /**
     * Formatear fecha para mostrar
     */
    formatDate: function(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    },

    /**
     * Formatear fecha y hora
     */
    formatDateTime: function(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleString('es-ES', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },

    /**
     * Mostrar notificación de éxito
     */
    showSuccess: function(message, duration = 3000) {
        this.showNotification(message, 'success', duration);
    },

    /**
     * Mostrar notificación de error
     */
    showError: function(message, duration = 5000) {
        this.showNotification(message, 'error', duration);
    },

    /**
     * Mostrar notificación
     */
    showNotification: function(message, type = 'info', duration = 3000) {
        // Crear contenedor si no existe
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }

        // Crear notificación
        const notification = document.createElement('div');
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };
        
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };

        notification.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
        notification.innerHTML = `
            <div class="flex items-center">
                <span class="text-lg mr-2">${icons[type]}</span>
                <span>${message}</span>
            </div>
        `;

        container.appendChild(notification);

        // Animar entrada
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto-remover
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, duration);
    },

    /**
     * Confirmar acción
     */
    confirm: function(message, callback) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="text-center">
                        <div class="text-4xl mb-4">⚠️</div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Confirmar Acción</h2>
                        <p class="text-gray-600 mb-6">${message}</p>
                        <div class="flex justify-center space-x-4">
                            <button class="cancel-btn px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button class="confirm-btn px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                                Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Event listeners
        modal.querySelector('.cancel-btn').addEventListener('click', () => {
            document.body.removeChild(modal);
        });

        modal.querySelector('.confirm-btn').addEventListener('click', () => {
            document.body.removeChild(modal);
            if (callback) callback();
        });

        // Cerrar al hacer click fuera
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });
    },

    /**
     * Cargar datos con loading
     */
    async fetchWithLoading(url, options = {}) {
        const loadingElement = this.showLoading();
        
        try {
            const response = await fetch(url, options);
            const data = await response.json();
            return data;
        } finally {
            this.hideLoading(loadingElement);
        }
    },

    /**
     * Mostrar loading
     */
    showLoading: function() {
        const loading = document.createElement('div');
        loading.id = 'admin-loading';
        loading.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
        loading.innerHTML = `
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-verde-principal"></div>
                <span class="text-gray-700">Cargando...</span>
            </div>
        `;
        document.body.appendChild(loading);
        return loading;
    },

    /**
     * Ocultar loading
     */
    hideLoading: function(element) {
        if (element && element.parentNode) {
            element.parentNode.removeChild(element);
        }
    },

    /**
     * Validar formulario
     */
    validateForm: function(formElement) {
        const errors = [];
        const requiredFields = formElement.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                errors.push(`El campo "${field.previousElementSibling?.textContent || field.name}" es requerido`);
                field.classList.add('border-red-500');
            } else {
                field.classList.remove('border-red-500');
            }
        });

        // Validaciones específicas
        const emailFields = formElement.querySelectorAll('input[type="email"]');
        emailFields.forEach(field => {
            if (field.value && !this.isValidEmail(field.value)) {
                errors.push(`El email "${field.value}" no es válido`);
                field.classList.add('border-red-500');
            }
        });

        return errors;
    },

    /**
     * Validar email
     */
    isValidEmail: function(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },

    /**
     * Debounce para búsquedas
     */
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Copiar al portapapeles
     */
    copyToClipboard: function(text) {
        navigator.clipboard.writeText(text).then(() => {
            this.showSuccess('Copiado al portapapeles');
        }).catch(() => {
            this.showError('Error al copiar al portapapeles');
        });
    },

    /**
     * Exportar datos a JSON
     */
    exportToJSON: function(data, filename = 'data.json') {
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    },

    /**
     * Importar archivo JSON
     */
    importFromJSON: function(file, callback) {
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const data = JSON.parse(e.target.result);
                if (callback) callback(data);
            } catch (error) {
                AdminUtils.showError('Error al leer el archivo JSON: ' + error.message);
            }
        };
        reader.readAsText(file);
    },

    /**
     * Generar slug desde texto
     */
    generateSlug: function(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    },

    /**
     * Truncar texto
     */
    truncateText: function(text, maxLength = 100) {
        if (text.length <= maxLength) return text;
        return text.substr(0, maxLength) + '...';
    },

    /**
     * Obtener color contrastante
     */
    getContrastColor: function(hexColor) {
        // Remover # si está presente
        const color = hexColor.replace('#', '');
        
        // Convertir a RGB
        const r = parseInt(color.substr(0, 2), 16);
        const g = parseInt(color.substr(2, 2), 16);
        const b = parseInt(color.substr(4, 2), 16);
        
        // Calcular luminancia
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        
        return luminance > 0.5 ? '#000000' : '#FFFFFF';
    },

    /**
     * Formatear número con separadores de miles
     */
    formatNumber: function(num) {
        return new Intl.NumberFormat('es-ES').format(num);
    },

    /**
     * Obtener estadísticas de texto
     */
    getTextStats: function(text) {
        const words = text.trim().split(/\s+/).filter(word => word.length > 0);
        const chars = text.length;
        const charsNoSpaces = text.replace(/\s/g, '').length;
        const lines = text.split('\n').length;
        
        return {
            words: words.length,
            characters: chars,
            charactersNoSpaces: charsNoSpaces,
            lines: lines,
            avgWordsPerLine: words.length / lines
        };
    }
};

// Funciones específicas para el panel de poemas
window.PoemUtils = {
    
    /**
     * Calcular tiempo de lectura estimado
     */
    calculateReadingTime: function(text) {
        const wordsPerMinute = 200; // Promedio de palabras por minuto
        const words = text.trim().split(/\s+/).filter(word => word.length > 0);
        const minutes = Math.ceil(words.length / wordsPerMinute);
        return Math.max(1, minutes); // Mínimo 1 minuto
    },

    /**
     * Generar extracto del poema
     */
    generateExtract: function(content, maxLength = 150) {
        // Remover saltos de línea y espacios extra
        const cleanContent = content.replace(/\s+/g, ' ').trim();
        
        if (cleanContent.length <= maxLength) {
            return cleanContent;
        }
        
        // Buscar un punto de corte natural
        let extract = cleanContent.substr(0, maxLength);
        const lastSpace = extract.lastIndexOf(' ');
        
        if (lastSpace > maxLength * 0.8) {
            extract = extract.substr(0, lastSpace);
        }
        
        return extract + '...';
    },

    /**
     * Validar contenido de poema
     */
    validatePoemContent: function(content) {
        const errors = [];
        
        if (!content || content.trim().length < 10) {
            errors.push('El contenido debe tener al menos 10 caracteres');
        }
        
        if (content.length > 10000) {
            errors.push('El contenido no puede exceder 10,000 caracteres');
        }
        
        // Verificar si tiene al menos una línea
        const lines = content.split('\n').filter(line => line.trim().length > 0);
        if (lines.length < 2) {
            errors.push('El poema debe tener al menos 2 líneas');
        }
        
        return errors;
    }
};

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Configurar tooltips
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-50 bg-gray-900 text-white text-sm px-2 py-1 rounded shadow-lg';
            tooltip.textContent = this.dataset.tooltip;
            tooltip.style.top = this.offsetTop - 30 + 'px';
            tooltip.style.left = this.offsetLeft + 'px';
            document.body.appendChild(tooltip);
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                document.body.removeChild(this._tooltip);
                this._tooltip = null;
            }
        });
    });

    // Configurar auto-save para formularios
    const forms = document.querySelectorAll('form[data-autosave]');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        const debouncedSave = AdminUtils.debounce(() => {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            localStorage.setItem(`autosave_${form.id}`, JSON.stringify(data));
        }, 1000);

        inputs.forEach(input => {
            input.addEventListener('input', debouncedSave);
        });

        // Restaurar datos guardados
        const savedData = localStorage.getItem(`autosave_${form.id}`);
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.value = data[key];
                    }
                });
            } catch (e) {
                console.error('Error al restaurar datos autoguardados:', e);
            }
        }
    });

    // Limpiar autoguardado al enviar formulario
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            localStorage.removeItem(`autosave_${this.id}`);
        });
    });
});
