<?php
include 'config.php';

// 1. Verificar que se haya pasado un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Si no hay ID, redirigir a la lista principal
    header("Location: index.php");
    exit();
}

$id = $conn->real_escape_string($_GET['id']);
$usuario = null;

// 2. Consulta Individual
$sql_select = "SELECT id, nombre, email, telefono FROM usuarios WHERE id=$id";
$resultado = $conn->query($sql_select);

if ($resultado->num_rows == 1) {
    $usuario = $resultado->fetch_assoc();
} else {
    // Si el ID no existe
    $error_msg = "El registro con ID $id no fue encontrado.";
}

$conn->close();
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