<?php
include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/navbar.php';
include '../includes/conexion.php';

$base_url = '/gimnasio/';

// Obtener lista de entrenadores para el select
$entrenadores = $conn->query("SELECT id, nombreCompleto FROM usuarios WHERE idRol = 4 AND activo = 1");

if ($_POST) {
    $nombre = escapar($_POST['nombre']);
    $email = escapar($_POST['email']);
    $telefono = escapar($_POST['telefono']);
    $fechaNacimiento = $_POST['fechaNacimiento'] ? "'".escapar($_POST['fechaNacimiento'])."'" : "NULL";
    $direccion = $_POST['direccion'] ? "'".escapar($_POST['direccion'])."'" : "NULL";
    $idEntrenador = $_POST['idEntrenador'] ? $_POST['idEntrenador'] : "NULL";
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    // Generar username (email) y password por defecto
    $username = $email;
    $password = password_hash('123456', PASSWORD_DEFAULT);
    
    // Insertar en la base de datos (sin QR primero)
    $sql = "INSERT INTO usuarios (
        nombreCompleto, 
        email, 
        telefono, 
        fechaNacimiento, 
        direccion, 
        username, 
        password_hash, 
        idRol, 
        idEntrenadorAsignado, 
        activo
    ) VALUES (
        '$nombre', 
        '$email', 
        '$telefono', 
        $fechaNacimiento, 
        $direccion, 
        '$username', 
        '$password', 
        5, 
        $idEntrenador, 
        $activo
    )";
    
    if ($conn->query($sql)) {
        $nuevoId = $conn->insert_id; // Obtener el ID recién insertado
        
        // Generar QR basado en el ID y nombre
        // Formato: GYM-[ID]-[INICIALES]
        $iniciales = '';
        $partes = explode(' ', trim($nombre));
        foreach ($partes as $p) {
            if (!empty($p)) $iniciales .= strtoupper(substr($p, 0, 1));
        }
        $iniciales = substr($iniciales, 0, 3); // Máximo 3 iniciales
        
        $qrCode = 'GYM-' . str_pad($nuevoId, 4, '0', STR_PAD_LEFT) . '-' . $iniciales;
        
        // Actualizar el QR
        $conn->query("UPDATE usuarios SET qrCode = '$qrCode' WHERE id = $nuevoId");
        
        header('Location: index.php?mensaje=creado&qr=' . urlencode($qrCode));
    } else {
        $error = "Error al crear: " . $conn->error;
    }
}
?>

<div class="c-body">
    <main class="c-main">
        <div class="container-fluid">
            <h1 class="mb-4">Nuevo Socio</h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-user-plus me-2"></i> Datos del Socio
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <!-- Columna izquierda -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Información Personal</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Nombre Completo *</label>
                                    <input type="text" name="nombre" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Teléfono *</label>
                                    <input type="text" name="telefono" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" name="fechaNacimiento" class="form-control">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Dirección</label>
                                    <textarea name="direccion" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            
                            <!-- Columna derecha -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Configuración de Cuenta</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Entrenador Asignado</label>
                                    <select name="idEntrenador" class="form-select">
                                        <option value="">-- Sin entrenador --</option>
                                        <?php while($row = $entrenadores->fetch_assoc()): ?>
                                            <option value="<?php echo $row['id']; ?>">
                                                <?php echo $row['nombreCompleto']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="activo" class="form-check-input" checked>
                                        <label class="form-check-label">Cuenta Activa</label>
                                    </div>
                                    <small class="text-muted">Si está activo, el socio puede acceder al sistema</small>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Nota:</strong> El username será el email y la contraseña por defecto es <strong>123456</strong>
                                </div>
                                
                                <div class="alert alert-success">
                                    <i class="fas fa-qrcode me-2"></i>
                                    <strong>QR:</strong> Se generará automáticamente con formato <strong>GYM-0001-JP</strong> (ID + Iniciales)
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="text-end">
                            <a href="<?php echo $base_url; ?>socios/index.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Socio
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