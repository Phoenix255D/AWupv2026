<?php
require_once 'config.php';

// CRUD de maestros:
// - Crea y actualiza por POST
// - Elimina y carga datos a editar por GET
// - Muestra formulario + listado final
function redirigir($mensaje)
{
    header('Location: maestros.php?msg=' . urlencode($mensaje));
    exit;
}

// 1) Guardar (alta/edicion) de maestro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idOriginal = trim($_POST['idOriginal'] ?? '');
    $id = trim($_POST['id'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $id_especialidad = isset($_POST['id_especialidad']) ? (int)$_POST['id_especialidad'] : 0;
    $email = trim($_POST['email'] ?? '');
    $telefono = isset($_POST['telefono']) ? (float)$_POST['telefono'] : -1;

    if ($id === '' || $nombre === '' || $id_especialidad <= 0 || $email === '' || $telefono === '') {
        redirigir('Datos invalidos. Verifica los campos.');
    }

    $stmt = $conexion->prepare('SELECT nombre FROM especialidades WHERE id = ?');
    $stmt->bind_param('s', $id_especialidad);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Si existe id original, se trata de una edicion.
    if ($idOriginal !== '') {
        $stmt = $conexion->prepare('UPDATE maestros SET id = ?, nombre = ?, id_especialidad = ?, email = ?, telefono = ?, especialidad = ? WHERE id = ?');
        $stmt->bind_param('ssisss', $id, $nombre, $id_especialidad, $email, $telefono, $resultado,$id);
        $ok = $stmt->execute();
        $stmt->close();
        redirigir($ok ? 'maestro actualizado.' : 'No se pudo actualizar el maestro.');
    } else {
        // Alta de nuevo maestro.
        $stmt = $conexion->prepare('INSERT INTO maestros (id, id_especialidad, nombre, email, telefono = ?, especialidad = ?) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sissd', $id, $id_especialidad, $nombre, $email, $telefono, $resultado);
        $ok = $stmt->execute();
        $stmt->close();
        redirigir($ok ? 'maestro agregado.' : 'No se pudo agregar el maestro. Revisa id unica y especialidad valida.');
    }
}

// 2) Eliminar maestro por id
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    $id = trim($_GET['id']);
    if ($id !== '') {
        $stmt = $conexion->prepare('DELETE FROM maestros WHERE id = ?');
        $stmt->bind_param('s', $id);
        $ok = $stmt->execute();
        $stmt->close();
        redirigir($ok ? 'maestro eliminado.' : 'No se pudo eliminar el maestro.');
    }
}

// Datos de apoyo para el select de especialidades en el formulario
$especialidades = $conexion->query('SELECT id, nombre FROM especialidades ORDER BY nombre ASC');

// Estructura por defecto del formulario (modo agregar)
$maestroEditar = [
    'idOriginal' => '',
    'id' => '',
    'nombre' => '',
    'id_especialidad' => 0,
    'email' => '',
    'telefono' => ''
];

// 3) Cargar maestro en modo edicion
if (isset($_GET['accion']) && $_GET['accion'] === 'editar' && isset($_GET['id'])) {
    $id = trim($_GET['id']);
    if ($id !== '') {
        $stmt = $conexion->prepare('SELECT id, id_especialidad, nombre, email, telefono, especialidad FROM maestros WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($fila = $resultado->fetch_assoc()) {
            $fila['id'] = $fila['id'];
            $maestroEditar = $fila;
        }
        $stmt->close();
    }
}

// 4) Listado principal de maestros con nombre de carrera
$listado = $conexion->query('SELECT m.id, m.nombre, e.nombre AS carrera, m.email, m.telefono
    FROM maestros m
    INNER JOIN especialidades e ON e.id = m.id_especialidad
    ORDER BY m.id DESC');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Maestros</title>
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
                    <a href="#" class="link active">
                        <p>Maestros</p>
                    </a>
                </li>
                <li>
                    <a href="umnos.php" class="link">
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
                        <h3 class="texto">Maestros</h3>
                    </div>
                    <div class="head">
                        <ol class="head2">
                            <li class="head2-item"><a href="#">Inicio</a></li>
                            <li class="head2-item active">Maestros</li>
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
                        <?php echo $maestroEditar['idOriginal'] !== '' ? 'Editar maestro' : 'Registro de Maestros'; ?>
                    </h5>
                    <p class="texto">
                        <?php echo $maestroEditar['idOriginal'] !== '' ? 'Modifique los datos del maestro' : 'Complete el formulario para registrar un nuevo maestro'; ?>
                    </p>
                    
                    <form method="post" action="maestros.php">
                        <input type="hidden" name="idOriginal" value="<?php echo htmlspecialchars((string)$maestroEditar['idOriginal']); ?>">
                        
                        <div class="texto">
                            <label for="id" class="form-title">Id</label>
                            <input type="text" class="form-control" id="id" name="id" 
                                   value="<?php echo htmlspecialchars((string)$maestroEditar['id']); ?>" 
                                   placeholder="Ingrese la id" required>
                            <div class="form-text">Ingrese el Id del maestro.</div>
                        </div>

                        <div class="texto">
                            <label for="nombre" class="form-title">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars((string)$maestroEditar['nombre']); ?>" 
                                   placeholder="Ingrese el nombre completo" required>
                            <div class="form-text">Ingrese el nombre completo del maestro.</div>
                        </div>

                        <div class="texto">
                            <label for="id_especialidad" class="form-title">Especialidad</label>
                            <select name="id_especialidad" class="form-control" id="id_especialidad" required>
                                <option value="">Selecciona una especialidad</option>
                                <?php 
                                // Resetear el puntero del resultado de carreras
                                $especialidades->data_seek(0);
                                while ($e = $especialidades->fetch_assoc()): 
                                    echo "aa";
                                ?>
                                    <option value="<?php echo (int)$e['id']; ?>" <?php echo ((int)$maestroEditar['id_especialidad'] === (int)$e['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($e['nombre']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="form-text">Seleccione la especialidad del maestro.</div>
                        </div>

                        <div class="texto">
                            <label for="email" class="form-title">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars((string)$maestroEditar['email']); ?>" 
                                   placeholder="maestro@ejemplo.com" required>
                            <div class="form-text">Ingrese el correo electrónico del maestro.</div>
                        </div>

                        <div class="texto">
                            <label for="telefono" class="form-title">telefono</label>
                            <input type="telefono" class="form-control" id="telefono" name="telefono" 
                                   value="<?php echo htmlspecialchars((string)$maestroEditar['telefono']); ?>" 
                                   placeholder="1235551234" required>
                            <div class="form-text">Ingrese el telefono del maestro.</div>
                        </div>
                        
                        <button type="submit" class="btn btn1">
                            <?php echo $maestroEditar['id'] !== '' ? 'Guardar Cambios' : 'Guardar maestro'; ?>
                        </button>
                        
                        <?php if ($maestroEditar['id'] !== ''): ?>
                            <a href="maestros.php" class="btn btn2">
                                Cancelar
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            
            <div class="tabla-total">
                <div class="contenedor-tabla">
                    <h5>
                        Listado de Maestros Registrados
                    </h5>
                    <div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Especialidad</th>
                                    <th>Email</th>
                                    <th>Telefono</th>
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
                                            <td><?php echo htmlspecialchars($a['id']); ?></td>
                                            <td><?php echo htmlspecialchars($a['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($a['especialidad']); ?></td>
                                            <td><?php echo htmlspecialchars($a['email']); ?></td>
                                            <td><?php echo htmlspecialchars((string)$a['telefono']); ?></td>
                                            <td>
                                                <a href="maestros.php?accion=editar&id=<?php echo urlencode($a['id']); ?>" 
                                                   class="btn btn3" title="Editar">
                                                    Editar
                                                </a>
                                                <a href="maestros.php?accion=eliminar&id=<?php echo urlencode($a['id']); ?>" 
                                                   class="btn btn4" 
                                                   onclick="return confirm('¿Eliminar maestro?');" title="Eliminar">
                                                    Eliminar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">
                                            No hay maestros registrados
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