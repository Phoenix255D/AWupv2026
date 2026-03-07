<?php
include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/navbar.php';
include '../includes/conexion.php';

$base_url = '/gimnasio/';
$id = (int)$_GET['id'];

// Obtener lista de entrenadores
$entrenadores = $conn->query("SELECT id, nombreCompleto FROM usuarios WHERE idRol = 4 AND activo = 1");

// Obtener datos del socio
$result = $conn->query("SELECT * FROM usuarios WHERE id = $id");
$row = $result->fetch_assoc();

if (!$row) {
    header('Location: index.php?error=no_encontrado');
    exit;
}

if ($_POST) {
    $nombre = escapar($_POST['nombre']);
    $email = escapar($_POST['email']);
    $telefono = escapar($_POST['telefono']);
    $fechaNacimiento = $_POST['fechaNacimiento'] ? "'".escapar($_POST['fechaNacimiento'])."'" : "NULL";
    $direccion = $_POST['direccion'] ? "'".escapar($_POST['direccion'])."'" : "NULL";
    $idEntrenador = $_POST['idEntrenador'] ? $_POST['idEntrenador'] : "NULL";
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    $sql = "UPDATE usuarios SET 
        nombreCompleto = '$nombre',
        email = '$email',
        telefono = '$telefono',
        fechaNacimiento = $fechaNacimiento,
        direccion = $direccion,
        idEntrenadorAsignado = $idEntrenador,
        activo = $activo
        WHERE id = $id";
    
    if ($conn->query($sql)) {
        header('Location: index.php?mensaje=actualizado');
    } else {
        $error = "Error al actualizar: " . $conn->error;
    }
}
?>

<div class="c-body">
    <main class="c-main">
        <div class="container-fluid">
            <h1 class="mb-4">Editar Socio #<?php echo $id; ?></h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <i class="fas fa-edit me-2"></i> Datos del Socio
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <!-- Columna izquierda -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Información Personal</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Nombre Completo *</label>
                                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($row['nombreCompleto']); ?>" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Teléfono *</label>
                                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($row['telefono']); ?>" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" name="fechaNacimiento" value="<?php echo $row['fechaNacimiento']; ?>" class="form-control">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Dirección</label>
                                    <textarea name="direccion" class="form-control" rows="2"><?php echo htmlspecialchars($row['direccion']); ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Columna derecha -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Configuración de Cuenta</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Entrenador Asignado</label>
                                    <select name="idEntrenador" class="form-select">
                                        <option value="">-- Sin entrenador --</option>
                                        <?php 
                                        $entrenadores->data_seek(0); // Reiniciar el puntero
                                        while($ent = $entrenadores->fetch_assoc()): 
                                        ?>
                                            <option value="<?php echo $ent['id']; ?>" 
                                                <?php echo $row['idEntrenadorAsignado'] == $ent['id'] ? 'selected' : ''; ?>>
                                                <?php echo $ent['nombreCompleto']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="activo" class="form-check-input" <?php echo $row['activo'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Cuenta Activa</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">QR Code</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="<?php echo $row['qrCode'] ?? 'No generado'; ?>" readonly disabled>
                                        <button class="btn btn-outline-secondary" type="button" onclick="alert('Función para generar QR')">
                                            <i class="fas fa-qrcode"></i> Regenerar
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="alert alert-secondary">
                                    <small>
                                        <strong>Usuario:</strong> <?php echo $row['username']; ?><br>
                                        <strong>Último acceso:</strong> <?php echo $row['ultimoAcceso'] ?? 'Nunca'; ?><br>
                                        <strong>Creado:</strong> <?php echo $row['created_at']; ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="text-end">
                            <a href="<?php echo $base_url; ?>socios/index.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
$conn->close();
include '../includes/footer.php';
?>