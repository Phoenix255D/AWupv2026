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
				<label for="nombre" >Nombre de la especialidad</label>
				<input type="text" class="controls" id="nombre" name="nombre" required>
			</div>

			<div class="mb-3">
				<label for="nombre" >Descripcion</label>
				<input type="text" class="controls" id="nombre2" name="nombre2" required>
			</div>


			<button type="submit" name="Guardar" class="buttons">Guardar</button>
		</form>
	</div>
    		<input type="button" class="buttons2" value="Regresar" onclick="location.href='especialidadesMedicas.php'">

</section>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
