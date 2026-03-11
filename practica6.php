<?php
if(isset($_POST['num_estudiantes'])) {
    $num_estudiantes = $_POST['num_estudiantes'];
    ?>
    <form method="post">
        <input type="hidden" name="num_estudiantes" value="<?php echo $num_estudiantes; ?>">
        <?php for($i = 1; $i <= $num_estudiantes; $i++): ?>
        <h3>Estudiante <?php echo $i; ?></h3>
        <label>Nombre:</label>
        <input type="text" name="nombre<?php echo "$i";?>" required><br><br>
        <label>Sexo:</label>
        <select name="sexo<?php echo "$i";?>">
        <option value="H" selected>Hombre</option>
        <option value="M">Mujer</option>
        </select>
        <?php endfor; ?>
        <input type="submit" name="guardar" value="Guardar">
    </form>
    <?php
}

if(isset($_POST['guardar'])) {
    $num_estudiantes = $_POST['num_estudiantes'];
    echo "<h3>Nombres de las mujeres:</h3>";
    for($i = 1; $i <= $num_estudiantes; $i++) {
    $sexo = $_POST['sexo'.$i];
    $nombre = $_POST['nombre'.$i];
        if(strtoupper($sexo) == 'M') {
            echo $nombre . "<br>";
        }
    }
} else {
    ?>
    <form method="post">
        <label>Número de estudiantes a capturar:</label>
        <input type="number" name="num_estudiantes" min="1" required>
        <input type="submit" value="Continuar">
    </form>
    <?php
}
?>