<?php
$linea = mysqli_connect("localhost","root","","examenP");
$sql = "SELECT * FROM tabla1";
$resultado = mysqli_query($linea, $sql);
	echo '<table>';
	echo '<thead><tr><th>id</th><th>Nombre</th><th>Descripcion corta</th><th>Descripcion larga</th><th>Fecha de creacion</th><th>fecha terminacion</th><th>costo</th><th>Nombre responsable</th></tr></thead>';
while ($empa = mysqli_fetch_array($resultado)) {
	echo "<tr>";            
    echo "<td>{$empa['idProyecto']}</td>";
    echo "<td>{$empa['nombre']}</td>";
    echo "<td>{$empa['descripcionCorta']}</td>";
    echo "<td>{$empa['descripcionLarga']}</td>";
    echo "<td>{$empa['fechaCreacion']}</td>";
    echo "<td>{$empa['fechaTerminacion']}</td>";
    echo "<td>{$empa['costo']}</td>";
    echo "<td>{$empa['nombreResponsable']}</td>";
    echo "</tr>";
        }
	echo '</table>';


if (isset($_POST['formulario']))
{
    $nombre = $_POST['nombre'];
    $descC = $_POST['descripcionC'];
    $descL = $_POST['descripcionL'];
    $fechaC = $_POST['fechaC'];
    $fechaT = $_POST['fechaT'];
    $costo = $_POST['costo'];
    $nombreR = $_POST['nombreR'];

    $sql2 = "INSERT INTO `tabla1`(`nombre`, `descripcionCorta`, `descripcionLarga`, `fechaCreacion`, `fechaTerminacion`, `costo`, `nombreREsponsable`) 
    VALUES ('$nombre','$descC','$descL','$fechaC','$fechaT','$costo','$nombreR')";
    $resultado = mysqli_query($linea, $sql2);

}
?>

<html>

<body>
<form method="post" action="">
<input id="nombre" name="nombre" placeholder="nombre" required>

<input id="descripcionC" name="descripcionC" placeholder="Descripcion corta" required>

<input id="descripcionL" name="descripcionL" placeholder="Descripcion larga" required>

<input id="fechaC" name="fechaC" type=date required>

<input id="fechaT" name="fechaT" type=date required>

<input id="costo" name="costo" placeholder="costo" type=number required>

<input id="nombreR" name="nombreR" placeholder="nombre responsable" required>

<input class="buttons" type="submit" name="formulario" value="registrar">
</form>



</body>

</html>