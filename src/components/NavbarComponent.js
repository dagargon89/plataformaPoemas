/**
 * Componente de navegación
 * Extiende BaseComponent para manejar la barra de navegación
 */
class NavbarComponent extends BaseComponent {
    constructor(elementId, options = {}) {
        super(elementId, options);
        this.currentView = this.getCurrentView();
    }

    get defaultOptions() {
        return {
            autoRender: true,
            debug: false,
            showMobileMenu: false
        };
    }

    renderContent() {
        const navbarHTML = `
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo/Título -->
                    <div class="flex items-center">
                        <a href="index.html" class="text-xl font-playfair font-bold text-white hover:text-rosa-claro transition-colors">
                            📚 Poemas
                        </a>
                    </div>

                    <!-- Navegación Desktop -->
                    <nav class="hidden md:flex space-x-6">
                        <a href="index.html" class="nav-link ${this.isActive('index')}">
                            🏠 Inicio
                        </a>
                        <a href="tarjetas-poemas.html" class="nav-link ${this.isActive('tarjetas')}">
                            🎴 Tarjetas
                        </a>
                        <a href="lista-scroll.html" class="nav-link ${this.isActive('lista')}">
                            📜 Lista
                        </a>
                        <a href="libro-tradicional.html" class="nav-link ${this.isActive('libro')}">
                            📖 Libro
                        </a>
                    </nav>

                    <!-- Botón Admin (opcional) -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="admin/" class="text-sm text-rosa-claro hover:text-white transition-colors">
                            ⚙️ Admin
                        </a>
                        <a href="temas-demo.html" class="text-sm text-rosa-claro hover:text-white transition-colors">
                            🎨 Temas
                        </a>
                    </div>

                    <!-- Botón Mobile Menu -->
                    <button class="md:hidden text-white hover:text-rosa-claro transition-colors" 
                            onclick="this.toggleMobileMenu()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="md:hidden ${this.options.showMobileMenu ? 'block' : 'hidden'} bg-verde-oscuro mt-2 rounded-lg">
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <a href="index.html" class="mobile-nav-link ${this.isActive('index')}">
                            🏠 Inicio
                        </a>
                        <a href="tarjetas-poemas.html" class="mobile-nav-link ${this.isActive('tarjetas')}">
                            🎴 Tarjetas
                        </a>
                        <a href="lista-scroll.html" class="mobile-nav-link ${this.isActive('lista')}">
                            📜 Lista
                        </a>
                        <a href="libro-tradicional.html" class="mobile-nav-link ${this.isActive('libro')}">
                            📖 Libro
                        </a>
                        <div class="border-t border-verde-claro pt-2 mt-2">
                            <a href="admin/" class="mobile-nav-link text-rosa-claro">
                                ⚙️ Panel Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;

        this.setHTML(navbarHTML);
    }

    setupEventListeners() {
        // Event listeners para navegación
        this.on('click', (e) => {
            if (e.target.matches('.nav-link, .mobile-nav-link')) {
                this.handleNavigation(e);
            }
        });

        // Cerrar mobile menu al hacer click fuera
        document.addEventListener('click', (e) => {
            const mobileMenu = this.querySelector('#mobile-menu');
            const menuButton = e.target.closest('button');
            
            if (mobileMenu && !mobileMenu.contains(e.target) && !menuButton) {
                this.closeMobileMenu();
            }
        });
    }

    /**
     * Determina si un enlace está activo
     */
    isActive(view) {
        const currentPage = this.getCurrentPage();
        return currentPage === view ? 'text-rosa-claro font-semibold' : 'text-white hover:text-rosa-claro';
    }

    /**
     * Obtiene la página actual
     */
    getCurrentPage() {
        const path = window.location.pathname;
        const page = path.split('/').pop();
        
        if (page === 'index.html' || page === '' || page === 'plataformaPoemas') {
            return 'index';
        } else if (page.includes('tarjetas')) {
            return 'tarjetas';
        } else if (page.includes('lista')) {
            return 'lista';
        } else if (page.includes('libro')) {
            return 'libro';
        }
        
        return 'index';
    }

    /**
     * Obtiene la vista actual
     */
    getCurrentView() {
        return this.getCurrentPage();
    }

    /**
     * Maneja la navegación
     */
    handleNavigation(e) {
        e.preventDefault();
        const href = e.target.getAttribute('href');
        
        if (href) {
            // Cerrar mobile menu si está abierto
            this.closeMobileMenu();
            
            // Navegar
            window.location.href = href;
        }
    }

    /**
     * Alterna el menú móvil
     */
    toggleMobileMenu() {
        this.options.showMobileMenu = !this.options.showMobileMenu;
        this.render();
    }

    /**
     * Cierra el menú móvil
     */
    closeMobileMenu() {
        this.options.showMobileMenu = false;
        const mobileMenu = this.querySelector('#mobile-menu');
        if (mobileMenu) {
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('block');
        }
    }

    /**
     * Actualiza la vista activa
     */
    updateActiveView(newView) {
        this.currentView = newView;
        this.render();
    }
}

// Hacer la clase disponible globalmente
window.NavbarComponent = NavbarComponent;
