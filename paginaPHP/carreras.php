<?php
require_once 'config.php';

// CRUD de carreras:
// - Crea y actualiza por POST
// - Elimina y carga datos a editar por GET
// - Muestra formulario + listado final
function redirigirCarreras($mensaje)
{
    header('Location: carreras.php?msg=' . urlencode($mensaje));
    exit;
}

// 1) Guardar (alta/edicion) de carrera
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_carrera = isset($_POST['id_carrera']) ? (int)$_POST['id_carrera'] : 0;
    $nombre = trim($_POST['nombre'] ?? '');

    if ($nombre === '') {
        redirigirCarreras('El nombre de la carrera es obligatorio.');
    }

    // Si llega id_carrera, actualiza; en caso contrario, inserta.
    if ($id_carrera > 0) {
        $stmt = $conexion->prepare('UPDATE carreras SET nombre = ? WHERE id_carrera = ?');
        $stmt->bind_param('si', $nombre, $id_carrera);
        $ok = $stmt->execute();
        $stmt->close();
        redirigirCarreras($ok ? 'Carrera actualizada.' : 'No se pudo actualizar la carrera.');
    } else {
        $stmt = $conexion->prepare('INSERT INTO carreras (nombre) VALUES (?)');
        $stmt->bind_param('s', $nombre);
        $ok = $stmt->execute();
        $stmt->close();
        redirigirCarreras($ok ? 'Carrera agregada.' : 'No se pudo agregar la carrera (nombre duplicado).');
    }
}

// 2) Eliminar carrera
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id_carrera'])) {
    $id_carrera = (int)$_GET['id_carrera'];
    if ($id_carrera > 0) {
        $stmt = $conexion->prepare('DELETE FROM carreras WHERE id_carrera = ?');
        $stmt->bind_param('i', $id_carrera);
        $ok = $stmt->execute();
        $stmt->close();
        redirigirCarreras($ok ? 'Carrera eliminada.' : 'No se pudo eliminar la carrera. Si tiene alumnos relacionados, eliminarlos o reasignarlos primero.');
    }
}

// Estructura por defecto del formulario (modo agregar)
$carreraEditar = [
    'id_carrera' => 0,
    'nombre' => ''
];

// 3) Cargar carrera en modo edicion
if (isset($_GET['accion']) && $_GET['accion'] === 'editar' && isset($_GET['id_carrera'])) {
    $id_carrera = (int)$_GET['id_carrera'];
    $stmt = $conexion->prepare('SELECT id_carrera, nombre FROM carreras WHERE id_carrera = ?');
    $stmt->bind_param('i', $id_carrera);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($fila = $resultado->fetch_assoc()) {
        $carreraEditar = $fila;
    }
    $stmt->close();
}

// 4) Listado principal de carreras
$listado = $conexion->query('SELECT id_carrera, nombre FROM carreras ORDER BY id_carrera DESC');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carreras</title>
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
                    <a href="#" class="link active">
                        <p>Carreras</p>
                    </a>
                </li>
                <li>
                    <a href="especialidades.php" class="link">
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
                        <h3 class="texto">Carreras</h3>
                    </div>
                    <div class="head">
                        <ol class="head2">
                            <li class="head2-item"><a href="#">Inicio</a></li>
                            <li class="head2-item active">Carreras</li>
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
                                <?php echo $carreraEditar['id_carrera'] > 0 ? 'Editar Carrera' : 'Registro de Carreras'; ?>
                            </h5>
                            <p class="texto">
                                <?php echo $carreraEditar['id_carrera'] > 0 ? 'Modifique los datos de la carrera' : 'Complete el formulario para registrar una nueva carrera'; ?>
                            </p>
                            
                            <form method="post" action="carreras.php">
                                <input type="hidden" name="id_carrera" value="<?php echo (int)$carreraEditar['id_carrera']; ?>">
                                
                                <div class="texto">
                                    <label for="nombre" class="form-title">Nombre de la carrera</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars((string)$carreraEditar['nombre']); ?>" 
                                           placeholder="Ingrese el nombre de la carrera" required>
                                    <div class="form-text">Ingrese el nombre completo de la carrera.</div>
                                </div>
                                
                                <button type="submit" class="btn btn1">
                                    <?php echo $carreraEditar['id_carrera'] > 0 ? 'Guardar Cambios' : 'Guardar Carrera'; ?>
                                </button>
                                
                                <?php if ($carreraEditar['id_carrera'] > 0): ?>
                                    <a href="carreras.php" class="btn btn2">
                                     Cancelar
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                    
                    <div class="tabla-total">
                        <div class="contenedor-tabla">
                            <h5>
                                Listado de Carreras
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
                                                    <td><?php echo (int)$c['id_carrera']; ?></td>
                                                    <td><?php echo htmlspecialchars($c['nombre']); ?></td>
                                                    <td>
                                                        <a href="carreras.php?accion=editar&id_carrera=<?php echo (int)$c['id_carrera']; ?>" 
                                                           class="btn btn3" title="Editar">Editar
                                                        </a>
                                                        <a href="carreras.php?accion=eliminar&id_carrera=<?php echo (int)$c['id_carrera']; ?>" 
                                                           class="btn btn4" onclick="return confirm('¿Eliminar carrera?');" title="Eliminar">
                                                        Eliminar
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3">
                                                    No hay carreras registradas
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