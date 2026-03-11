<!DOCTYPE html>
<?php
session_start();
require("conexion.php");

if (isset($_POST['acceder'])) {
    $usua = $_POST['usuario'];
    $contra = $_POST['contraseña'];
    $usus = null;
    $contrar = null;

    if ($usua != null && $contra != null) {
        // Buscar en la tabla de usuarios
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usua'";
        $resultado = mysqli_query($linea, $sql);

        while ($empa = mysqli_fetch_assoc($resultado)) {
            $usus = $empa['usuario'];
            $idU = $empa['idUsuario'];
            $contrar = $empa['contraseña'];
            $tipo = $empa['rol'];
        }
        if ($usus === null) {
        echo '<script>alert("Usuario no encontrado");</script>';
        // Redirigir o mostrar error
        }else{
        if ($usua == $usus) {
            if (password_verify($contra, $contrar)) {
                $_SESSION['sesion'] = true;
                $_SESSION['idUsu'] = $idU;
                $_SESSION['tipo'] = $tipo;
                header("Location: Seleccion.php");
                die();
            } else {
                echo '<meta http-equiv="refresh" content="0; URL=Seccion.php">
                <script>alert("Contraseña incorrecta");</script>';
            }
        }
    }
    }
}
?>
	<link rel="stylesheet" href="stilo.css">


<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso de Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
    <section class="container2">
<section class="form-login3">
    <h1><i>Inicio de sesión</i></h1>

    <form method="POST" action="">
        <input class="controls" type="text" name="usuario" placeholder="Usuario" required>
        <input class="controls" type="password" name="contraseña" placeholder="Contraseña" required>
        <input class="buttons" type="submit" name="acceder" value="Acceder">
        <input class="buttons" type="button" value="Regresar" onclick="location.href='index.php'">
    </form>
    </section>
</section>
</body>
</html>
