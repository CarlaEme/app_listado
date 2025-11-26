<?php
// Incluir el archivo de conexión (que ahora devuelve un objeto PDO en $conn)
include 'config.php';

// Consulta para obtener todos los registros
$sql = "SELECT id, nombre, email, telefono FROM usuarios";

// CAMBIO 1: Ejecutar la consulta con $conn->query() (igual que mysqli, devuelve un PDOStatement)
$resultado = $conn->query($sql);

// CAMBIO 2: Obtener todos los resultados en un array asociativo.
// Esto es más robusto en PDO para contar las filas.
$usuarios = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Liberar el resultado de la consulta (buena práctica)
$resultado = null; 
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
        <h1 class="mb-4 text-center">Gestión de Empleados</h1>
        <h2 class="mb-4 text-center">Dirección de Recursos Humanos</h2>
        
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
                // CAMBIO 3: Usar count() en el array $usuarios para verificar si hay registros.
                if (count($usuarios) > 0) {
                    // CAMBIO 4: Iterar sobre el array $usuarios usando foreach (más idiomático en PDO/PHP)
                    foreach($usuarios as $row) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["telefono"]) . "</td>";
                        echo "<td>";
                        // Enlaces
                        echo "<a href='ver.php?id=" . $row["id"] . "' class='btn btn-info btn-sm me-2'>Ver</a>";
                        echo "<a href='editar.php?id=" . $row["id"] . "' class='btn btn-warning btn-sm me-2'>Editar</a>";
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


</table>
  </div>

  <footer class="bg-dark text-white mt-3 py-3">
        <div class="container">
            <div class="row">
    
    <div class="container mt-3 mb-5">
        <hr>
        <h2 class="mb-4 text-center">Enlaces</h2>
        <div class="list-group">
            
            <a 
                href="https://youtu.be/GHBhBkJjWD4?si=wESBAg9i0TAo7iZs" 
                class="list-group-item list-group-item-action" 
                target="_blank"
                rel="noopener noreferrer">
                Ver Video 1: Recursos Humanos
            </a>

            <a 
                href="https://youtu.be/AcSI9FsUW4A?si=EemR-8fUhhNjNhaU" 
                class="list-group-item list-group-item-action" 
                target="_blank"
                rel="noopener noreferrer">
                Ver Video 2: Funciones de RH
            </a>
            
           <a 
        href="https://docs.google.com/spreadsheets/d/11_z-0YAeTDF1YjLQBBcgDDZLGbNbeN4GoNCs9c66JSI/edit?usp=sharing" 
        class="list-group-item list-group-item-action list-group-item-info" 
        target="_blank"
        rel="noopener noreferrer">
        Ver Empleados en Nómina 
        </a>

        <div class="col-12 mb-3">
                    <h5 class="text-white text-center">Conceptualización de Servicios en la Nube</h5>
                    <p class="text-white text-center">Carla Judith Meza Ronquillo @2025
                    </p>
                </div>

              
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
         
<?php
// CAMBIO 5: Cerrar conexión PDO (estableciendo la variable a null)
$conn = null;
?>