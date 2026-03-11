<?php 
require("conexion.php");
        echo '<script>alert("Funcion no implementada");
        location.href = "configUsu";
</script>';

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$contra = $_POST['contrasena'];
$contraP = $_POST['contraBor'];

if($contraP == "LASARGOANDO"){
$actualizar = "UPDATE registro SET nombre='$nombre', contraseña='$contra' WHERE id='$id'";
$resultado = mysqli_query($linea,$actualizar);

if($resultado){
	echo "<script> alert('Se actualizaron los datos sera dirigido a la pagina anterior'); window.location='registrousu.php'</script>";
} else {
    echo "<script> alert('No se actualizaron los datos intentelo otra vez'); window.location='registrousu.php'</script>";
}
}else{
    echo "<script> alert('Contraseña incorrecta'); window.location='registrousu.php'</script>";	
}
?>