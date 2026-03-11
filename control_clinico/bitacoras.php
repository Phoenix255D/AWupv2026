<?php
session_start();
require("conexion.php");
if ($_SESSION['sesion'] == false) {
    header("Location: Seccion.php");
} else {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuración de pacientes</title>
    <link rel="stylesheet" type="text/css" href="stilo.css">
</head>
<body>
<div class="container">
    <h2>Gestión de medicos</h2>

    <!-- Formulario de Búsqueda -->
    <form action="" method="get">
        <div class="controlsbox">
            <input type="text" class="controls2" name="buscar" placeholder="Buscar" required>
            <button type="submit" name="enviar" class="buttons3">Buscar</button>
        </div>
    </form>

    <!-- Resultados de Búsqueda -->
    <?php if (isset($_GET['enviar'])) {
        $buscar = $_GET['buscar'];
        $Consultar = $linea->query("SELECT * FROM Menu WHERE Nombre LIKE '%$buscar%'"); 
    ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>idMedico</th>
                    <th>Nombre completo</th>
                    <th>Cedula Profesional</th>
                    <th>Especialidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($Mostrar = $Consultar->fetch_array()) { ?>
                <tr>
                    <td><?php echo $Mostrar['Nombre']; ?></td>
                    <td>$<?php echo number_format($Mostrar['Precio'], 2); ?></td>
                    <td><a href="ActualizarMenu.php?id=<?php echo $Mostrar['id'];?>" class="btn btn-warning btn-sm">Modificar</a></td>
                    <td><a href="borrarMenu.php?id=<?php echo $Mostrar['id'];?>" class="btn btn-danger btn-sm">Borrar</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>

    <!-- Tabla Principal -->
    <h3>Lista completa de medicos</h3>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>id de la bitacora</th>
                    <th>id del usuario</th>
                    <th>Fecha de acceso</th>
                    <th>Accion realizada</th>
                    <th>En que modulo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * from bitacora_acceso";
                $resultado = mysqli_query($linea, $sql);
                while ($mostrar = mysqli_fetch_array($resultado)) {
                ?>
                <tr>
                    <td><?php #echo $mostrar['idPaciente']; ?></td>
                    <td><?php #$nombre = $mostrar['nombre'] . " " . $mostrar['apellidoPaterno'] . " " . $mostrar['apellidoMaterno']; echo $nombre; ?></td>
                    <td><?php #echo $mostrar['CURP']; ?></td>
                    <td><?php #echo $mostrar['fechaNacimiento']; ?></td>
                    <td><?php #echo 'SIONO' ?></td>

                </tr>
                <?php } mysqli_free_result($resultado); ?>
            </tbody>
        </table>
    </div>

    <!-- Botón de Regreso -->
    <div class="text-center mt-4">
        <button class="buttons2" onclick="location.href='Seleccion.php'">Regresar</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>