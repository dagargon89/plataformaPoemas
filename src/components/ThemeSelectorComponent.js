/**
 * Componente selector de temas
 * Permite cambiar temas desde la interfaz de usuario
 */
class ThemeSelectorComponent extends BaseComponent {
    constructor(elementId, options = {}) {
        super(elementId, options);
        this.themeService = null;
        this.initThemeService();
    }

    /**
     * Inicializa el servicio de temas
     */
    initThemeService() {
        // Esperar a que el themeService esté disponible
        const checkThemeService = () => {
            if (window.themeService) {
                this.themeService = window.themeService;
                this.log('ThemeService inicializado correctamente');
                // Si el componente ya está renderizado, actualizar el contenido
                if (this.element && this.element.innerHTML) {
                    this.renderContent();
                }
            } else {
                // Reintentar en el siguiente tick
                setTimeout(checkThemeService, 10);
            }
        };
        
        checkThemeService();
    }

    get defaultOptions() {
        return {
            autoRender: true,
            debug: false,
            showPreview: true,
            showDescription: true,
            compact: false
        };
    }

    renderContent() {
        // Verificar si el themeService está disponible
        if (!this.themeService) {
            this.log('ThemeService no disponible, renderizando placeholder');
            this.setHTML('<div class="theme-selector-loading">Cargando temas...</div>');
            return;
        }

        const themes = this.themeService.getAvailableThemes();
        const currentTheme = this.themeService.getCurrentTheme();

        const themeSelectorHTML = `
            <div class="theme-selector ${this.options.compact ? 'compact' : ''}">
                ${this.options.compact ? this.renderCompactSelector(themes, currentTheme) : this.renderFullSelector(themes, currentTheme)}
            </div>
        `;

        this.setHTML(themeSelectorHTML);
    }

    /**
     * Renderiza el selector compacto
     */
    renderCompactSelector(themes, currentTheme) {
        return `
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-verde-oscuro">Tema:</label>
                <select id="theme-select" class="theme-select-compact">
                    ${themes.map(theme => `
                        <option value="${theme.key}" ${theme.key === currentTheme.name ? 'selected' : ''}>
                            ${theme.name}
                        </option>
                    `).join('')}
                </select>
                <button id="theme-random" class="theme-random-btn" title="Tema aleatorio">
                    🎲
                </button>
            </div>
        `;
    }

    /**
     * Renderiza el selector completo
     */
    renderFullSelector(themes, currentTheme) {
        return `
            <div class="theme-selector-full">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-playfair font-semibold text-verde-oscuro">
                        🎨 Seleccionar Tema
                    </h3>
                    <button id="theme-random" class="theme-random-btn-full" title="Tema aleatorio">
                        🎲 Aleatorio
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    ${themes.map(theme => this.renderThemeCard(theme, currentTheme)).join('')}
                </div>

                ${this.options.showDescription ? `
                    <div class="mt-4 p-3 bg-verde-muy-claro rounded-lg">
                        <p class="text-sm text-verde-oscuro">
                            <strong>${currentTheme.data.name}:</strong> ${currentTheme.data.description}
                        </p>
                    </div>
                ` : ''}
            </div>
        `;
    }

    /**
     * Renderiza una tarjeta de tema
     */
    renderThemeCard(theme, currentTheme) {
        const isActive = theme.key === currentTheme.name;
        
        return `
            <div class="theme-card ${isActive ? 'active' : ''}" data-theme="${theme.key}">
                <div class="theme-preview">
                    <div class="preview-colors">
                        <div class="preview-color primary" style="background-color: ${theme.colors.primary.main}"></div>
                        <div class="preview-color secondary" style="background-color: ${theme.colors.secondary.main}"></div>
                        <div class="preview-color accent" style="background-color: ${theme.colors.accent.gold}"></div>
                    </div>
                </div>
                <div class="theme-info">
                    <h4 class="theme-name">${theme.name}</h4>
                    ${this.options.showDescription ? `<p class="theme-description">${theme.description}</p>` : ''}
                </div>
                ${isActive ? '<div class="theme-active-indicator">✓</div>' : ''}
            </div>
        `;
    }

    setupEventListeners() {
        // Selector compacto
        this.on('change', '#theme-select', (e) => {
            this.changeTheme(e.target.value);
        });

        // Botón aleatorio
        this.on('click', '#theme-random, .theme-random-btn, .theme-random-btn-full', (e) => {
            e.preventDefault();
            this.randomTheme();
        });

        // Tarjetas de tema
        this.on('click', '.theme-card', (e) => {
            const themeCard = e.target.closest('.theme-card');
            if (themeCard) {
                const themeKey = themeCard.dataset.theme;
                this.changeTheme(themeKey);
            }
        });

        // Navegación con teclado
        this.on('keydown', '.theme-card', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const themeCard = e.target.closest('.theme-card');
                if (themeCard) {
                    const themeKey = themeCard.dataset.theme;
                    this.changeTheme(themeKey);
                }
            }
        });

        // Escuchar cambios de tema desde el servicio
        document.addEventListener('theme-changed', (e) => {
            this.updateActiveTheme(e.detail.theme);
        });
    }

    /**
     * Cambia el tema
     */
    changeTheme(themeKey) {
        if (!this.themeService) {
            this.log('ThemeService no disponible para cambiar tema');
            return;
        }
        this.themeService.applyTheme(themeKey);
        this.log(`Tema cambiado a: ${themeKey}`);
    }

    /**
     * Aplica un tema aleatorio
     */
    randomTheme() {
        if (!this.themeService) {
            this.log('ThemeService no disponible para tema aleatorio');
            return;
        }
        this.themeService.randomTheme();
        this.log('Tema aleatorio aplicado');
    }

    /**
     * Actualiza el tema activo en la interfaz
     */
    updateActiveTheme(themeKey) {
        // Actualizar selector compacto
        const select = this.querySelector('#theme-select');
        if (select) {
            select.value = themeKey;
        }

        // Actualizar tarjetas
        const themeCards = this.querySelectorAll('.theme-card');
        themeCards.forEach(card => {
            const isActive = card.dataset.theme === themeKey;
            card.classList.toggle('active', isActive);
            
            // Actualizar indicador activo
            let indicator = card.querySelector('.theme-active-indicator');
            if (isActive && !indicator) {
                indicator = document.createElement('div');
                indicator.className = 'theme-active-indicator';
                indicator.textContent = '✓';
                card.appendChild(indicator);
            } else if (!isActive && indicator) {
                indicator.remove();
            }
        });

        // Actualizar descripción si está visible
        if (this.options.showDescription) {
            this.updateDescription(themeKey);
        }
    }

    /**
     * Actualiza la descripción del tema
     */
    updateDescription(themeKey) {
        if (!this.themeService) {
            return;
        }
        const theme = this.themeService.getCurrentTheme();
        const descriptionElement = this.querySelector('.theme-description-text');
        
        if (descriptionElement && theme.data) {
            descriptionElement.innerHTML = `
                <strong>${theme.data.name}:</strong> ${theme.data.description}
            `;
        }
    }

    /**
     * Obtiene el tema actual
     */
    getCurrentTheme() {
        if (!this.themeService) {
            return null;
        }
        return this.themeService.getCurrentTheme();
    }

    /**
     * Obtiene información del componente
     */
    getInfo() {
        return {
            component: 'ThemeSelectorComponent',
            currentTheme: this.getCurrentTheme(),
            availableThemes: this.themeService ? this.themeService.getAvailableThemes().length : 0,
            options: this.options
        };
    }
}

// Estilos CSS para el componente
const themeSelectorStyles = `
<style>
.theme-selector {
    font-family: var(--font-primary);
}

.theme-select-compact {
    padding: 0.5rem;
    border: 1px solid var(--color-verde-claro);
    border-radius: 6px;
    background: white;
    color: var(--color-verde-oscuro);
    font-size: 0.875rem;
}

.theme-random-btn {
    background: var(--color-rosa-principal);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.theme-random-btn:hover {
    background: var(--color-rosa-oscuro);
    transform: scale(1.05);
}

.theme-random-btn-full {
    background: var(--color-verde-principal);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.theme-random-btn-full:hover {
    background: var(--color-verde-oscuro);
    transform: translateY(-2px);
}

.theme-card {
    background: white;
    border: 2px solid var(--color-verde-muy-claro);
    border-radius: 12px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.theme-card:hover {
    border-color: var(--color-verde-claro);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.theme-card.active {
    border-color: var(--color-verde-principal);
    background: var(--color-verde-muy-claro);
}

.theme-preview {
    margin-bottom: 0.75rem;
}

.preview-colors {
    display: flex;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.preview-color {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.theme-info {
    text-align: center;
}

.theme-name {
    font-family: var(--font-heading);
    font-size: 1rem;
    font-weight: 600;
    color: var(--color-verde-oscuro);
    margin-bottom: 0.25rem;
}

.theme-description {
    font-size: 0.75rem;
    color: var(--color-verde-principal);
    line-height: 1.4;
}

.theme-active-indicator {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: var(--color-verde-principal);
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
}

.theme-card:focus {
    outline: 2px solid var(--color-verde-principal);
    outline-offset: 2px;
}

.theme-card[tabindex="0"] {
    cursor: pointer;
}

/* Responsive */
@media (max-width: 768px) {
    .theme-selector-full .grid {
        grid-template-columns: 1fr;
    }
    
    .theme-card {
        padding: 0.75rem;
    }
}
</style>
`;

// Añadir estilos al documento
if (!document.querySelector('#theme-selector-styles')) {
    const styleElement = document.createElement('div');
    styleElement.id = 'theme-selector-styles';
    styleElement.innerHTML = themeSelectorStyles;
    document.head.appendChild(styleElement);
}

// Hacer la clase disponible globalmente
window.ThemeSelectorComponent = ThemeSelectorComponent;
