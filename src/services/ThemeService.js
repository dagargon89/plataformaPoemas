/**
 * Servicio de gestión de temas dinámicos
 * Permite cambiar colores y estilos en tiempo real
 */
export class ThemeService {
    constructor() {
        this.currentTheme = 'default';
        this.themes = this.initializeThemes();
        this.storageKey = 'poemas_theme';
        this.init();
    }

    /**
     * Inicializa el servicio
     */
    init() {
        this.loadSavedTheme();
        this.applyTheme(this.currentTheme);
        this.setupEventListeners();
    }

    /**
     * Inicializa los temas predefinidos
     */
    initializeThemes() {
        return {
            // Tema original (verde/rosa/amarillo)
            default: {
                name: 'Naturaleza',
                description: 'Tema original con verdes, rosas y amarillos',
                colors: {
                    primary: {
                        main: '#636B2F',
                        light: '#8B9A4A',
                        dark: '#4A5220',
                        veryLight: '#F0F4E8'
                    },
                    secondary: {
                        main: '#E89EB8',
                        light: '#F2C4D1',
                        dark: '#D67A9A',
                        veryLight: '#FDF2F8'
                    },
                    accent: {
                        light: '#FDF4E3',
                        medium: '#F9E6B3',
                        gold: '#F4D03F',
                        intense: '#D4AC0D'
                    }
                },
                fonts: {
                    primary: 'Crimson Text',
                    heading: 'Playfair Display'
                }
            },

            // Tema oceánico (azules/verdes)
            ocean: {
                name: 'Océano',
                description: 'Tema inspirado en el océano con azules y verdes',
                colors: {
                    primary: {
                        main: '#2E5266',
                        light: '#4A7C7E',
                        dark: '#1A3A3A',
                        veryLight: '#E8F4F8'
                    },
                    secondary: {
                        main: '#7FB3D3',
                        light: '#A8D0E6',
                        dark: '#5A9BC4',
                        veryLight: '#F0F8FF'
                    },
                    accent: {
                        light: '#E6F3FF',
                        medium: '#B3D9FF',
                        gold: '#4A90E2',
                        intense: '#2E5BBA'
                    }
                },
                fonts: {
                    primary: 'Crimson Text',
                    heading: 'Playfair Display'
                }
            },

            // Tema atardecer (naranjas/rojos)
            sunset: {
                name: 'Atardecer',
                description: 'Tema cálido inspirado en atardeceres',
                colors: {
                    primary: {
                        main: '#D2691E',
                        light: '#E07B3F',
                        dark: '#B8541A',
                        veryLight: '#FDF2E9'
                    },
                    secondary: {
                        main: '#FF6B6B',
                        light: '#FF8E8E',
                        dark: '#E55A5A',
                        veryLight: '#FFF5F5'
                    },
                    accent: {
                        light: '#FFF8E1',
                        medium: '#FFE082',
                        gold: '#FFB74D',
                        intense: '#FF8F00'
                    }
                },
                fonts: {
                    primary: 'Crimson Text',
                    heading: 'Playfair Display'
                }
            },

            // Tema nocturno (grises/azules oscuros)
            night: {
                name: 'Nocturno',
                description: 'Tema oscuro para lectura nocturna',
                colors: {
                    primary: {
                        main: '#2C3E50',
                        light: '#34495E',
                        dark: '#1A252F',
                        veryLight: '#ECF0F1'
                    },
                    secondary: {
                        main: '#9B59B6',
                        light: '#BB8FCE',
                        dark: '#7D3C98',
                        veryLight: '#F4ECF7'
                    },
                    accent: {
                        light: '#F8F9FA',
                        medium: '#E9ECEF',
                        gold: '#F39C12',
                        intense: '#E67E22'
                    }
                },
                fonts: {
                    primary: 'Crimson Text',
                    heading: 'Playfair Display'
                }
            },

            // Tema primavera (verdes/rosas suaves)
            spring: {
                name: 'Primavera',
                description: 'Tema fresco y vibrante de primavera',
                colors: {
                    primary: {
                        main: '#27AE60',
                        light: '#58D68D',
                        dark: '#1E8449',
                        veryLight: '#E8F8F5'
                    },
                    secondary: {
                        main: '#F8BBD9',
                        light: '#FAD7E6',
                        dark: '#F1948A',
                        veryLight: '#FDF2F8'
                    },
                    accent: {
                        light: '#F0FFF0',
                        medium: '#C8E6C9',
                        gold: '#FFD700',
                        intense: '#FFA500'
                    }
                },
                fonts: {
                    primary: 'Crimson Text',
                    heading: 'Playfair Display'
                }
            }
        };
    }

    /**
     * Aplica un tema específico
     */
    applyTheme(themeName) {
        if (!this.themes[themeName]) {
            console.warn(`Tema '${themeName}' no encontrado, usando tema por defecto`);
            themeName = 'default';
        }

        this.currentTheme = themeName;
        const theme = this.themes[themeName];

        // Aplicar colores CSS
        this.applyColors(theme.colors);
        
        // Aplicar fuentes
        this.applyFonts(theme.fonts);

        // Actualizar clases del body
        document.body.className = document.body.className.replace(/theme-\w+/g, '');
        document.body.classList.add(`theme-${themeName}`);

        // Guardar preferencia
        this.saveTheme(themeName);

        // Emitir evento de cambio de tema
        this.emitThemeChange(themeName, theme);

        console.log(`🎨 Tema aplicado: ${theme.name}`);
    }

    /**
     * Aplica los colores del tema
     */
    applyColors(colors) {
        const root = document.documentElement;
        
        // Colores primarios
        root.style.setProperty('--color-verde-principal', colors.primary.main);
        root.style.setProperty('--color-verde-claro', colors.primary.light);
        root.style.setProperty('--color-verde-oscuro', colors.primary.dark);
        root.style.setProperty('--color-verde-muy-claro', colors.primary.veryLight);

        // Colores secundarios
        root.style.setProperty('--color-rosa-principal', colors.secondary.main);
        root.style.setProperty('--color-rosa-claro', colors.secondary.light);
        root.style.setProperty('--color-rosa-oscuro', colors.secondary.dark);
        root.style.setProperty('--color-rosa-muy-claro', colors.secondary.veryLight);

        // Colores de acento
        root.style.setProperty('--color-amarillo-claro', colors.accent.light);
        root.style.setProperty('--color-amarillo-medio', colors.accent.medium);
        root.style.setProperty('--color-amarillo-dorado', colors.accent.gold);
        root.style.setProperty('--color-amarillo-intenso', colors.accent.intense);
    }

    /**
     * Aplica las fuentes del tema
     */
    applyFonts(fonts) {
        const root = document.documentElement;
        
        root.style.setProperty('--font-primary', fonts.primary);
        root.style.setProperty('--font-heading', fonts.heading);
    }

    /**
     * Obtiene el tema actual
     */
    getCurrentTheme() {
        return {
            name: this.currentTheme,
            data: this.themes[this.currentTheme]
        };
    }

    /**
     * Obtiene todos los temas disponibles
     */
    getAvailableThemes() {
        return Object.keys(this.themes).map(key => ({
            key,
            ...this.themes[key]
        }));
    }

    /**
     * Carga el tema guardado
     */
    loadSavedTheme() {
        const savedTheme = localStorage.getItem(this.storageKey);
        if (savedTheme && this.themes[savedTheme]) {
            this.currentTheme = savedTheme;
        }
    }

    /**
     * Guarda el tema actual
     */
    saveTheme(themeName) {
        localStorage.setItem(this.storageKey, themeName);
    }

    /**
     * Configura event listeners
     */
    setupEventListeners() {
        // Escuchar cambios de tema desde otros componentes
        document.addEventListener('theme-change', (e) => {
            this.applyTheme(e.detail.theme);
        });

        // Escuchar cambios de tema desde el sistema
        document.addEventListener('theme-request', (e) => {
            this.applyTheme(e.detail.theme);
        });
    }

    /**
     * Emite evento de cambio de tema
     */
    emitThemeChange(themeName, themeData) {
        const event = new CustomEvent('theme-changed', {
            detail: {
                theme: themeName,
                data: themeData,
                timestamp: Date.now()
            }
        });
        document.dispatchEvent(event);
    }

    /**
     * Cambia al siguiente tema
     */
    nextTheme() {
        const themeKeys = Object.keys(this.themes);
        const currentIndex = themeKeys.indexOf(this.currentTheme);
        const nextIndex = (currentIndex + 1) % themeKeys.length;
        this.applyTheme(themeKeys[nextIndex]);
    }

    /**
     * Cambia al tema anterior
     */
    previousTheme() {
        const themeKeys = Object.keys(this.themes);
        const currentIndex = themeKeys.indexOf(this.currentTheme);
        const prevIndex = currentIndex === 0 ? themeKeys.length - 1 : currentIndex - 1;
        this.applyTheme(themeKeys[prevIndex]);
    }

    /**
     * Aplica un tema aleatorio
     */
    randomTheme() {
        const themeKeys = Object.keys(this.themes);
        const randomIndex = Math.floor(Math.random() * themeKeys.length);
        this.applyTheme(themeKeys[randomIndex]);
    }

    /**
     * Obtiene información del servicio
     */
    getInfo() {
        return {
            currentTheme: this.currentTheme,
            availableThemes: this.getAvailableThemes().length,
            themes: this.getAvailableThemes()
        };
    }
}

// Crear instancia global del servicio
export const themeService = new ThemeService();

// Exportar para uso global
window.themeService = themeService;
