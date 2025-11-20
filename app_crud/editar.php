<?php
include 'config.php';

$mensaje = "";

// 1. Procesar el formulario de actualización (si se envió por POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $conn->real_escape_string($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email = $conn->real_escape_string($_POST['email']);
    $telefono = $conn->real_escape_string($_POST['telefono']);

    $sql = "UPDATE usuarios SET nombre='$nombre', email='$email', telefono='$telefono' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página principal
        header("Location: index.php?status=updated");
        exit();
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al actualizar: " . $conn->error . "</div>";
    }
} 

// 2. Mostrar el formulario con los datos actuales (si se accede por GET)
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Consulta Individual
    $sql_select = "SELECT id, nombre, email, telefono FROM usuarios WHERE id=$id";
    $resultado = $conn->query($sql_select);

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
    } else {
        // Si no se encuentra el ID
        header("Location: index.php?error=notfound");
        exit();
    }
} else {
    // Si no se proporciona un ID
    header("Location: index.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Editar Usuario: <?php echo htmlspecialchars($usuario['nombre']); ?></h1>
        
        <?php echo $mensaje; ?>
        
        <form action="editar.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
            </div>
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>