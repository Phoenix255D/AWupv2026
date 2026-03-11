<?php  
session_start();
if ($_SESSION['sesion'] == false) {
    header("Location: Seccion.php");
} else {
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Panel Administrador</title>

	<link rel="stylesheet" href="style.css">
</head>
<body>
<?php if($_SESSION['tipo'] == 'Admin'){
?>
<header>
    <div class="bx bx-menu" id="menu-icon"></div>
    <ul class="navbar">
        <li><a href='controlM.php'>Control de medicos</a></li>
        <li><a href='controlP.php'>Control de pacientes</a></li>
        <li><a href='especialidadesMedicas.php'>Especialidades medicas</a></li>
        <li><a href='configUsu.php'>Control de usuarios</a></li>
        <li><a href='bitacoras.php'>Control de bitacoras</a></li>
    </ul>
</header>
<?php } else if($_SESSION['tipo'] == 'Medico'){
    ?>
<header>
    <div class="bx bx-menu" id="menu-icon"></div>
    <ul class="navbar">
        <li><a href='controlP.php'>Control de pacientes</a></li>
        <li><a href='registrousu.php'>Control de agendas</a></li>
        <li><a href='registrousu.php'>Control de expedientes</a></li>    
    </ul>
</header>
    <?php 
    } else if($_SESSION['tipo'] == 'Recepcionista'){
    ?>
<header>
    <div class="bx bx-menu" id="menu-icon"></div>
    <ul class="navbar">
        <li><a href='registrousu.php'>Control de agendas</a></li>
        <li><a href='registrousu.php'>Reportes</a></li>
        <li><a href='registrousu.php'>Gestor de tarifas</a></li>
    </ul>
</header>
    <?php 
    }
    ?>
<br><br><br><br><br>

<h2>
<center>
<br><br><br>
<?php if($_SESSION['tipo'] == 'Admin'){
?>
Control de medicos. <br><br>
Control de pacientes. <br><br>
Especialidades medicas. <br><br>
Control de usuarios. <br><br>
Control de bitacoras. <br><br>
Especialidades medicas. <br><br>

<?php } else if($_SESSION['tipo'] == 'Medico'){
    ?>
Control de pacientes. <br><br>
Control de agendas. <br><br>
Control de expedientes. <br><br>
Especialidades medicas. <br><br>

    <?php 
    } else if($_SESSION['tipo'] == 'Recepcionista'){
    ?>
Control de agendas. <br><br>
Reportes. <br><br>
Gestor de tarifas. <br><br>

    <?php 
    }
    ?>

</center>
</h2>

</body>
</html>
<?php } 
?>
