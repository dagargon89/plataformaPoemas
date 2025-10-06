/**
 * Componente de pie de página
 * Extiende BaseComponent para manejar el footer
 */
export class FooterComponent extends BaseComponent {
    constructor(elementId, options = {}) {
        super(elementId, options);
    }

    get defaultOptions() {
        return {
            autoRender: true,
            debug: false,
            showSocialLinks: false,
            showAdminLink: true
        };
    }

    renderContent() {
        const footerHTML = `
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Información del sitio -->
                    <div class="text-center md:text-left">
                        <h3 class="text-lg font-playfair font-semibold text-white mb-3">
                            📚 Sistema de Poemas
                        </h3>
                        <p class="text-rosa-claro text-sm leading-relaxed">
                            Una plataforma dinámica para disfrutar de la poesía 
                            con múltiples formas de lectura y gestión de contenido.
                        </p>
                    </div>

                    <!-- Enlaces de navegación -->
                    <div class="text-center">
                        <h4 class="text-white font-semibold mb-3">Navegación</h4>
                        <div class="space-y-2">
                            <div>
                                <a href="index.html" class="footer-link">🏠 Inicio</a>
                            </div>
                            <div>
                                <a href="tarjetas-poemas.html" class="footer-link">🎴 Vista Tarjetas</a>
                            </div>
                            <div>
                                <a href="lista-scroll.html" class="footer-link">📜 Vista Lista</a>
                            </div>
                            <div>
                                <a href="libro-tradicional.html" class="footer-link">📖 Vista Libro</a>
                            </div>
                        </div>
                    </div>

                    <!-- Enlaces administrativos -->
                    <div class="text-center md:text-right">
                        <h4 class="text-white font-semibold mb-3">Administración</h4>
                        <div class="space-y-2">
                            ${this.options.showAdminLink ? `
                                <div>
                                    <a href="admin/" class="footer-link">⚙️ Panel Admin</a>
                                </div>
                            ` : ''}
                            <div>
                                <a href="admin/panel.php" class="footer-link">📝 Gestionar Poemas</a>
                            </div>
                            <div>
                                <a href="admin/api/test.php" class="footer-link">🔧 API Status</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Separador -->
                <div class="border-t border-verde-claro mt-8 pt-6">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <!-- Copyright -->
                        <div class="text-center md:text-left">
                            <p class="text-rosa-claro text-sm">
                                © ${new Date().getFullYear()} Sistema de Poemas Dinámico. 
                                Todos los derechos reservados.
                            </p>
                        </div>

                        <!-- Información técnica -->
                        <div class="text-center md:text-right">
                            <p class="text-rosa-claro text-xs">
                                Versión 1.0.0 | Desarrollado con ❤️
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        this.setHTML(footerHTML);
    }

    setupEventListeners() {
        // Event listeners para enlaces del footer
        this.on('click', (e) => {
            if (e.target.matches('.footer-link')) {
                this.handleFooterLink(e);
            }
        });
    }

    /**
     * Maneja los clics en enlaces del footer
     */
    handleFooterLink(e) {
        e.preventDefault();
        const href = e.target.getAttribute('href');
        
        if (href) {
            // Emitir evento personalizado
            this.emit('footer-navigation', { href, text: e.target.textContent });
            
            // Navegar
            window.location.href = href;
        }
    }

    /**
     * Actualiza la configuración del footer
     */
    updateConfig(newConfig) {
        this.options = { ...this.options, ...newConfig };
        this.render();
    }

    /**
     * Muestra/oculta enlaces administrativos
     */
    toggleAdminLinks(show = true) {
        this.options.showAdminLink = show;
        this.render();
    }

    /**
     * Añade un enlace personalizado al footer
     */
    addCustomLink(text, href, section = 'navigation') {
        // Esta funcionalidad se puede extender para añadir enlaces dinámicamente
        this.log(`Añadiendo enlace personalizado: ${text} -> ${href}`);
    }
}
