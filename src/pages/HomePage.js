/**
 * Página principal del sistema
 * Extiende BasePage para manejar la página de inicio
 */
class HomePage extends BasePage {
    constructor(options = {}) {
        super(options);
        this.stats = null;
    }

    get defaultOptions() {
        return {
            autoLoad: true,
            debug: false,
            cacheData: true,
            showLoading: true,
            showStats: true
        };
    }

    async fetchData() {
        // Cargar estadísticas del sistema si están disponibles
        if (this.options.showStats) {
            try {
                await this.loadStats();
            } catch (error) {
                this.log('No se pudieron cargar las estadísticas:', error);
            }
        }
    }

    /**
     * Carga estadísticas del sistema
     */
    async loadStats() {
        try {
            const response = await fetch('/admin/api/poemas.php');
            const poemas = await response.json();
            
            this.stats = {
                totalPoemas: poemas.length,
                categorias: this.countCategories(poemas),
                autores: this.countAuthors(poemas)
            };
            
            this.log('Estadísticas cargadas:', this.stats);
        } catch (error) {
            this.log('Error al cargar estadísticas:', error);
            this.stats = {
                totalPoemas: 0,
                categorias: 0,
                autores: 0
            };
        }
    }

    /**
     * Cuenta las categorías únicas
     */
    countCategories(poemas) {
        const categorias = new Set();
        poemas.forEach(poema => {
            if (poema.categoria_nombre) {
                categorias.add(poema.categoria_nombre);
            }
        });
        return categorias.size;
    }

    /**
     * Cuenta los autores únicos
     */
    countAuthors(poemas) {
        const autores = new Set();
        poemas.forEach(poema => {
            if (poema.autor_nombre) {
                autores.add(poema.autor_nombre);
            }
        });
        return autores.size;
    }

    renderContent() {
        // El contenido principal ya está en el HTML
        // Solo necesitamos actualizar las estadísticas si están disponibles
        if (this.stats) {
            this.updateStatsDisplay();
        }
    }

    /**
     * Actualiza la visualización de estadísticas
     */
    updateStatsDisplay() {
        const statsContainer = document.getElementById('stats-container');
        
        if (statsContainer && this.stats) {
            const statsHTML = `
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h2 class="text-2xl font-playfair font-bold text-verde-oscuro mb-4 text-center">
                        📊 Estadísticas del Sistema
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-verde-principal">${this.stats.totalPoemas}</div>
                            <div class="text-sm text-gray-600">Poemas</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-rosa-principal">${this.stats.autores}</div>
                            <div class="text-sm text-gray-600">Autores</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-amarillo-intenso">${this.stats.categorias}</div>
                            <div class="text-sm text-gray-600">Categorías</div>
                        </div>
                    </div>
                </div>
            `;
            
            // Insertar antes del selector de vistas
            const selectorContainer = document.querySelector('.text-center.mb-12');
            if (selectorContainer) {
                selectorContainer.insertAdjacentHTML('beforebegin', statsHTML);
            }
        }
    }

    setupPageEventListeners() {
        // Event listeners específicos de la página de inicio
        document.addEventListener('click', (e) => {
            if (e.target.matches('.view-card')) {
                this.handleViewCardClick(e);
            }
        });
    }

    /**
     * Maneja el clic en las tarjetas de vista
     */
    handleViewCardClick(e) {
        const card = e.target.closest('.view-card');
        if (card) {
            // Añadir efecto visual
            card.classList.add('animate-pulse-custom');
            
            setTimeout(() => {
                card.classList.remove('animate-pulse-custom');
            }, 600);
        }
    }

    /**
     * Añade efectos visuales a las tarjetas de vista
     */
    enhanceViewCards() {
        const viewCards = document.querySelectorAll('.grid > div');
        
        viewCards.forEach((card, index) => {
            // Añadir clase para efectos hover
            card.classList.add('view-card', 'hover-lift');
            
            // Añadir animación de entrada escalonada
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate-fadeIn');
        });
    }

    afterRender() {
        // Añadir efectos visuales después del renderizado
        this.enhanceViewCards();
        
        // Configurar tooltips si es necesario
        this.setupTooltips();
    }

    /**
     * Configura tooltips para las tarjetas
     */
    setupTooltips() {
        const viewCards = document.querySelectorAll('.view-card');
        
        viewCards.forEach(card => {
            const title = card.querySelector('h3').textContent;
            const description = card.querySelector('p').textContent;
            
            card.title = `${title}: ${description}`;
        });
    }

    /**
     * Actualiza las estadísticas en tiempo real
     */
    async refreshStats() {
        await this.loadStats();
        this.updateStatsDisplay();
    }

    /**
     * Maneja errores específicos de la página de inicio
     */
    handleError(message, error) {
        super.handleError(message, error);
        
        // Lógica específica para errores en la página de inicio
        if (message.includes('estadísticas')) {
            // Si no se pueden cargar las estadísticas, ocultar la sección
            const statsContainer = document.getElementById('stats-container');
            if (statsContainer) {
                statsContainer.style.display = 'none';
            }
        }
    }
}

// Hacer la clase disponible globalmente
window.HomePage = HomePage;
