<?php
include 'config.php';

// 1. Verificar que se haya pasado un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// 2. Preparar la consulta con un marcador de posición (?)
// CAMBIO 1: Eliminamos la necesidad de $conn->real_escape_string()
// y usamos un marcador de posición para la seguridad.
$sql_select = "SELECT id, nombre, email, telefono FROM usuarios WHERE id = ?";

try {
    // Preparar la sentencia
    $stmt = $conn->prepare($sql_select);
    
    // Ejecutar la sentencia, pasando el ID como un array.
    // PDO se encarga de sanear y bindear (atar) el valor de forma segura.
    $stmt->execute([$_GET['id']]);
    
    // Obtener el resultado
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // El usuario fue encontrado (fetch devuelve el array)
    } else {
        // Si fetch devuelve false (o null), el registro no existe
        $error_msg = "El registro con ID " . htmlspecialchars($_GET['id']) . " no fue encontrado.";
    }

} catch (PDOException $e) {
    // Manejo de errores de la base de datos
    $error_msg = "Error de base de datos: " . $e->getMessage();
}

// CAMBIO 2: Liberar la sentencia preparada
$stmt = null; 

// CAMBIO 3: Cerrar la conexión PDO
$conn = null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Detalles del Usuario</h1>
        
        <?php if (isset($error_msg)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_msg; ?>
            </div>
            <a href="index.php" class="btn btn-secondary">Volver a la Lista</a>
        <?php else: ?>
            
            <div class="card">
                <div class="card-header bg-info text-white">
                    Usuario ID: <?php echo htmlspecialchars($usuario['id']); ?>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono']); ?>
                    </li>
                </ul>
            </div>
            
            <div class="mt-3">
                <a href="editar.php?id=<?php echo $usuario['id']; ?>" class="btn btn-warning me-2">Editar Usuario</a>
                <a href="index.php" class="btn btn-secondary">Volver a la Lista</a>
            </div>
            
        <?php endif; ?>
    </div>
</body>
</html>