-- phpMyAdmin SQL Dump
-- Versión: 10.4.32-MariaDB
-- Base de datos: `proyectoGimnasio`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- BASE DE DATOS: proyectoGimnasio
--

-- --------------------------------------------------------

--
-- Tabla: roles (Catálogo de roles del sistema)
--

CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT,
    nivelAcceso INT NOT NULL COMMENT 'Mayor número = más privilegios',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: usuarios (Personas que usan el sistema - unificada)
--

CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    -- Datos personales
    nombreCompleto VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    fechaNacimiento DATE,
    direccion TEXT,
    fotoURL VARCHAR(255),
    
    -- Datos de autenticación
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    
    -- Relaciones
    idRol INT NOT NULL,
    idEntrenadorAsignado INT NULL COMMENT 'Solo para clientes',
    
    -- Control de acceso QR
    qrCode VARCHAR(255) UNIQUE,
    
    -- Estados
    activo BOOLEAN DEFAULT TRUE,
    ultimoAcceso TIMESTAMP NULL,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (idRol) REFERENCES roles(id),
    FOREIGN KEY (idEntrenadorAsignado) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: contactosEmergencia (Normalización de contactos de emergencia)
--

CREATE TABLE contactosEmergencia (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    nombreCompleto VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    parentesco VARCHAR(50),
    esPrincipal BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_contacto_principal (idUsuario, esPrincipal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: tiposMembresia (Catálogo)
--

CREATE TABLE tiposMembresia (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    duracionDias INT NOT NULL,
    descripcion TEXT,
    precioBase DECIMAL(10,2) NOT NULL,
    permiteCongelamiento BOOLEAN DEFAULT TRUE,
    diasCongelamiento INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: membresias (Membresías compradas por usuarios)
--

CREATE TABLE membresias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    idTipoMembresia INT NOT NULL,
    idVendedor INT NOT NULL COMMENT 'Usuario que vendió',
    
    -- Fechas
    fechaInicio DATE NOT NULL,
    fechaFin DATE NOT NULL,
    fechaCongelamientoInicio DATE NULL,
    fechaCongelamientoFin DATE NULL,
    
    -- Económico
    precioPagado DECIMAL(10,2) NOT NULL,
    descuentoAplicado DECIMAL(10,2) DEFAULT 0,
    
    -- Estado
    estado ENUM('activa', 'congelada', 'expirada', 'cancelada') DEFAULT 'activa',
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (idTipoMembresia) REFERENCES tiposMembresia(id),
    FOREIGN KEY (idVendedor) REFERENCES usuarios(id),
    
    INDEX idx_fechas (fechaInicio, fechaFin),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: historialMembresias (Auditoría de cambios)
--

CREATE TABLE historialMembresias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idMembresia INT NOT NULL,
    idUsuarioModificador INT NOT NULL,
    accion ENUM('creada', 'renovada', 'congelada', 'reactivada', 'cancelada') NOT NULL,
    fechaCambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observaciones TEXT,
    
    FOREIGN KEY (idMembresia) REFERENCES membresias(id) ON DELETE CASCADE,
    FOREIGN KEY (idUsuarioModificador) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: promociones
--

CREATE TABLE promociones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    tipoDescuento ENUM('porcentaje', 'monto_fijo') NOT NULL,
    valorDescuento DECIMAL(10,2) NOT NULL,
    fechaInicio DATE NOT NULL,
    fechaFin DATE NOT NULL,
    usoMaximo INT NULL,
    usosActuales INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    
    INDEX idx_vigencia (fechaInicio, fechaFin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: promociones_membresias (Relación N:M)
--

CREATE TABLE promociones_membresias (
    idPromocion INT NOT NULL,
    idTipoMembresia INT NOT NULL,
    PRIMARY KEY (idPromocion, idTipoMembresia),
    FOREIGN KEY (idPromocion) REFERENCES promociones(id) ON DELETE CASCADE,
    FOREIGN KEY (idTipoMembresia) REFERENCES tiposMembresia(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: pagos
--

CREATE TABLE pagos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    idMembresia INT NULL,
    idVendedor INT NOT NULL,
    idPromocion INT NULL,
    
    -- Detalles del pago
    concepto VARCHAR(255) NOT NULL,
    montoBase DECIMAL(10,2) NOT NULL,
    descuento DECIMAL(10,2) DEFAULT 0,
    montoFinal DECIMAL(10,2) NOT NULL,
    
    -- Método de pago
    metodoPago ENUM('efectivo', 'tarjeta_credito', 'tarjeta_debito', 'transferencia', 'otros') NOT NULL,
    referenciaPago VARCHAR(100) NULL,
    
    -- Estado
    estado ENUM('completado', 'pendiente', 'cancelado', 'reembolsado') DEFAULT 'completado',
    
    -- Metadata
    fechaPago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id),
    FOREIGN KEY (idMembresia) REFERENCES membresias(id),
    FOREIGN KEY (idVendedor) REFERENCES usuarios(id),
    FOREIGN KEY (idPromocion) REFERENCES promociones(id),
    
    INDEX idx_fecha (fechaPago)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: accesos (Registro de entradas/salidas)
--

CREATE TABLE accesos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    tipo ENUM('entrada', 'salida') NOT NULL,
    fechaAcceso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    metodoAcceso ENUM('qr', 'manual', 'huella') DEFAULT 'qr',
    idRegistrador INT NULL COMMENT 'Si fue registro manual',
    
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id),
    FOREIGN KEY (idRegistrador) REFERENCES usuarios(id),
    
    INDEX idx_fecha_usuario (fechaAcceso, idUsuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: ejercicios (Catálogo)
--

CREATE TABLE ejercicios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    grupoMuscular VARCHAR(100),
    equipamientoNecesario VARCHAR(255),
    imagenURL VARCHAR(255),
    videoURL VARCHAR(255),
    dificultad ENUM('principiante', 'intermedio', 'avanzado') DEFAULT 'principiante',
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: rutinas
--

CREATE TABLE rutinas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL COMMENT 'Cliente',
    idEntrenador INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fechaAsignacion DATE NOT NULL,
    fechaInicio DATE NULL,
    fechaFin DATE NULL,
    activo BOOLEAN DEFAULT TRUE,
    
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (idEntrenador) REFERENCES usuarios(id),
    
    INDEX idx_usuario_activo (idUsuario, activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: diasRutina (Días de la semana para la rutina)
--

CREATE TABLE diasRutina (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idRutina INT NOT NULL,
    diaSemana ENUM('lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo') NOT NULL,
    orden INT DEFAULT 0,
    
    FOREIGN KEY (idRutina) REFERENCES rutinas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_rutina_dia (idRutina, diaSemana)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: rutinaEjercicios (Tabla intermedia con atributos)
--

CREATE TABLE rutinaEjercicios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idDiaRutina INT NOT NULL,
    idEjercicio INT NOT NULL,
    ordenEjercicio INT NOT NULL,
    series INT NOT NULL,
    repeticiones VARCHAR(50) NOT NULL COMMENT 'Puede ser "10-12" o "hasta fallo"',
    descanso INT COMMENT 'Segundos de descanso',
    notas TEXT,
    
    FOREIGN KEY (idDiaRutina) REFERENCES diasRutina(id) ON DELETE CASCADE,
    FOREIGN KEY (idEjercicio) REFERENCES ejercicios(id),
    
    INDEX idx_orden (ordenEjercicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: evaluacionesFisicas
--

CREATE TABLE evaluacionesFisicas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    idEntrenador INT NOT NULL,
    fechaEvaluacion DATE NOT NULL,
    
    -- Medidas
    peso DECIMAL(5,2) NOT NULL,
    altura DECIMAL(5,2) NOT NULL,
    imc DECIMAL(5,2) GENERATED ALWAYS AS (peso / (altura * altura)) STORED,
    porcentajeGrasa DECIMAL(5,2),
    
    -- Medidas corporales
    pecho DECIMAL(5,2),
    cintura DECIMAL(5,2),
    cadera DECIMAL(5,2),
    brazo DECIMAL(5,2),
    pierna DECIMAL(5,2),
    
    -- Observaciones
    observaciones TEXT,
    fotoProgresoURL VARCHAR(255),
    
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (idEntrenador) REFERENCES usuarios(id),
    
    INDEX idx_usuario_fecha (idUsuario, fechaEvaluacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: clasesGrupales
--

CREATE TABLE clasesGrupales (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    idEntrenador INT NOT NULL,
    capacidadMaxima INT NOT NULL,
    duracionMinutos INT NOT NULL,
    colorIdentificador VARCHAR(7) DEFAULT '#3498db',
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: horariosClases
--

CREATE TABLE horariosClases (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idClase INT NOT NULL,
    diaSemana ENUM('lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo') NOT NULL,
    horaInicio TIME NOT NULL,
    horaFin TIME NOT NULL,
    sala VARCHAR(50),
    
    FOREIGN KEY (idClase) REFERENCES clasesGrupales(id) ON DELETE CASCADE,
    UNIQUE KEY unique_horario (idClase, diaSemana, horaInicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: reservasClases
--

CREATE TABLE reservasClases (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idHorarioClase INT NOT NULL,
    idUsuario INT NOT NULL,
    fechaReserva DATE NOT NULL,
    estado ENUM('confirmada', 'cancelada', 'completada', 'no_asistio') DEFAULT 'confirmada',
    fechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fechaCancelacion TIMESTAMP NULL,
    
    FOREIGN KEY (idHorarioClase) REFERENCES horariosClases(id) ON DELETE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_reserva (idHorarioClase, idUsuario, fechaReserva),
    INDEX idx_fecha_estado (fechaReserva, estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: listaEsperaClases
--

CREATE TABLE listaEsperaClases (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idHorarioClase INT NOT NULL,
    idUsuario INT NOT NULL,
    fechaSolicitud DATE NOT NULL,
    posicion INT NOT NULL,
    notificado BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (idHorarioClase) REFERENCES horariosClases(id) ON DELETE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_lista (idHorarioClase, idUsuario, fechaSolicitud)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: categoriasProductos
--

CREATE TABLE categoriasProductos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: proveedores
--

CREATE TABLE proveedores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    contacto VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    rfc VARCHAR(20),
    notas TEXT,
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: productos
--

CREATE TABLE productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigoBarras VARCHAR(50) UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    idCategoria INT NOT NULL,
    idProveedor INT NOT NULL,
    
    -- Inventario
    stockActual INT NOT NULL DEFAULT 0,
    stockMinimo INT NOT NULL DEFAULT 5,
    ubicacion VARCHAR(50),
    
    -- Económico
    precioCompra DECIMAL(10,2) NOT NULL,
    precioVenta DECIMAL(10,2) NOT NULL,
    iva DECIMAL(5,2) DEFAULT 16,
    
    -- Metadata
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (idCategoria) REFERENCES categoriasProductos(id),
    FOREIGN KEY (idProveedor) REFERENCES proveedores(id),
    
    INDEX idx_stock (stockActual, stockMinimo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: ventasPOS
--

CREATE TABLE ventasPOS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    folio VARCHAR(20) UNIQUE NOT NULL,
    idUsuario INT NOT NULL COMMENT 'Cliente',
    idVendedor INT NOT NULL,
    fechaVenta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10,2) NOT NULL,
    iva DECIMAL(10,2) NOT NULL,
    descuento DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    metodoPago ENUM('efectivo', 'tarjeta', 'transferencia', 'multiple') NOT NULL,
    estado ENUM('completada', 'cancelada', 'pendiente') DEFAULT 'completada',
    
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id),
    FOREIGN KEY (idVendedor) REFERENCES usuarios(id),
    
    INDEX idx_folio (folio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: detalleVentas
--

CREATE TABLE detalleVentas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idVenta INT NOT NULL,
    idProducto INT NOT NULL,
    cantidad INT NOT NULL,
    precioUnitario DECIMAL(10,2) NOT NULL,
    descuento DECIMAL(10,2) DEFAULT 0,
    subtotal DECIMAL(10,2) GENERATED ALWAYS AS (cantidad * precioUnitario - descuento) STORED,
    
    FOREIGN KEY (idVenta) REFERENCES ventasPOS(id) ON DELETE CASCADE,
    FOREIGN KEY (idProducto) REFERENCES productos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: bitacora (Auditoría completa del sistema)
--

CREATE TABLE bitacora (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT,
    accion VARCHAR(100) NOT NULL,
    tabla VARCHAR(50),
    idRegistro INT,
    datosAntiguos JSON,
    datosNuevos JSON,
    ipDireccion VARCHAR(45),
    userAgent TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (idUsuario) REFERENCES usuarios(id),
    INDEX idx_fecha (fecha),
    INDEX idx_usuario (idUsuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabla: parametrosSistema (Configuración global)
--

CREATE TABLE parametrosSistema (
    id INT PRIMARY KEY AUTO_INCREMENT,
    clave VARCHAR(50) UNIQUE NOT NULL,
    valor TEXT NOT NULL,
    descripcion TEXT,
    tipoDato ENUM('texto', 'numero', 'booleano', 'json') DEFAULT 'texto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- INSERTS DE DATOS INICIALES
--

INSERT INTO roles (nombre, descripcion, nivelAcceso) VALUES
('SuperAdmin', 'Acceso total al sistema', 100),
('Administrador', 'Gestión completa del gimnasio', 90),
('Recepcionista', 'Operaciones diarias', 50),
('Entrenador', 'Gestión de rutinas y evaluaciones', 40),
('Cliente', 'Acceso a autoservicio', 10);

INSERT INTO tiposMembresia (nombre, duracionDias, descripcion, precioBase) VALUES
('Pase Diario', 1, 'Acceso por un día', 50.00),
('Mensual', 30, 'Acceso ilimitado por 30 días', 500.00),
('Trimestral', 90, 'Acceso ilimitado por 90 días', 1350.00),
('Anual', 365, 'Acceso ilimitado por 365 días', 4800.00);

INSERT INTO categoriasProductos (nombre, descripcion) VALUES
('Suplementos', 'Proteinas, creatinas, aminoácidos'),
('Bebidas', 'Bebidas energéticas, hidratantes'),
('Accesorios', 'Guantes, toallas, botellas'),
('Ropa', 'Playeras, shorts, sudaderas');

INSERT INTO parametrosSistema (clave, valor, descripcion, tipoDato) VALUES
('gym_nombre', 'Mi Gimnasio', 'Nombre del gimnasio', 'texto'),
('gym_horario_apertura', '06:00', 'Hora de apertura', 'texto'),
('gym_horario_cierre', '22:00', 'Hora de cierre', 'texto'),
('permiso_reservas_anticipadas', '7', 'Días de anticipación para reservas', 'numero'),
('notificaciones_stock_bajo', 'true', 'Alertar cuando stock bajo', 'booleano');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;