<?php
// Incluir el archivo de conexión
include 'config.php';

// Consulta para obtener todos los registros
$sql = "SELECT id, nombre, email, telefono FROM usuarios";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Gestión de Usuarios</h1>
        
        <a href="agregar.php" class="btn btn-primary mb-3">
            <i class="fas fa-plus-circle"></i> Agregar Nuevo Usuario
        </a>
        
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado->num_rows > 0) {
                    // Iterar sobre cada fila de resultados
                    while($row = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["telefono"]) . "</td>";
                        echo "<td>";
                        // **NUEVO ENLACE para Ver**
                        echo "<a href='ver.php?id=" . $row["id"] . "' class='btn btn-info btn-sm me-2'>Ver</a>";
                        // Enlace para Editar (Consulta Individual implícita y preparación para Edición)
                        echo "<a href='editar.php?id=" . $row["id"] . "' class='btn btn-warning btn-sm me-2'>Editar</a>";
                        // Enlace para Eliminar
                        echo "<a href='eliminar.php?id=" . $row["id"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de eliminar este registro?\")'>Eliminar</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No hay usuarios registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Cerrar conexión
$conn->close();
?>