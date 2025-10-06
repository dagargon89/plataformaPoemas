<?php
/**
 * Configuración de la base de datos MySQL
 * Sistema de Poemas Dinámico
 */

class Database {
    private $db;
    private $config;

    public function __construct($config = null) {
        // Cargar configuración desde config.php si no se proporciona
        if ($config === null) {
            require_once __DIR__ . '/config.php';
            $this->config = DB_CONFIG;
        } else {
            $this->config = $config;
        }
        $this->connect();
    }

    /**
     * Conecta a la base de datos MySQL
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->config['host']};charset={$this->config['charset']}";
            
            // Primero conectamos sin especificar base de datos para crearla si no existe
            $this->db = new PDO($dsn, $this->config['username'], $this->config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Crear base de datos si no existe
            $this->db->exec("CREATE DATABASE IF NOT EXISTS `{$this->config['dbname']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Reconectar especificando la base de datos
            $dsn = "mysql:host={$this->config['host']};dbname={$this->config['dbname']};charset={$this->config['charset']}";
            $this->db = new PDO($dsn, $this->config['username'], $this->config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            throw new Exception("Error al conectar con la base de datos: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la instancia de la base de datos
     */
    public function getConnection() {
        return $this->db;
    }

    /**
     * Crea todas las tablas necesarias
     */
    public function createTables() {
        try {
            // Tabla autores
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS autores (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nombre VARCHAR(100) NOT NULL UNIQUE,
                    biografia TEXT,
                    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // Tabla categorias
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS categorias (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nombre VARCHAR(50) NOT NULL UNIQUE,
                    icono VARCHAR(50),
                    color VARCHAR(20),
                    descripcion TEXT,
                    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // Tabla etiquetas
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS etiquetas (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nombre VARCHAR(50) NOT NULL UNIQUE,
                    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // Tabla poemas
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS poemas (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    titulo VARCHAR(200) NOT NULL,
                    autor_id INT NOT NULL,
                    categoria_id INT NOT NULL,
                    icono VARCHAR(50),
                    extracto TEXT,
                    contenido TEXT NOT NULL,
                    tiempo_lectura INT DEFAULT 2,
                    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (autor_id) REFERENCES autores(id) ON DELETE CASCADE,
                    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            // Tabla de relación muchos a muchos entre poemas y etiquetas
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS poema_etiquetas (
                    poema_id INT,
                    etiqueta_id INT,
                    PRIMARY KEY (poema_id, etiqueta_id),
                    FOREIGN KEY (poema_id) REFERENCES poemas(id) ON DELETE CASCADE,
                    FOREIGN KEY (etiqueta_id) REFERENCES etiquetas(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

            return true;
        } catch (PDOException $e) {
            throw new Exception("Error al crear tablas: " . $e->getMessage());
        }
    }

    /**
     * Inserta datos de ejemplo
     */
    public function insertSampleData() {
        try {
            // Verificar si ya hay datos
            $stmt = $this->db->query("SELECT COUNT(*) FROM autores");
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                return true; // Ya hay datos
            }

            // Insertar autores de ejemplo
            $autores = [
                ['nombre' => 'Gabriel García Márquez', 'biografia' => 'Escritor colombiano, premio Nobel de Literatura 1982.'],
                ['nombre' => 'Pablo Neruda', 'biografia' => 'Poeta chileno, premio Nobel de Literatura 1971.'],
                ['nombre' => 'Mario Benedetti', 'biografia' => 'Escritor uruguayo, conocido por sus poemas y novelas.'],
                ['nombre' => 'Octavio Paz', 'biografia' => 'Poeta mexicano, premio Nobel de Literatura 1990.'],
                ['nombre' => 'Federico García Lorca', 'biografia' => 'Poeta español, figura clave de la Generación del 27.']
            ];

            $stmt = $this->db->prepare("INSERT INTO autores (nombre, biografia) VALUES (?, ?)");
            foreach ($autores as $autor) {
                $stmt->execute([$autor['nombre'], $autor['biografia']]);
            }

            // Insertar categorías de ejemplo
            $categorias = [
                ['nombre' => 'Naturaleza', 'icono' => '🌿', 'color' => '#636B2F', 'descripcion' => 'Poemas sobre la naturaleza y el medio ambiente'],
                ['nombre' => 'Amor', 'icono' => '💕', 'color' => '#E89EB8', 'descripcion' => 'Poemas románticos y de amor'],
                ['nombre' => 'Reflexión', 'icono' => '🤔', 'color' => '#F4D03F', 'descripcion' => 'Poemas filosóficos y de reflexión'],
                ['nombre' => 'Nostalgia', 'icono' => '🌅', 'color' => '#D67A9A', 'descripcion' => 'Poemas nostálgicos y melancólicos'],
                ['nombre' => 'Libertad', 'icono' => '🕊️', 'color' => '#8B9A4A', 'descripcion' => 'Poemas sobre libertad y justicia']
            ];

            $stmt = $this->db->prepare("INSERT INTO categorias (nombre, icono, color, descripcion) VALUES (?, ?, ?, ?)");
            foreach ($categorias as $categoria) {
                $stmt->execute([$categoria['nombre'], $categoria['icono'], $categoria['color'], $categoria['descripcion']]);
            }

            // Insertar etiquetas de ejemplo
            $etiquetas = [
                ['nombre' => 'Romántico'],
                ['nombre' => 'Nostálgico'],
                ['nombre' => 'Filosófico'],
                ['nombre' => 'Naturaleza'],
                ['nombre' => 'Libertad'],
                ['nombre' => 'Melancolía'],
                ['nombre' => 'Esperanza'],
                ['nombre' => 'Tiempo'],
                ['nombre' => 'Sueños'],
                ['nombre' => 'Amistad']
            ];

            $stmt = $this->db->prepare("INSERT INTO etiquetas (nombre) VALUES (?)");
            foreach ($etiquetas as $etiqueta) {
                $stmt->execute([$etiqueta['nombre']]);
            }

            // Insertar poemas de ejemplo
            $poemas = [
                [
                    'titulo' => 'El mar y la luna',
                    'autor_id' => 1,
                    'categoria_id' => 1,
                    'icono' => '🌊',
                    'extracto' => 'El mar se extiende infinito bajo la luna plateada...',
                    'contenido' => "El mar se extiende infinito\nbajo la luna plateada,\nondas que susurran secretos\nde historias no contadas.\n\nLa brisa acaricia mi rostro\ncomo un recuerdo perdido,\nentre las olas que danzan\nen este baile eterno y querido.",
                    'tiempo_lectura' => 3
                ],
                [
                    'titulo' => 'Tu nombre en mi corazón',
                    'autor_id' => 2,
                    'categoria_id' => 2,
                    'icono' => '💖',
                    'extracto' => 'Escribo tu nombre en mi corazón cada día...',
                    'contenido' => "Escribo tu nombre en mi corazón cada día,\ncomo si fuera la primera vez,\ncon letras de luz y melodía\nque iluminan mi alma sin cesar.\n\nTu nombre es mi refugio seguro,\nmi estrella en la noche oscura,\nel verso más hermoso y puro\nque habita en mi literatura.",
                    'tiempo_lectura' => 4
                ],
                [
                    'titulo' => 'El tiempo que se escapa',
                    'autor_id' => 3,
                    'categoria_id' => 3,
                    'icono' => '⏰',
                    'extracto' => 'El tiempo se escapa entre mis dedos...',
                    'contenido' => "El tiempo se escapa entre mis dedos\ncomo arena dorada al viento,\nsegundos que se vuelven recuerdos\nen este viaje sin aliento.\n\n¿Qué es el tiempo sino un sueño?\nUn instante que se desvanece,\nun suspiro, un momento pequeño\nque la memoria agradece.",
                    'tiempo_lectura' => 3
                ]
            ];

            $stmt = $this->db->prepare("INSERT INTO poemas (titulo, autor_id, categoria_id, icono, extracto, contenido, tiempo_lectura) VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach ($poemas as $poema) {
                $stmt->execute([
                    $poema['titulo'],
                    $poema['autor_id'],
                    $poema['categoria_id'],
                    $poema['icono'],
                    $poema['extracto'],
                    $poema['contenido'],
                    $poema['tiempo_lectura']
                ]);
            }

            // Insertar relaciones poema-etiquetas
            $relaciones = [
                [1, 4], [1, 6], // El mar y la luna: Naturaleza, Melancolía
                [2, 1], [2, 7], // Tu nombre en mi corazón: Romántico, Esperanza
                [3, 3], [3, 8]  // El tiempo que se escapa: Filosófico, Tiempo
            ];

            $stmt = $this->db->prepare("INSERT INTO poema_etiquetas (poema_id, etiqueta_id) VALUES (?, ?)");
            foreach ($relaciones as $relacion) {
                $stmt->execute($relacion);
            }

            return true;
        } catch (PDOException $e) {
            throw new Exception("Error al insertar datos de ejemplo: " . $e->getMessage());
        }
    }

    /**
     * Verifica el estado de la base de datos
     */
    public function checkDatabaseStatus() {
        $status = [
            'database_exists' => true, // MySQL siempre existe si la conexión funciona
            'tables_created' => false,
            'data_inserted' => false,
            'foreign_keys_enabled' => true // MySQL tiene claves foráneas habilitadas por defecto
        ];

        try {
            // Verificar si las tablas existen
            $tables = ['autores', 'categorias', 'etiquetas', 'poemas', 'poema_etiquetas'];
            $existingTables = 0;
            
            foreach ($tables as $table) {
                $stmt = $this->db->query("SHOW TABLES LIKE '$table'");
                if ($stmt->fetch()) {
                    $existingTables++;
                }
            }
            
            $status['tables_created'] = ($existingTables === count($tables));
            
            // Verificar si hay datos
            if ($status['tables_created']) {
                $stmt = $this->db->query("SELECT COUNT(*) FROM autores");
                $status['data_inserted'] = ($stmt->fetchColumn() > 0);
            }
            
        } catch (Exception $e) {
            $status['error'] = $e->getMessage();
        }

        return $status;
    }

    /**
     * Cierra la conexión
     */
    public function close() {
        $this->db = null;
    }
}

// Función helper para obtener la instancia de la base de datos
function getDatabase() {
    static $db = null;
    if ($db === null) {
        $db = new Database();
    }
    return $db;
}
?>
