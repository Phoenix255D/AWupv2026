<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro de medico</title>
	<link href="stilo.css" rel="stylesheet">
</head>
<body>
<section class="container">

	<h1>Registrar medico</h1>

	<?php 
	require("conexion.php");
	$id = $_GET["id"];
	$sql = "SELECT * FROM control_pacientes WHERE idPaciente = '$id'";
	$resultado = mysqli_query($linea, $sql);

	?>
	<div class="form-login3">
		<form method="post" action="modificarAdmin.php">

			<div class="mb-3">
				<label for="nombre" >Nombre(s)</label>
				<input type="text" class="controls" id="nombre" name="nombre" required>
			</div>

			<div class="mb-3">
				<label for="nombre" >Apellido paterno</label>
				<input type="text" class="controls" id="nombre2" name="nombre2" required>
			</div>

			<div class="mb-3">
				<label for="nombre" >Apellido materno</label>
				<input type="text" class="controls" id="nombre3" name="nombre3" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Cedula profesional</label>
				<input type="date" class="controls" id="fechaN" name="fechaN" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Especialidad</label>
				<input list="especialidad" class="controls" id="sexo" name="sexo" required>
			<datalist id="especialidad">
  <option value="Otorrino">
  <option value="Fisiologia">
  <option value="Pediatria">
  <option value="Nose">
</datalist>

			</div>

			<div class="mb-3">
				<label for="contrasena" >Telefono</label>
				<input type="text" class="controls" id="telefono" name="telefono" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Correo electronico</label>
				<input type="text" class="controls" id="correoE" name="correoE" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Horario de atencion</label>
				<input type="text" class="controls" id="numDir" name="numDir" required>
			</div>

			<button type="submit" name="Guardar" class="buttons">Guardar</button>
		</form>
	</div>
    		<input type="button" class="buttons2" value="Regresar" onclick="location.href='controlM.php'">

</section>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
