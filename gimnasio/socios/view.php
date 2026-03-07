<?php
include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/navbar.php';
include '../includes/conexion.php';

$base_url = '/gimnasio/';
$id = (int)$_GET['id'];

// Obtener datos completos del socio
$sql = "SELECT u.*, 
        e.nombreCompleto as nombreEntrenador,
        r.nombre as nombreRol
        FROM usuarios u 
        LEFT JOIN usuarios e ON u.idEntrenadorAsignado = e.id
        LEFT JOIN roles r ON u.idRol = r.id
        WHERE u.id = $id";

$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    header('Location: index.php?error=no_encontrado');
    exit;
}

$socio = $result->fetch_assoc();

// Obtener membresías del socio
$membresias = $conn->query("
    SELECT m.*, tm.nombre as tipo_membresia, tm.precioBase 
    FROM membresias m
    JOIN tiposMembresia tm ON m.idTipoMembresia = tm.id
    WHERE m.idUsuario = $id
    ORDER BY m.fechaInicio DESC
");

// Obtener pagos del socio
$pagos = $conn->query("
    SELECT * FROM pagos 
    WHERE idUsuario = $id 
    ORDER BY fechaPago DESC 
    LIMIT 5
");
?>

<div class="c-body">
    <main class="c-main">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h1>Detalle del Socio</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
            </div>
            
            <div class="row">
                <!-- Columna izquierda - Foto y datos básicos -->
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-user-circle me-2"></i> Perfil
                        </div>
                        <div class="card-body text-center">
                            <?php if ($socio['fotoURL']): ?>
                                <img src="<?php echo $base_url . $socio['fotoURL']; ?>" 
                                     class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            <?php else: ?>
                                <i class="fas fa-user-circle fa-5x text-muted mb-3"></i>
                            <?php endif; ?>
                            
                            <h4><?php echo htmlspecialchars($socio['nombreCompleto']); ?></h4>
                            <p class="text-muted">ID: #<?php echo $socio['id']; ?></p>
                            
                            <span class="badge bg-<?php echo $socio['activo'] ? 'success' : 'danger'; ?> fs-6 p-2 mb-3">
                                <?php echo $socio['activo'] ? 'ACTIVO' : 'INACTIVO'; ?>
                            </span>
                            
                            <?php if ($socio['qrCode']): ?>
                                <div class="mt-3">
                                    <button class="btn btn-outline-primary" onclick="mostrarQR('<?php echo $socio['qrCode']; ?>')">
                                        <i class="fas fa-qrcode me-2"></i> Ver QR
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-phone-alt me-2"></i> Contacto
                        </div>
                        <div class="card-body">
                            <p><strong>Email:</strong><br> <?php echo htmlspecialchars($socio['email']); ?></p>
                            <p><strong>Teléfono:</strong><br> <?php echo htmlspecialchars($socio['telefono']); ?></p>
                            <p><strong>Dirección:</strong><br> <?php echo htmlspecialchars($socio['direccion'] ?? 'No especificada'); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Columna derecha - Información detallada -->
                <div class="col-md-8">
                    <!-- Datos personales -->
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-user me-2"></i> Datos Personales
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Fecha Nacimiento:</strong><br> 
                                        <?php echo $socio['fechaNacimiento'] ? date('d/m/Y', strtotime($socio['fechaNacimiento'])) : 'No especificada'; ?>
                                    </p>
                                    <p><strong>Edad:</strong><br> 
                                        <?php 
                                        if ($socio['fechaNacimiento']) {
                                            $edad = date_diff(date_create($socio['fechaNacimiento']), date_create('today'))->y;
                                            echo $edad . ' años';
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Rol:</strong><br> <?php echo $socio['nombreRol'] ?? 'Cliente'; ?></p>
                                    <p><strong>Entrenador:</strong><br> 
                                        <?php echo $socio['nombreEntrenador'] ?? '<span class="text-muted">Sin entrenador asignado</span>'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Membresías -->
                    <div class="card mb-3">
                        <div class="card-header bg-warning text-white">
                            <i class="fas fa-id-card me-2"></i> Membresías
                        </div>
                        <div class="card-body">
                            <?php if ($membresias && $membresias->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Inicio</th>
                                                <th>Fin</th>
                                                <th>Estado</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($m = $membresias->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $m['tipo_membresia']; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($m['fechaInicio'])); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($m['fechaFin'])); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        echo $m['estado'] == 'activa' ? 'success' : 
                                                            ($m['estado'] == 'expirada' ? 'danger' : 'warning'); 
                                                    ?>">
                                                        <?php echo $m['estado']; ?>
                                                    </span>
                                                </td>
                                                <td>$<?php echo number_format($m['precioPagado'], 2); ?></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center mb-0">No tiene membresías registradas</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Últimos pagos -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-credit-card me-2"></i> Últimos Pagos
                        </div>
                        <div class="card-body">
                            <?php if ($pagos && $pagos->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Concepto</th>
                                                <th>Monto</th>
                                                <th>Método</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($p = $pagos->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo date('d/m/Y', strtotime($p['fechaPago'])); ?></td>
                                                <td><?php echo $p['concepto']; ?></td>
                                                <td>$<?php echo number_format($p['montoFinal'], 2); ?></td>
                                                <td><?php echo $p['metodoPago']; ?></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center mb-0">No tiene pagos registrados</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Información del sistema -->
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <i class="fas fa-cog me-2"></i> Información del Sistema
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Username:</strong><br> <?php echo htmlspecialchars($socio['username']); ?></p>
                                    <p><strong>QR Code:</strong><br> <?php echo $socio['qrCode'] ?? 'No generado'; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Creado:</strong><br> <?php echo date('d/m/Y H:i', strtotime($socio['created_at'])); ?></p>
                                    <p><strong>Último acceso:</strong><br> <?php echo $socio['ultimoAcceso'] ? date('d/m/Y H:i', strtotime($socio['ultimoAcceso'])) : 'Nunca'; ?></p>
                                    <p><strong>Actualizado:</strong><br> <?php echo $socio['updated_at'] ? date('d/m/Y H:i', strtotime($socio['updated_at'])) : 'Nunca'; ?></p>
                                </div>
                            </div>
                        </div>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function mostrarQR(qrCode) {
    const qrImage = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${qrCode}`;
    document.getElementById('qrImageContainer').innerHTML = `<img src="${qrImage}" class="img-fluid" alt="QR Code">`;
    document.getElementById('qrText').innerText = qrCode;
    
    const modal = new bootstrap.Modal(document.getElementById('qrModal'));
    modal.show();
}
</script>

<?php
$conn->close();
include '../includes/footer.php';
?>