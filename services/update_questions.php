<?php
require_once '../config/database.php';

function modifyQuestion($exercise,$module, $question, $optionA, $optionB, $optionC, $correctOpt){
    global $pdo;

    $updateQuery = "UPDATE ejercicios SET moduloId = :module, pregunta = :question, opcion_a = :optionA, opcion_b = :optionB, opcion_c = :optionC, resp_crrct = :correctOpt WHERE ejercicioId = :exercise";
    $updateStmt = $pdo->prepare($updateQuery);
    $params = [
        'exercise' => $exercise,
        'module' => $module,
        'question' => $question,
        'optionA' => $optionA,
        'optionB' => $optionB,
        'optionC' => $optionC,
        'correctOpt' => $correctOpt
    ];

    try{
        if ($updateStmt->execute($params)){
            return ["success" => true, "message" => "Pregunta modificada con éxito." ];
        } else {
            return ["success" => false, "message" => "Error al modificar la pregunta." . $pdo->errorInfo()[2]];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error al agregar la pregunta: " . $pdo->getMessage()];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents('debug_log.txt', print_r($_POST, true));

    $exercise = $_POST['ejercicioId'] ?? '';
    $module = $_POST['moduloId'] ?? '';
    $question = $_POST['pregunta'] ?? '';
    $optionA = $_POST['opcion_a'] ?? '';
    $optionB = $_POST['opcion_b'] ?? '';
    $optionC = $_POST['opcion_c'] ?? '';
    $correctOpt = $_POST['resp_crrct'] ?? '';

    $response = modifyQuestion($exercise, $module, $question, $optionA, $optionB, $optionC, $correctOpt);
    echo json_encode($response);
    exit;
}
?>