<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'javaacademy2');
define('DB_USER', 'root');
define('DB_PASS', 'chicorita123');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    die("ERROR: No se pudo conectar. " . $e->getMessage());
}

return $pdo;
?>
