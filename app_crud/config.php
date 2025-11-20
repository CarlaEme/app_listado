<?php
// Usaremos variables de entorno de Render para la conexión
// Si no están definidas (ej. en desarrollo local), usamos los valores por defecto
define('DB_HOST', getenv('HOST') ?: 'localhost');
define('DB_USER', getenv('USER') ?: 'root');
define('DB_PASSWORD', getenv('PASSWORD') ?: '12345678');
define('DB_NAME', getenv('DATABASE') ?: 'crud_app');

// Render suele proveer la URL de la BD en la variable DATABASE_URL
$db_url = getenv('DATABASE_URL');

if ($db_url) {
    // Si estamos en Render, parseamos la URL de conexión de PostgreSQL
    $url = parse_url($db_url);
    $host = $url['host'];
    $port = $url['port'] ?? 5432;
    $user = $url['user'];
    $pass = $url['pass'];
    $db = substr($url['path'], 1); // Quitar la barra inicial
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
} else {
    // Si estamos en desarrollo local
    $dsn = "pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $user = DB_USER;
    $pass = DB_PASSWORD;
}


try {
    // Crear la conexión PDO
    $pdo = new PDO($dsn, $user, $pass);
    // Configurar el modo de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Para simplificar el resto del CRUD, usaremos la variable $conn
    $conn = $pdo; 
    
} catch (PDOException $e) {
    // En producción, solo mostrar un error genérico
    $error_message = (getenv('RENDER') == 'true') ? "Error de conexión a la BD." : "Error de conexión: " . $e->getMessage();
    die($error_message);
}

// Nota: Tendrás que adaptar las consultas de tu CRUD para usar $conn->prepare()
// y $conn->execute() en lugar de $conn->query() para PDO.
?>