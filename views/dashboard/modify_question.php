<?php
$ejercicioId = $_GET['ejercicioId'] ?? '';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../services/questions_service.php';

$getQuestionsService = new QuestionsService($pdo);
$getQuestions = [];
$getQuestions = $getQuestionsService->getQuestionById($ejercicioId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Pregunta - Java Academy</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../public/css/dashboard-style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <img src="../../public/images/logo.png" alt="Java Academy Logo">
        <div class="navbar-nav ml-auto">
            <span class="nav-item nav-link">Bienvenido,</span>
            <a class="nav-item nav-link" href="logout.php">Cerrar sesión</a>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Modificar pregunta</h5>
                        <form id="modQuestionForm" action="../../services/update_questions.php" method="post">
                            <?php foreach ($getQuestions as $question): ?>
                            <input type="hidden" name="ejercicioId" id="ejercicioId" value="<?php echo htmlspecialchars($question['ejercicioId']); ?>">
                            <div class="form-group">
                                <label for="moduloId">Modulo destino</label>
                                <select name="moduloId" id="moduloId" class="form-control" required>
                                    <option value="1" <?php echo $question['moduloId'] == 1 ? 'selected' : ''; ?>>Modulo 1</option>
                                    <option value="2" <?php echo $question['moduloId'] == 2 ? 'selected' : ''; ?>>Modulo 2</option>
                                    <option value="3" <?php echo $question['moduloId'] == 3 ? 'selected' : ''; ?>>Modulo 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pregunta">Ingrese la pregunta</label>
                                <input type="text" class="form-control" id="pregunta" name="pregunta" value="<?php echo htmlspecialchars($question['pregunta']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="opcion_a">Opcion A</label>
                                <input type="text" class="form-control" id="opcion_a" name="opcion_a" value="<?php echo htmlspecialchars($question['opcion_a']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="opcion_b">Opcion B</label>
                                <input type="text" class="form-control" id="opcion_b" name="opcion_b" value="<?php echo htmlspecialchars($question['opcion_b']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="opcion_c">Opcion C</label>
                                <input type="text" class="form-control" id="opcion_c" name="opcion_c" value="<?php echo htmlspecialchars($question['opcion_c']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="resp_crrct">Respuesta Correcta</label>
                                <select name="resp_crrct" id="resp_crrct" class="form-control" required>
                                    <option value="A" <?php echo $question['resp_crrct'] == 'A' ? 'selected' : ''; ?>>A</option>
                                    <option value="B" <?php echo $question['resp_crrct'] == 'B' ? 'selected' : ''; ?>>B</option>
                                    <option value="C" <?php echo $question['resp_crrct'] == 'C' ? 'selected' : ''; ?>>C</option>
                                </select>
                            </div>
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-primary">Modificar pregunta</button>
                            <a href="tutor_dashboard.php" class="btn btn-secondary mt-3">Volver</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#modQuestionForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Éxito!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then(() => {
                            window.location.href = 'tutor_dashboard.php';
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Hubo un problema al procesar la solicitud.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        });
    });
    </script>
</body>
</html>