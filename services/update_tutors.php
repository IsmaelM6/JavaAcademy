<?php
require_once '../config/database.php';

function modifyTutor($id, $name, $email, $password, $date){
    global $pdo;

    $updateQuery = "UPDATE tutores SET nombreTutor = :nameT, email = :e_mail, password = :pass, fechaCreacion = :dateT WHERE tutorId = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $params = [
        'id' => $id,
        'nameT' => $name,
        'e_mail' => $email,
        'pass' => $password,
        'dateT' => $date
    ];

    try {
        if ($updateStmt->execute($params)) {
            return ["success" => true, "message" => "Tutor modificado exitosamente."];
        } else {
            return ["success" => false, "message" => "Error al modificar al tutor: " . $pdo->errorInfo()[2]];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error al modificar al tutor: " . $e->getMessage()];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mostrar datos recibidos para depuración
    file_put_contents('debug_log.txt', print_r($_POST, true));

    $id = $_POST['tutorId'] ?? '';
    $name = $_POST['nombreTutor'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $date = $_POST['fechaCreacion'] ?? '';

    // Validar que las contraseñas coincidan
    if ($password !== $confirmPassword) {
        echo json_encode(["success" => false, "message" => "Las contraseñas no coinciden."]);
        exit;
    }

    // Hashear la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Llamar a la función con la contraseña hasheada
    $response = modifyTutor($id, $name, $email, $hashedPassword, $date);
    echo json_encode($response);
    exit;
}
?>
