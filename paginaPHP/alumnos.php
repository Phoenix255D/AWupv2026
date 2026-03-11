<?php
require_once 'config.php';

// CRUD de alumnos:
// - Crea y actualiza por POST
// - Elimina y carga datos a editar por GET
// - Muestra formulario + listado final
function redirigir($mensaje)
{
    header('Location: alumnos.php?msg=' . urlencode($mensaje));
    exit;
}

// 1) Guardar (alta/edicion) de alumno
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matriculaOriginal = trim($_POST['matricula_original'] ?? '');
    $matricula = trim($_POST['matricula'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $id_carrera = isset($_POST['id_carrera']) ? (int)$_POST['id_carrera'] : 0;
    $email = trim($_POST['email'] ?? '');
    $promedio_general = isset($_POST['promedio_general']) ? (float)$_POST['promedio_general'] : -1;

    if ($matricula === '' || $nombre === '' || $id_carrera <= 0 || $email === '' || $promedio_general < 0 || $promedio_general > 10) {
        redirigir('Datos invalidos. Verifica los campos.');
    }

    // Si existe matricula original, se trata de una edicion.
    if ($matriculaOriginal !== '') {
        $stmt = $conexion->prepare('UPDATE alumnos SET matricula = ?, nombre = ?, id_carrera = ?, email = ?, promedio_general = ? WHERE matricula = ?');
        $stmt->bind_param('ssisss', $matricula, $nombre, $id_carrera, $email, $promedio_general, $matriculaOriginal);
        $ok = $stmt->execute();
        $stmt->close();
        redirigir($ok ? 'Alumno actualizado.' : 'No se pudo actualizar el alumno.');
    } else {
        // Alta de nuevo alumno.
        $stmt = $conexion->prepare('INSERT INTO alumnos (matricula, id_carrera, nombre, email, promedio_general) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sissd', $matricula, $id_carrera, $nombre, $email, $promedio_general);
        $ok = $stmt->execute();
        $stmt->close();
        redirigir($ok ? 'Alumno agregado.' : 'No se pudo agregar el alumno. Revisa matricula unica y carrera valida.');
    }
}

// 2) Eliminar alumno por matricula
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['matricula'])) {
    $matricula = trim($_GET['matricula']);
    if ($matricula !== '') {
        $stmt = $conexion->prepare('DELETE FROM alumnos WHERE matricula = ?');
        $stmt->bind_param('s', $matricula);
        $ok = $stmt->execute();
        $stmt->close();
        redirigir($ok ? 'Alumno eliminado.' : 'No se pudo eliminar el alumno.');
    }
}

// Datos de apoyo para el select de carreras en el formulario
$carreras = $conexion->query('SELECT id_carrera, nombre FROM carreras ORDER BY nombre ASC');

// Estructura por defecto del formulario (modo agregar)
$alumnoEditar = [
    'matricula_original' => '',
    'matricula' => '',
    'nombre' => '',
    'id_carrera' => 0,
    'email' => '',
    'promedio_general' => ''
];

// 3) Cargar alumno en modo edicion
if (isset($_GET['accion']) && $_GET['accion'] === 'editar' && isset($_GET['matricula'])) {
    $matricula = trim($_GET['matricula']);
    if ($matricula !== '') {
        $stmt = $conexion->prepare('SELECT matricula, id_carrera, nombre, email, promedio_general FROM alumnos WHERE matricula = ?');
        $stmt->bind_param('s', $matricula);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($fila = $resultado->fetch_assoc()) {
            $fila['matricula_original'] = $fila['matricula'];
            $alumnoEditar = $fila;
        }
        $stmt->close();
    }
}

// 4) Listado principal de alumnos con nombre de carrera
$listado = $conexion->query('SELECT a.matricula, a.nombre, c.nombre AS carrera, a.email, a.promedio_general
    FROM alumnos a
    INNER JOIN carreras c ON c.id_carrera = a.id_carrera
    ORDER BY a.matricula DESC');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Alumnos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <aside class="app-sidebar">
        <div class="sidebar-brand">
            <a href="#" class="brand-link">
                <span class="brand-text">Control escuela</span>
            </a>
        </div>
        
        <nav class="sidebar-menu">
            <ul class="nav">
                <li>
                    <a href="maestros.php" class="link">
                        <p>Maestros</p>
                    </a>
                </li>
                <li>
                    <a href="#" class="link active">
                        <p>Alumnos</p>
                    </a>
                </li>
                <li>
                    <a href="carreras.php" class="link">
                        <p>Carreras</p>
                    </a>
                </li>
                <li>
                    <a href="especialidades.php" class="link">
                        <p>Especialidades</p>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="head">
                        <h3 class="texto">Alumnos</h3>
                    </div>
                    <div class="head">
                        <ol class="head2">
                            <li class="head2-item"><a href="#">Inicio</a></li>
                            <li class="head2-item active">Alumnos</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alerta">
                <?php echo htmlspecialchars($_GET['msg']); ?>
                <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">×</button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="tabla-total">
                <div class="contenedor-tabla">
                    <h5>
                        <?php echo $alumnoEditar['matricula_original'] !== '' ? 'Editar Alumno' : 'Registro de Alumnos'; ?>
                    </h5>
                    <p class="texto">
                        <?php echo $alumnoEditar['matricula_original'] !== '' ? 'Modifique los datos del alumno' : 'Complete el formulario para registrar un nuevo alumno'; ?>
                    </p>
                    
                    <form method="post" action="alumnos.php">
                        <input type="hidden" name="matricula_original" value="<?php echo htmlspecialchars((string)$alumnoEditar['matricula_original']); ?>">
                        
                        <div class="texto">
                            <label for="matricula" class="form-title">Matrícula</label>
                            <input type="text" class="form-control" id="matricula" name="matricula" 
                                   value="<?php echo htmlspecialchars((string)$alumnoEditar['matricula']); ?>" 
                                   placeholder="Ingrese la matrícula" required>
                            <div class="form-text">Ingrese la matrícula del alumno.</div>
                        </div>

                        <div class="texto">
                            <label for="nombre" class="form-title">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars((string)$alumnoEditar['nombre']); ?>" 
                                   placeholder="Ingrese el nombre completo" required>
                            <div class="form-text">Ingrese el nombre completo del alumno.</div>
                        </div>

                        <div class="texto">
                            <label for="id_carrera" class="form-title">Carrera</label>
                            <select name="id_carrera" class="form-control" id="id_carrera" required>
                                <option value="">Selecciona una carrera</option>
                                <?php 
                                // Resetear el puntero del resultado de carreras
                                $carreras->data_seek(0);
                                while ($c = $carreras->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo (int)$c['id_carrera']; ?>" <?php echo ((int)$alumnoEditar['id_carrera'] === (int)$c['id_carrera']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($c['nombre']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="form-text">Seleccione la carrera del alumno.</div>
                        </div>

                        <div class="texto">
                            <label for="email" class="form-title">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars((string)$alumnoEditar['email']); ?>" 
                                   placeholder="alumno@ejemplo.com" required>
                            <div class="form-text">Ingrese el correo electrónico del alumno.</div>
                        </div>

                        <div class="texto">
                            <label for="promedio_general" class="form-title">Promedio general (0 - 10)</label>
                            <input type="number" step="0.01" min="0" max="10" class="form-control" id="promedio_general" name="promedio_general" 
                                   value="<?php echo htmlspecialchars((string)$alumnoEditar['promedio_general']); ?>" 
                                   placeholder="8.5" required>
                            <div class="form-text">Ingrese el promedio general del alumno.</div>
                        </div>
                        
                        <button type="submit" class="btn btn1">
                            <?php echo $alumnoEditar['matricula_original'] !== '' ? 'Guardar Cambios' : 'Guardar Alumno'; ?>
                        </button>
                        
                        <?php if ($alumnoEditar['matricula_original'] !== ''): ?>
                            <a href="alumnos.php" class="btn btn2">
                                Cancelar
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            
            <div class="tabla-total">
                <div class="contenedor-tabla">
                    <h5>
                        Listado de Alumnos Registrados
                    </h5>
                    <div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Matrícula</th>
                                    <th>Nombre</th>
                                    <th>Carrera</th>
                                    <th>Email</th>
                                    <th>Promedio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($listado->num_rows > 0): ?>
                                    <?php 
                                    // Resetear el puntero del listado si es necesario
                                    $listado->data_seek(0);
                                    while ($a = $listado->fetch_assoc()): 
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($a['matricula']); ?></td>
                                            <td><?php echo htmlspecialchars($a['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($a['carrera']); ?></td>
                                            <td><?php echo htmlspecialchars($a['email']); ?></td>
                                            <td><?php echo htmlspecialchars((string)$a['promedio_general']); ?></td>
                                            <td>
                                                <a href="alumnos.php?accion=editar&matricula=<?php echo urlencode($a['matricula']); ?>" 
                                                   class="btn btn3" title="Editar">
                                                    Editar
                                                </a>
                                                <a href="alumnos.php?accion=eliminar&matricula=<?php echo urlencode($a['matricula']); ?>" 
                                                   class="btn btn4" 
                                                   onclick="return confirm('¿Eliminar alumno?');" title="Eliminar">
                                                    Eliminar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">
                                            No hay alumnos registrados
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>