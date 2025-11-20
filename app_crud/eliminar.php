<?php
include 'config.php';

// Asegurarse de que el ID esté presente en la URL
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Consulta de Eliminación
    $sql = "DELETE FROM usuarios WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página principal después de la eliminación exitosa
        header("Location: index.php?status=deleted");
        exit();
    } else {
        // Manejar el error de eliminación
        echo "Error al eliminar el registro: " . $conn->error;
        // Opcional: Redirigir con mensaje de error
        // header("Location: index.php?error=deletefail"); 
    }
} else {
    // Si no se proporciona un ID
    header("Location: index.php");
    exit();
}

$conn->close();
?>