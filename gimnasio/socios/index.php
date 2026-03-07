<?php
include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/navbar.php';
include '../includes/conexion.php';

$base_url = '/gimnasio/';

// Obtener socios (rol Cliente = 5)
$sql = "SELECT u.*, 
        e.nombreCompleto as nombreEntrenador 
        FROM usuarios u 
        LEFT JOIN usuarios e ON u.idEntrenadorAsignado = e.id
        WHERE u.idRol = 5
        ORDER BY u.id DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<div class="c-body">
    <main class="c-main">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h1>Socios</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="create.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Socio
                    </a>
                </div>
            </div>
            
            <?php if (isset($_GET['mensaje'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    if ($_GET['mensaje'] == 'creado') echo "Socio creado correctamente";
                    if ($_GET['mensaje'] == 'actualizado') echo "Socio actualizado correctamente";
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-users me-2"></i> Lista de Socios
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Entrenador</th>
                                    <th>QR</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nombreCompleto']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                        <td>
                                            <?php 
                                            if ($row['idEntrenadorAsignado']) {
                                                echo htmlspecialchars($row['nombreEntrenador'] ?? 'N/A');
                                            } else {
                                                echo '<span class="text-muted">Sin entrenador</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($row['qrCode']): ?>
                                                <button class="btn btn-sm btn-outline-info" onclick="mostrarQR('<?php echo $row['qrCode']; ?>')">
                                                    <i class="fas fa-qrcode"></i>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted">--</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $row['activo'] ? 'success' : 'danger'; ?>">
                                                <?php echo $row['activo'] ? 'Activo' : 'Inactivo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este socio?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No hay socios registrados</p>
                                            <a href="create.php" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Crear primer socio
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal para mostrar QR -->
<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Código QR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrImageContainer"></div>
                <p id="qrText" class="mt-2"></p>
            </div>
        </div>
    </div>
</div>

<script>
function mostrarQR(qrCode) {
    // Usar API pública de QR
    const qrImage = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${qrCode}`;
    document.getElementById('qrImageContainer').innerHTML = `<img src="${qrImage}" class="img-fluid" alt="QR Code">`;
    document.getElementById('qrText').innerText = qrCode;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('qrModal'));
    modal.show();
}
</script>

<?php
$conn->close();
include '../includes/footer.php';
?>