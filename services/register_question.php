<?php
require_once '../config/database.php';

function registerQuestion($module, $question, $optionA, $optionB, $optionC, $correctOpt){
    global $pdo;

    $checkQuery = "SELECT * FROM ejercicios WHERE pregunta = :question";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->execute(['question' => $question]);

    if ($checkStmt->rowCount() > 0){
        return ["success" => false, "message" => "La pregunta ya fue ingresada."];
    }

    $insertQuery = "INSERT INTO ejercicios (moduloId, pregunta, opcion_a, opcion_b, opcion_c, resp_crrct) VALUES (:module, :question, :optionA, :optionB, :optionC, :correctOpt)";
    $inserStmt = $pdo->prepare($insertQuery);
    $params = [
        'module' => $module,
        'question' => $question,
        'optionA' => $optionA,
        'optionB' => $optionB,
        'optionC' => $optionC,
        'correctOpt' => $correctOpt
    ];

    try{
        if ($inserStmt->execute($params)){
            return ["success" => true, "message" => "Pregunta agregada con exito."];
        } else {
            return ["success" => false, "message" => "Error al agregar la pregunta: " . $pdo->errorInfo()[2]];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error al agregar la pregunta: " . $pdo->getMessage()];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents('debug_log.txt', print_r($_POST, true));

    $module = $_POST['moduloId'] ?? '';
    $question = $_POST['pregunta'] ?? '';
    $optionA = $_POST['opcion_a'] ?? '';
    $optionB = $_POST['opcion_b'] ?? '';
    $optionC = $_POST['opcion_c'] ?? '';
    $correctOpt = $_POST['resp_crrct'] ?? '';

    $response = registerQuestion($module, $question, $optionA, $optionB, $optionC, $correctOpt);
    echo json_encode($response);
    exit;
}
?>