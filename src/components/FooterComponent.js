/**
 * Componente de pie de página
 * Extiende BaseComponent para manejar el footer
 */
class FooterComponent extends BaseComponent {
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
            <div class="container mx-auto px-4 py-4">
                <!-- Solo información de copyright -->
                <div class="flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0">
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
        `;

        this.setHTML(footerHTML);
    }

    setupEventListeners() {
        // No hay event listeners necesarios para el footer simplificado
    }

    /**
     * Actualiza la configuración del footer
     */
    updateConfig(newConfig) {
        this.options = { ...this.options, ...newConfig };
        this.render();
    }
}

// Hacer la clase disponible globalmente
window.FooterComponent = FooterComponent;
