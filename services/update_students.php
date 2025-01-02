<?php
require_once '../config/database.php';

function modifyStudent($id, $name, $email, $password, $date){
    global $pdo;

    $updateQuery = "UPDATE alumnos SET nombreUsuario = :nameE, email = :e_mail, password = :pass, fechaCreacion = :dateE WHERE alumnoId = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $params = [
        'id' => $id,
        'nameE' => $name,
        'e_mail' => $email,
        'pass' => $password,
        'dateE' => $date
    ];

    try {
        if ($updateStmt->execute($params)) {
            return ["success" => true, "message" => "Alumno modificado exitosamente."];
        } else {
            return ["success" => false, "message" => "Error al modificar el alumno: " . $pdo->errorInfo()[2]];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error al modificar el alumno: " . $e->getMessage()];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents('debug_log.txt', print_r($_POST, true));

    $id = $_POST['alumnoId'] ?? '';
    $name = $_POST['nombreUsuario'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $date = $_POST['fechaCreacion'] ?? '';

    if ($password !== $confirmPassword) {
        echo json_encode(["success" => false, "message" => "Las contraseñas no coinciden."]);
        exit;
    }

    // Hashear la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $response = modifyStudent($id, $name, $email, $hashedPassword, $date);
    echo json_encode($response);
    exit;
}
?>
