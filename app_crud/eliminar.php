<?php
include 'config.php';

// Asegurarse de que el ID esté presente en la URL
if (isset($_GET['id'])) {
    
    // 1. Consulta de Eliminación con Marcador de Posición (?)
    $sql = "DELETE FROM usuarios WHERE id = ?";

    try {
        // Preparar la sentencia
        // CAMBIO 1: Se usa prepare() en lugar de concatenar el string.
        $stmt = $conn->prepare($sql);
        
        // Ejecutar la sentencia, pasando el ID de forma segura.
        // CAMBIO 2: Se usa execute() para enviar el valor.
        $stmt->execute([$_GET['id']]);

        // Verificar si se eliminó alguna fila
        if ($stmt->rowCount() > 0) {
            // Redirigir a la página principal después de la eliminación exitosa
            header("Location: index.php?status=deleted");
            exit();
        } else {
            // El ID no fue encontrado o no se pudo eliminar (ej. no existe)
            header("Location: index.php?error=notfound"); 
            exit();
        }

    } catch (PDOException $e) {
        // CAMBIO 3: Manejar errores de la base de datos con try/catch
        echo "Error al eliminar el registro: " . $e->getMessage();
        // Opcional: Redirigir con mensaje de error, pero mostrarlo es más informativo aquí.
        // header("Location: index.php?error=deletefail"); 
    }
    
    // Liberar la sentencia
    $stmt = null; 

} else {
    // Si no se proporciona un ID
    header("Location: index.php");
    exit();
}

// CAMBIO 4: Cerrar la conexión PDO
$conn = null;
?>