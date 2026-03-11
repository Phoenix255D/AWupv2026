<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro de paciente</title>
	<link href="stilo.css" rel="stylesheet">
</head>
<body>
<section class="container">

	<h1>Registrar paciente</h1>

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
				<label for="contrasena" >CURP</label>
				<input type="text" class="controls" id="curp" name="curp" required>
			</div>

			<div class="mb-3">
				<label for="contraBor" >Fecha de nacimiento</label>
				<input type="date" class="controls" id="fechaN" name="fechaN" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Sexo</label>
				<input type="text" class="controls" id="sexo" name="sexo" required>
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
				<label for="contrasena" >Numero de direccion</label>
				<input type="text" class="controls" id="numDir" name="numDir" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Numero de direccion interior</label>
				<input type="text" class="controls" id="numIntDir" name="numIntDir" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Calle</label>
				<input type="text" class="controls" id="calle" name="calle" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Fraccionamiento/Colonia</label>
				<input type="text" class="controls" id="fracc" name="fracc" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Codigo postal</label>
				<input type="text" class="controls" id="codigoP" name="codigoP" required>
			</div>
			
			<div class="mb-3">
				<label for="contrasena" >Ciudad</label>
				<input type="text" class="controls" id="ciudad" name="ciudad" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Estado</label>
				<input type="text" class="controls" id="estado" name="estado" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Contacto de emergencia</label>
				<input type="text" class="controls" id="contactoE" name="contactoE" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Telefono de emergencia</label>
				<input type="text" class="controls" id="telefonoE" name="telefonoE" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Alergias</label>
				<input type="text" class="controls" id="alergias" name="alergias" required>
			</div>

			<div class="mb-3">
				<label for="contrasena" >Antecedentes medicos</label>
				<input type="text" class="controls" id="aMedicos" name="aMedicos">
			</div>

			<button type="submit" name="Guardar" class="buttons">Guardar</button>
		</form>
	</div>
    		<input type="button" class="buttons2" value="Regresar" onclick="location.href='configUsu.php'">

</section>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
