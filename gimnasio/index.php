<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/navbar.php';
include 'includes/conexion.php';  // <-- AGREGAR ESTA LÍNEA

// Estadísticas
$resultSocios = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE idRol = 5");
$totalSocios = $resultSocios->fetch_assoc()['total'];

$resultActivos = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE idRol = 5 AND activo = 1");
$totalActivos = $resultActivos->fetch_assoc()['total'];
?>

<div class="c-body">
    <main class="c-main">
        <div class="container-fluid">
            <h1 class="mb-4">Dashboard</h1>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5>Total Socios</h5>
                            <h2><?php echo $totalSocios; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5>Socios Activos</h5>
                            <h2><?php echo $totalActivos; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
$conn->close(); // Cerrar conexión
include 'includes/footer.php';
?>