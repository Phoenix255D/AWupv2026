<?php
include '../includes/conexion.php';

$id = (int)$_GET['id'];

// Verificar si tiene membresías o pagos antes de eliminar
$check = $conn->query("SELECT id FROM membresias WHERE idUsuario = $id LIMIT 1");
if ($check->num_rows > 0) {
    // Si tiene membresías, solo desactivar
    $conn->query("UPDATE usuarios SET activo = 0 WHERE id = $id");
} else {
    // Si no tiene nada, eliminar
    $conn->query("DELETE FROM usuarios WHERE id = $id");
}

$conn->close();
header('Location: index.php');
exit;
?>