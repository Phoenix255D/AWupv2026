<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Actualizar del paciente</title>
	<link rel="stylesheet" href="stilo.css">
</head>
<body>
<section class="container">

	<h1>Actualizar paciente</h1>

	<?php 
	require("conexion.php");
	$id = $_GET["id"];
	$sql = "SELECT * FROM control_pacientes WHERE idPaciente = '$id'";
	$resultado = mysqli_query($linea, $sql);

	echo '<table class="table table-bordered">';
	echo '<thead class="table-dark"><tr><th>id</th><th>Nombre completo</th><th>CURP</th><th>Fecha de nacimiento</th></tr></thead>';
	while ($mostrar = mysqli_fetch_array($resultado)) {
		$nombre = $mostrar['nombre'] . " " . $mostrar['apellidoPaterno'] . " " . $mostrar['apellidoMaterno'];
		echo "<tr>";
		echo "<td>{$mostrar['idPaciente']}</td>";
		echo "<td>{$nombre}</td>";
		echo "<td>{$mostrar['CURP']}</td>";
		echo "<td>{$mostrar['fechaNacimiento']}</td>";

		echo "</tr>";
	}
	echo '</table>';
	?>

	<?php
	$resultado = mysqli_query($linea, $sql);
	while ($mostrar = mysqli_fetch_array($resultado)) {
	?>
	
		<form class="form-login3" method="post" action="modificarAdmin.php">
			<input type="hidden" name="id" value="<?php echo $mostrar["id"]; ?>">

				<label class="text" for="nombre" class="form-label">Nombre(s)</label>
				<input class="controls" type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $mostrar["nombre"]; ?>" required>

				<label for="nombre" class="form-label">Apellido paterno</label>
				<input class="controls" type="text" class="form-control" id="nombre2" name="nombre2" value="<?php echo $mostrar["apellidoPaterno"]; ?>" required>

				<label for="nombre" class="form-label">Apellido materno</label>
				<input class="controls" type="text" class="form-control" id="nombre3" name="nombre3" value="<?php echo $mostrar["apellidoMaterno"]; ?>" required>

				<label for="contrasena" class="form-label">CURP</label>
				<input class="controls" type="text" class="form-control" id="curp" name="curp" value="<?php echo $mostrar["CURP"]; ?>" required>

				<label for="contraBor" class="form-label">Fecha de nacimiento</label>
				<input class="controls" type="date" class="form-control" id="fechaN" name="fechaN" value="<?php echo $mostrar["fechaNacimiento"]; ?>" required>

				<label for="contrasena" class="form-label">Sexo</label>
				<input class="controls" type="text" class="form-control" id="sexo" name="sexo" value="<?php echo $mostrar["sexo"]; ?>" required>

				<label for="contrasena" class="form-label">Telefono</label>
				<input class="controls" type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $mostrar["telefono"]; ?>" required>

				<label for="contrasena" class="form-label">Correo electronico</label>
				<input class="controls" type="text" class="form-control" id="correoE" name="correoE" value="<?php echo $mostrar["correoElectronico"]; ?>" required>

				<label for="contrasena" class="form-label">Numero de direccion</label>
				<input class="controls" type="text" class="form-control" id="numDir" name="numDir" value="<?php echo $mostrar["numeroDir"]; ?>" required>

				<label for="contrasena" class="form-label">Numero de direccion interior</label>
				<input class="controls" type="text" class="form-control" id="numIntDir" name="numIntDir" value="<?php echo $mostrar["numeroInteriorDir"]; ?>">

				<label for="contrasena" class="form-label">Calle</label>
				<input class="controls" type="text" class="form-control" id="calle" name="calle" value="<?php echo $mostrar["calle"]; ?>" required>

				<label for="contrasena" class="form-label">Fraccionamiento/Colonia</label>
				<input class="controls" type="text" class="form-control" id="fracc" name="fracc" value="<?php echo $mostrar["fracc/col"]; ?>" required>

				<label for="contrasena" class="form-label">Codigo postal</label>
				<input class="controls" type="text" class="form-control" id="codigoP" name="codigoP" value="<?php echo $mostrar["codigoPostal"]; ?>" required>

				<label for="contrasena" class="form-label">Ciudad</label>
				<input class="controls" type="text" class="form-control" id="ciudad" name="ciudad" value="<?php echo $mostrar["ciudad"]; ?>" required>

				<label for="contrasena" class="form-label">Estado</label>
				<input class="controls" type="text" class="form-control" id="estado" name="estado" value="<?php echo $mostrar["estado"]; ?>" required>

				<label for="contrasena" class="form-label">Contacto de emergencia</label>
				<input class="controls" type="text" class="form-control" id="contactoE" name="contactoE" value="<?php echo $mostrar["contactoEmergencia"]; ?>" required>

				<label for="contrasena" class="form-label">Telefono de emergencia</label>
				<input class="controls" type="text" class="form-control" id="telefonoE" name="telefonoE" value="<?php echo $mostrar["telefonoEmergencia"]; ?>" required>

				<label for="contrasena" class="form-label">Alergias</label>
				<input class="controls" type="text" class="form-control" id="alergias" name="alergias" value="<?php echo $mostrar["alergias"]; ?>" required>

				<label for="contrasena" class="form-label">Antecedentes medicos</label>
				<input class="controls" type="text" class="form-control" id="aMedicos" name="aMedicos" value="<?php echo $mostrar["antecedentesMedicos"]; ?>" required>

				<label for="contrasena" class="form-label">Fecha de registro</label>
				<input class="controls" type="date" class="form-control" id="fechaN" name="fechaN" value="<?php $fecha = new DateTime($mostrar["fechaRegistro"]); $fecha1 = $fecha->format('Y-m-d'); echo $fecha1; ?>" disabled>

				<label for="contrasena" class="form-label">Estatus:</label>
				<label for="contrasena" class="form-label"><?php if($mostrar["estatus"] == 1) {echo "Activo";}else{echo "Inactivo";} ?></label>
			</div>

			<button type="submit" name="Guardar" class="buttons">Guardar
		</form>
				<button type="button" class="buttons2" onclick="location.href='configUsu.php'">Regresar</button>

	</section>
	<?php } ?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
