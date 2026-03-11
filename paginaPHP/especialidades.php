<?php
require_once 'config.php';

// CRUD de especialidades:
// - Crea y actualiza por POST
// - Elimina y carga datos a editar por GET
// - Muestra formulario + listado final
function redirigirespecialidades($mensaje)
{
    header('Location: especialidades.php?msg=' . urlencode($mensaje));
    exit;
}

// 1) Guardar (alta/edicion) de especialidad
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_especialidad = isset($_POST['id_especialidad']) ? (int)$_POST['id_especialidad'] : 0;
    $nombre = trim($_POST['nombre'] ?? '');

    if ($nombre === '') {
        redirigirespecialidades('El nombre de la especialidad es obligatorio.');
    }

    // Si llega id_especialidad, actualiza; en caso contrario, inserta.
    if ($id_especialidad > 0) {
        $stmt = $conexion->prepare('UPDATE especialidades SET nombre = ? WHERE id_especialidad = ?');
        $stmt->bind_param('si', $nombre, $id_especialidad);
        $ok = $stmt->execute();
        $stmt->close();
        redirigirespecialidades($ok ? 'Especialidad actualizada.' : 'No se pudo actualizar la especialidad.');
    } else {
        $stmt = $conexion->prepare('INSERT INTO especialidades (nombre) VALUES (?)');
        $stmt->bind_param('s', $nombre);
        $ok = $stmt->execute();
        $stmt->close();
        redirigirespecialidades($ok ? 'Especialidad agregada.' : 'No se pudo agregar la especialidad (nombre duplicado).');
    }
}

// 2) Eliminar especialidad
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id_especialidad'])) {
    $id_especialidad = (int)$_GET['id_especialidad'];
    if ($id_especialidad > 0) {
        $stmt = $conexion->prepare('DELETE FROM especialidades WHERE id = ?');
        $stmt->bind_param('i', $id_especialidad);
        $ok = $stmt->execute();
        $stmt->close();
        redirigirespecialidades($ok ? 'Especialidad eliminada.' : 'No se pudo eliminar la especialidad. Si tiene alumnos relacionados, eliminarlos o reasignarlos primero.');
    }
}

// Estructura por defecto del formulario (modo agregar)
$especialidadEditar = [
    'id' => 0,
    'nombre' => ''
];

// 3) Cargar especialidad en modo edicion
if (isset($_GET['accion']) && $_GET['accion'] === 'editar' && isset($_GET['id_especialidad'])) {
    $id_especialidad = (int)$_GET['id_especialidad'];
    $stmt = $conexion->prepare('SELECT id, nombre FROM especialidades WHERE id = ?');
    $stmt->bind_param('i', $id_especialidad);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($fila = $resultado->fetch_assoc()) {
        $especialidadEditar = $fila;
    }
    $stmt->close();
}

// 4) Listado principal de especialidades
$listado = $conexion->query('SELECT id, nombre FROM especialidades ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>especialidades</title>
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
                    <a href="alumnos.php" class="link">
                        <p>Alumnos</p>
                    </a>
                </li>
                <li>
                    <a href="carreras.php" class="link active">
                        <p>Carreras</p>
                    </a>
                </li>
                <li>
                    <a href="#" class="link">
                        <p>Especialidades</p>
                    </a>
                </li>
        </nav>
    </aside>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="head">
                        <h3 class="texto">especialidades</h3>
                    </div>
                    <div class="head">
                        <ol class="head2">
                            <li class="head2-item"><a href="#">Inicio</a></li>
                            <li class="head2-item active">especialidades</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="">
                        <?php echo htmlspecialchars($_GET['msg']); ?>
                        <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">×</button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="tabla-total">
                        <div class="contenedor-tabla">
                            <h5>
                                <?php echo $especialidadEditar['id'] > 0 ? 'Editar Especialidad' : 'Registro de especialidades'; ?>
                            </h5>
                            <p class="texto">
                                <?php echo $especialidadEditar['id'] > 0 ? 'Modifique los datos de la especialidad' : 'Complete el formulario para registrar una nueva especialidad'; ?>
                            </p>
                            
                            <form method="post" action="especialidades.php">
                                <input type="hidden" name="id" value="<?php echo (int)$especialidadEditar['id']; ?>">
                                
                                <div class="texto">
                                    <label for="nombre" class="form-title">Nombre de la especialidad</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars((string)$especialidadEditar['nombre']); ?>" 
                                           placeholder="Ingrese el nombre de la especialidad" required>
                                    <div class="form-text">Ingrese el nombre completo de la especialidad.</div>
                                </div>
                                
                                <button type="submit" class="btn btn1">
                                    <?php echo $especialidadEditar['id'] > 0 ? 'Guardar Cambios' : 'Guardar Carrera'; ?>
                                </button>
                                
                                <?php if ($especialidadEditar['id'] > 0): ?>
                                    <a href="especialidades.php" class="btn btn2">
                                     Cancelar
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                    
                    <div class="tabla-total">
                        <div class="contenedor-tabla">
                            <h5>
                                Listado de especialidades
                            </h5>
                            <div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($listado->num_rows > 0): ?>
                                            <?php while ($c = $listado->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo (int)$c['id']; ?></td>
                                                    <td><?php echo htmlspecialchars($c['nombre']); ?></td>
                                                    <td>
                                                        <a href="especialidades.php?accion=editar&id_especialidad=<?php echo (int)$c['id_especialidad']; ?>" 
                                                           class="btn btn3" title="Editar">Editar
                                                        </a>
                                                        <a href="especialidades.php?accion=eliminar&id_especialidad=<?php echo (int)$c['id_carrera']; ?>" 
                                                           class="btn btn4" onclick="return confirm('¿Eliminar especialidad?');" title="Eliminar">
                                                        Eliminar
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3">
                                                    No hay especialidades registradas
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>