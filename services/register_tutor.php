<?php
require_once '../config/database.php';

function registerTutor($nombreTutor, $email, $password) {
    global $pdo;

    // Verificar si el tutor ya existe
    $checkQuery = "SELECT * FROM tutores WHERE nombreTutor = :nombreTutor OR email = :email";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->execute(['nombreTutor' => $nombreTutor, 'email' => $email]);

    if ($checkStmt->rowCount() > 0) {
        return ["success" => false, "message" => "El nombre de usuario o email ya está en uso."];
    }

    // Hashear la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertar nuevo tutor
    $insertQuery = "INSERT INTO tutores (nombreTutor, email, password) VALUES (:nombreTutor, :email, :password)";
    $insertStmt = $pdo->prepare($insertQuery);
    $params = [
        'nombreTutor' => $nombreTutor,
        'email' => $email,
        'password' => $hashedPassword
    ];

    try {
        if ($insertStmt->execute($params)) {
            return ["success" => true, "message" => "Tutor agregado exitosamente."];
        } else {
            return ["success" => false, "message" => "Error al agregar tutor: " . $pdo->errorInfo()[2]];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error al agregar tutor: " . $e->getMessage()];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mostrar datos recibidos para depuración
    file_put_contents('debug_log.txt', print_r($_POST, true));

    $nombreTutor = $_POST['nombreTutor'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if ($password !== $confirmPassword) {
        echo json_encode(["success" => false, "message" => "Las contraseñas no coinciden."]);
        exit;
    }

    $response = registerTutor($nombreTutor, $email, $password);
    echo json_encode($response);
    exit;
}
?>
