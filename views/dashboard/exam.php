<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../services/questions_service.php';
require_once __DIR__ . '/../../services/module_service.php'; 

// Verificar si el usuario está autenticado y es del tipo correcto
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumno') {
    header('Location: ../auth/login.html');
    exit;
}

$userId = $_SESSION['user_id'];
$moduleId = 1; // Cambiar al Módulo 1

// Crear instancia del servicio de preguntas
$questionsService = new QuestionsService($pdo);
$moduleService = new ModuleService($pdo);

// Obtener preguntas aleatorias para el examen del módulo 1
$questions = $questionsService->getRandomQuestionsByModule($moduleId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comenzarExamen'])) {
        // Evaluar las respuestas del examen
        $correctAnswers = 0;

        foreach ($questions as $question) {
            $questionId = $question['ejercicioId'];
            // Obtener la respuesta del usuario para esta pregunta
            $userAnswer = isset($_POST["question_$questionId"]) ? $_POST["question_$questionId"] : '';
            
            // Obtener la respuesta correcta
            $correctAnswer = $question['resp_crrct'];
            
            // Comparar la respuesta del usuario con la correcta
            if ($userAnswer == $correctAnswer) {
                $correctAnswers++;
            }
        }

        // Determinar el progreso del usuario
        $totalQuestions = count($questions);
        $todasCorrectas = $correctAnswers === $totalQuestions;

        if ($todasCorrectas) {
            $nuevoProgreso = 100;
            $moduleService->updateProgress($userId, $moduleId, $nuevoProgreso);

            echo "<script>
                    alert('¡Felicitaciones! Has completado el módulo con un 100%.');
                    window.location.href = 'index.php'; 
                  </script>";
        } else {
            $nuevoProgreso = 75;
            $moduleService->updateProgress($userId, $moduleId, $nuevoProgreso);

            echo "<script>
                    alert('¡Intente nuevamente! No se completo 100% de las preguntas.');
                    window.location.href = 'index.php'; 
                  </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Módulo 1</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="../css/preg-style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Examen Módulo 1</h1>
        <div class="card shadow-lg">
            <div class="card-body">
                <form method="post">
                    <?php foreach ($questions as $question): ?>
                        <div class="mb-4 p-3 border rounded">
                            <p class="fw-bold"><?php echo htmlspecialchars($question['pregunta']); ?></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question_<?php echo $question['ejercicioId']; ?>" value="A" id="question_<?php echo $question['ejercicioId']; ?>_a">
                                <label class="form-check-label" for="question_<?php echo $question['ejercicioId']; ?>_a"><?php echo htmlspecialchars($question['opcion_a']); ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question_<?php echo $question['ejercicioId']; ?>" value="B" id="question_<?php echo $question['ejercicioId']; ?>_b">
                                <label class="form-check-label" for="question_<?php echo $question['ejercicioId']; ?>_b"><?php echo htmlspecialchars($question['opcion_b']); ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question_<?php echo $question['ejercicioId']; ?>" value="C" id="question_<?php echo $question['ejercicioId']; ?>_c">
                                <label class="form-check-label" for="question_<?php echo $question['ejercicioId']; ?>_c"><?php echo htmlspecialchars($question['opcion_c']); ?></label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="comenzarExamen" class="btn btn-primary btn-lg w-100">Enviar Respuestas</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
