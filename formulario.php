<?php
// Iniciar sesión para usar variables de sesión
session_start();

// Procesar datos del formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar datos POST
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $intereses = $_POST['intereses'] ?? [];
    $comentarios = htmlspecialchars(trim($_POST['comentarios'] ?? ''));
    
    // Validar campos requeridos
    $errores = [];
    
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    }
    
    if (empty($email)) {
        $errores[] = "El email es obligatorio";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido";
    }
    
    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria";
    } elseif (strlen($password) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres";
    }
    
    // Si no hay errores, procesar los datos
    if (empty($errores)) {
        // Guardar datos en sesión (simulando registro)
        $_SESSION['usuario'] = [
            'nombre' => $nombre,
            'email' => $email,
            'genero' => $genero,
            'intereses' => $intereses
        ];
        
        $mensaje_exito = "¡Registro exitoso! Bienvenido/a, $nombre";
    }
}

// Procesar datos GET (para búsqueda/filtros)
$busqueda = htmlspecialchars(trim($_GET['busqueda'] ?? ''));
$categoria = $_GET['categoria'] ?? 'todos';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario PHP - POST y GET</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
        }
        
        .section {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        h1, h2, h3 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        h1 {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 3px solid #3498db;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #34495e;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 5px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .error {
            background-color: #ffeaea;
            color: #c0392b;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #c0392b;
        }
        
        .success {
            background-color: #e8f6e8;
            color: #27ae60;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #27ae60;
        }
        
        .info-box {
            background-color: #e8f4fc;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #3498db;
        }
        
        .info-box h4 {
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        ul {
            padding-left: 20px;
            margin-top: 10px;
        }
        
        .resultados {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-form input[type="text"] {
            flex-grow: 1;
        }
        
        .search-form select {
            width: auto;
            min-width: 150px;
        }
        
        .user-data {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Formulario PHP - Métodos POST y GET</h1>
    
    <?php if (!empty($errores)): ?>
        <div class="error">
            <h3>❌ Errores encontrados:</h3>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (isset($mensaje_exito)): ?>
        <div class="success">
            <h3><?php echo $mensaje_exito; ?></h3>
            <?php if (isset($_SESSION['usuario'])): ?>
                <p>Datos guardados en sesión correctamente.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="container">
        <!-- Sección 1: Formulario POST (Registro) -->
        <div class="section">
            <h2>Formulario de Registro (POST)</h2>
            <p>Los datos enviados con POST no son visibles en la URL y son más seguros para información sensible.</p>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nombre">Nombre completo *</label>
                    <input type="text" id="nombre" name="nombre" 
                           value="<?php echo isset($nombre) ? $nombre : ''; ?>" 
                           placeholder="Ej: María García" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo electrónico *</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo isset($email) ? $email : ''; ?>" 
                           placeholder="ejemplo@correo.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña *</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Mínimo 6 caracteres" required>
                </div>
                
                <div class="form-group">
                    <label>Género</label>
                    <div class="radio-group">
                        <label class="checkbox-item">
                            <input type="radio" name="genero" value="masculino" 
                                   <?php echo (isset($genero) && $genero == 'masculino') ? 'checked' : ''; ?>>
                            Masculino
                        </label>
                        <label class="checkbox-item">
                            <input type="radio" name="genero" value="femenino" 
                                   <?php echo (isset($genero) && $genero == 'femenino') ? 'checked' : ''; ?>>
                            Femenino
                        </label>
                        <label class="checkbox-item">
                            <input type="radio" name="genero" value="otro" 
                                   <?php echo (isset($genero) && $genero == 'otro') ? 'checked' : ''; ?>>
                            Otro
                        </label>
                        <label class="checkbox-item">
                            <input type="radio" name="genero" value="prefiero-no-decir" 
                                   <?php echo (isset($genero) && $genero == 'prefiero-no-decir') ? 'checked' : ''; ?>>
                            Prefiero no decir
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Intereses (selecciona al menos uno)</label>
                    <div class="checkbox-group">
                        <?php 
                        $opciones_intereses = [
                            'tecnologia' => 'Tecnología',
                            'deportes' => 'Deportes',
                            'musica' => 'Música',
                            'viajes' => 'Viajes',
                            'lectura' => 'Lectura',
                            'cocina' => 'Cocina'
                        ];
                        ?>
                        
                        <?php foreach ($opciones_intereses as $valor => $texto): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" name="intereses[]" value="<?php echo $valor; ?>"
                                       <?php echo (isset($intereses) && in_array($valor, $intereses)) ? 'checked' : ''; ?>>
                                <?php echo $texto; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="comentarios">Comentarios adicionales</label>
                    <textarea id="comentarios" name="comentarios" rows="4" 
                              placeholder="¿Alguna observación adicional?"><?php echo isset($comentarios) ? $comentarios : ''; ?></textarea>
                </div>
                
                <button type="submit">Enviar Registro (POST)</button>
            </form>
            
            <?php if (isset($_SESSION['usuario'])): ?>
                <div class="user-data">
                    <h4>Datos del usuario en sesión:</h4>
                    <ul>
                        <li><strong>Nombre:</strong> <?php echo $_SESSION['usuario']['nombre']; ?></li>
                        <li><strong>Email:</strong> <?php echo $_SESSION['usuario']['email']; ?></li>
                        <li><strong>Género:</strong> <?php echo $_SESSION['usuario']['genero'] ?: 'No especificado'; ?></li>
                        <li><strong>Intereses:</strong> 
                            <?php 
                            if (!empty($_SESSION['usuario']['intereses'])) {
                                $intereses_texto = [];
                                foreach ($_SESSION['usuario']['intereses'] as $interes) {
                                    $intereses_texto[] = $opciones_intereses[$interes] ?? $interes;
                                }
                                echo implode(', ', $intereses_texto);
                            } else {
                                echo 'Ninguno seleccionado';
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Sección 2: Formulario GET (Búsqueda) -->
        <div class="section">
            <h2>Formulario de Búsqueda (GET)</h2>
            <p>Los datos enviados con GET son visibles en la URL y son ideales para búsquedas y filtros.</p>
            
            <form method="GET" action="" class="search-form">
                <input type="text" name="busqueda" 
                       value="<?php echo $busqueda; ?>" 
                       placeholder="Buscar productos...">
                <select name="categoria">
                    <option value="todos" <?php echo $categoria == 'todos' ? 'selected' : ''; ?>>Todas las categorías</option>
                    <option value="electronica" <?php echo $categoria == 'electronica' ? 'selected' : ''; ?>>Electrónica</option>
                    <option value="ropa" <?php echo $categoria == 'ropa' ? 'selected' : ''; ?>>Ropa</option>
                    <option value="hogar" <?php echo $categoria == 'hogar' ? 'selected' : ''; ?>>Hogar</option>
                    <option value="libros" <?php echo $categoria == 'libros' ? 'selected' : ''; ?>>Libros</option>
                </select>
                <button type="submit">Buscar (GET)</button>
            </form>
            
            <div class="resultados">
                <h3>Resultados de la búsqueda:</h3>
                
                <?php if (!empty($busqueda) || $categoria != 'todos'): ?>
                    <p><strong>Término buscado:</strong> <?php echo !empty($busqueda) ? $busqueda : 'Ninguno'; ?></p>
                    <p><strong>Categoría seleccionada:</strong> 
                        <?php 
                        $categorias = [
                            'todos' => 'Todas las categorías',
                            'electronica' => 'Electrónica',
                            'ropa' => 'Ropa',
                            'hogar' => 'Hogar',
                            'libros' => 'Libros'
                        ];
                        echo $categorias[$categoria] ?? 'Desconocida';
                        ?>
                    </p>
                    
                    <!-- Simulación de resultados -->
                    <h4>Productos encontrados:</h4>
                    <ul>
                        <?php
                        // Simulación de base de datos
                        $productos_simulados = [
                            ['nombre' => 'Laptop HP', 'categoria' => 'electronica', 'precio' => '$899'],
                            ['nombre' => 'Camiseta Algodón', 'categoria' => 'ropa', 'precio' => '$25'],
                            ['nombre' => 'Sofá 3 plazas', 'categoria' => 'hogar', 'precio' => '$450'],
                            ['nombre' => 'PHP para Principiantes', 'categoria' => 'libros', 'precio' => '$35'],
                            ['nombre' => 'Smartphone Samsung', 'categoria' => 'electronica', 'precio' => '$699'],
                            ['nombre' => 'Jeans Clásicos', 'categoria' => 'ropa', 'precio' => '$49'],
                        ];
                        
                        $resultados_filtrados = [];
                        
                        foreach ($productos_simulados as $producto) {
                            $coincide_busqueda = empty($busqueda) || 
                                                 stripos($producto['nombre'], $busqueda) !== false;
                            $coincide_categoria = $categoria == 'todos' || 
                                                  $producto['categoria'] == $categoria;
                            
                            if ($coincide_busqueda && $coincide_categoria) {
                                $resultados_filtrados[] = $producto;
                            }
                        }
                        
                        if (!empty($resultados_filtrados)) {
                            foreach ($resultados_filtrados as $producto) {
                                echo "<li><strong>{$producto['nombre']}</strong> - " .
                                     "Categoría: {$categorias[$producto['categoria']]} - " .
                                     "Precio: {$producto['precio']}</li>";
                            }
                        } else {
                            echo "<li>No se encontraron productos con los criterios de búsqueda.</li>";
                        }
                        ?>
                    </ul>
                <?php else: ?>
                    <p>Realice una búsqueda para ver resultados aquí.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>