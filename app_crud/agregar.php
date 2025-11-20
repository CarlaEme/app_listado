<?php
include 'config.php';

$mensaje = "";

// Verificar si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Obtener los datos del formulario (sin necesidad de real_escape_string)
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono']; // Puede estar vacío

    // 2. Consulta de Inserción con Marcadores de Posición (?)
    // CAMBIO 1: Usamos '?' en lugar de concatenar las variables directamente.
    $sql = "INSERT INTO usuarios (nombre, email, telefono) VALUES (?, ?, ?)";

    try {
        // CAMBIO 2: Preparar la sentencia
        $stmt = $conn->prepare($sql);
        
        // CAMBIO 3: Ejecutar la sentencia, pasando los valores como un array.
        // PDO bindea y sanea automáticamente los valores.
        $stmt->execute([$nombre, $email, $telefono]);
        
        // Verificar si se insertó alguna fila (PDO devuelve 1 si fue exitoso)
        if ($stmt->rowCount() > 0) { 
            // Redirigir a la página principal después de la inserción exitosa
            header("Location: index.php?status=added");
            exit();
        } else {
             $mensaje = "<div class='alert alert-danger'>Error al agregar el usuario. No se insertó ninguna fila.</div>";
        }

    } catch (PDOException $e) {
        // CAMBIO 4: Manejar errores de la base de datos (ej. email duplicado)
        // PostgreSQL arrojará un error si el email es UNIQUE y se duplica.
        $mensaje = "<div class='alert alert-danger'>Error al agregar el usuario: " . $e->getMessage() . "</div>";
    }
}

// CAMBIO 5: Cerrar la conexión PDO
$conn = null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Agregar Nuevo Usuario</h1>
        
        <?php echo $mensaje; ?>
        
        <form action="agregar.php" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono">
            </div>
            <button type="submit" class="btn btn-success">Guardar Usuario</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>