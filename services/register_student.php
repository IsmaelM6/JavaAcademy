<?php
// services/register_student.php
require_once '../config/database.php';

function registerStudent($nombreUsuario, $email, $password) {
    global $pdo;
    
    // Verificar si el usuario ya existe
    $checkQuery = "SELECT * FROM alumnos WHERE nombreUsuario = :nombreUsuario OR email = :email";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->execute(['nombreUsuario' => $nombreUsuario, 'email' => $email]);
    
    if ($checkStmt->rowCount() > 0) {
        return ["success" => false, "message" => "El nombre de usuario o email ya está en uso."];
    }
    
    // Hashear la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertar nuevo estudiante
    $insertQuery = "INSERT INTO alumnos (nombreUsuario, email, password) VALUES (:nombreUsuario, :email, :password)";
    $insertStmt = $pdo->prepare($insertQuery);
    $params = [
        'nombreUsuario' => $nombreUsuario,
        'email' => $email,
        'password' => $hashedPassword
    ];
    
    if ($insertStmt->execute($params)) {
        return ["success" => true, "message" => "Registro exitoso."];
    } else {
        return ["success" => false, "message" => "Error al registrar: " . $pdo->errorInfo()[2]];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = $_POST['nombreUsuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    if ($password !== $confirmPassword) {
        echo json_encode(["success" => false, "message" => "Las contraseñas no coinciden."]);
        exit;
    }
    
    $response = registerStudent($nombreUsuario, $email, $password);
    echo json_encode($response);
    exit;
}
?>
