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

// Crear instancia del servicio de preguntas y módulos
$questionsService = new QuestionsService($pdo);
$moduleService = new ModuleService($pdo);

// Obtener 15 preguntas aleatorias de cada módulo
$questions = [];
for ($moduleId = 1; $moduleId <= 3; $moduleId++) {
    $moduleQuestions = $questionsService->getRandomQuestionsByModule($moduleId, 15);
    $questions = array_merge($questions, $moduleQuestions);
}

// Mezclar todas las preguntas
shuffle($questions);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['enviarExamen'])) {
        // Evaluar las respuestas del examen
        $correctAnswers = 0;

        foreach ($questions as $question) {
            $questionId = $question['ejercicioId'];
            $userAnswer = isset($_POST["question_$questionId"]) ? $_POST["question_$questionId"] : '';
            $correctAnswer = $question['resp_crrct'];

            if ($userAnswer === $correctAnswer) {
                $correctAnswers++;
            }
        }

        $totalQuestions = count($questions);
        $todasCorrectas = $correctAnswers === $totalQuestions;

        if ($todasCorrectas) {
            // Actualizar el progreso de todos los módulos a 100%
            for ($moduleId = 1; $moduleId <= 3; $moduleId++) {
                $moduleService->updateProgress($userId, $moduleId, 100);
            }

            // Marcar el examen final como completado en la sesión
            $_SESSION['final_exam_completed'] = true;

            echo "<script>
                    alert('¡Felicitaciones! Has aprobado el examen final con un 100%.');
                    window.location.href = 'index.php'; 
                  </script>";
        } else {
            $_SESSION['final_exam_completed'] = true;
            echo "<script>
                   alert('¡Felicitaciones! Has aprobado el examen final con un 100%.');
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
    <title>Examen Final</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="../css/preg-style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Examen Final</h1>
        <div class="card shadow-lg border-primary">
            <div class="card-body">
                <form method="post">
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="mb-4 p-3 border rounded bg-light">
                            <p class="fw-bold"><?php echo ($index + 1) . ". " . htmlspecialchars($question['pregunta']); ?></p>
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
                    <button type="submit" name="enviarExamen" class="btn btn-primary btn-lg w-100">Enviar Respuestas</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
