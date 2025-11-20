<?php
include 'config.php';

$mensaje = "";
$usuario = null;
$error_db = null;

// =========================================================
// 1. PROCESAR LA ACTUALIZACIÓN (MÉTODO POST)
// =========================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener los datos del formulario POST
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    if ($id) {
        // Consulta UPDATE con Marcadores de Posición (?)
        // CAMBIO 1: Uso de '?' para todos los valores.
        $sql_update = "UPDATE usuarios SET nombre = ?, email = ?, telefono = ? WHERE id = ?";
        
        try {
            // Preparar la sentencia
            $stmt = $conn->prepare($sql_update);
            
            // Ejecutar la sentencia, pasando todos los valores en un array
            // PDO bindea y sanea automáticamente.
            $stmt->execute([$nombre, $email, $telefono, $id]);
            
            // Verificar si se afectó alguna fila
            if ($stmt->rowCount() > 0) {
                header("Location: index.php?status=updated");
                exit();
            } else {
                // Si rowCount es 0, puede que los datos fueran idénticos
                $mensaje = "<div class='alert alert-info'>No se realizaron cambios o el usuario no existe.</div>";
            }

        } catch (PDOException $e) {
            // Manejar errores de DB (ej. email duplicado, llave foránea, etc.)
            $mensaje = "<div class='alert alert-danger'>Error al actualizar el usuario: " . $e->getMessage() . "</div>";
            $error_db = true; // Usar esta bandera para evitar intentar cargar el formulario después de un fallo POST.
        }
        $stmt = null; // Liberar la sentencia
    }
} 

// =========================================================
// 2. MOSTRAR EL FORMULARIO (MÉTODO GET o después de un POST fallido)
// =========================================================

// Usamos el ID del POST (si hubo un error) o el ID del GET
$id_a_buscar = $_GET['id'] ?? $_POST['id'] ?? null;

// Solo intentar cargar si tenemos un ID y no hubo un error grave en la actualización
if ($id_a_buscar && !$error_db) {
    
    // Consulta SELECT con Marcador de Posición (?)
    // CAMBIO 2: Sentencia preparada para SELECT.
    $sql_select = "SELECT id, nombre, email, telefono FROM usuarios WHERE id = ?";
    
    try {
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->execute([$id_a_buscar]);
        
        // CAMBIO 3: Obtener el resultado
        $usuario = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            // Si el ID no existe
            header("Location: index.php?error=notfound");
            exit();
        }
    } catch (PDOException $e) {
        // Manejo de errores de base de datos
        $mensaje = "<div class='alert alert-danger'>Error al cargar datos: " . $e->getMessage() . "</div>";
    }
    $stmt_select = null; // Liberar la sentencia
} 
// Si llegamos aquí sin un ID (y no es POST), redirigir
elseif (!$id_a_buscar && $_SERVER["REQUEST_METHOD"] != "POST") { 
    header("Location: index.php");
    exit();
}

// CAMBIO 4: Cerrar la conexión PDO
$conn = null;
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
        <h1 class="mb-4">Editar Usuario: <?php echo htmlspecialchars($usuario['nombre'] ?? 'ID Desconocido'); ?></h1>
        
        <?php echo $mensaje; ?>
        
        <?php if ($usuario): // Solo mostrar el formulario si el usuario se cargó ?>
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
        <?php endif; ?>
    </div>
</body>
</html>