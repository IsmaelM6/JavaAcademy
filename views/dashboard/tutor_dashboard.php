<?php
// views/dashboard/tutor_dashboard.php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../services/auth_service.php';
require_once __DIR__ . '/../../services/active_students_service.php';
require_once __DIR__ . '/../../services/progress_students_service.php';
require_once __DIR__ . '/../../services/questions_service.php';

// Verificar si el usuario está autenticado como tutor
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'tutor') {
    header('Location: ../auth/login.php');
    exit();
}

$username = $_SESSION['user_name'] ?? '@user';
$user_id = $_SESSION['user_id'] ?? 'N/A';

// Inicializar Servicios.
$progressStudentService = new ProgressStudentService($pdo);
$questionsService = new QuestionsService($pdo);
$activeStudentsService = new ActiveStudentsService($pdo);
$progressStudent = [];
$questions = [];
$successMessage = '';
$errorMessage = '';
$totalAlumnos = $activeStudentsService->getTotalActiveStudents();


// Determinar que datos mostrar basados en la accion del usuario.
$action = $_POST['action'] ?? '';

if ($action === 'manage') {
    $progressStudent = $progressStudentService->getProgressStudent();
    $studentsProgress = [];

    foreach ($progressStudent as $student) {
        $id = $student['alumnoId'];
        if (!isset($studentsProgress[$id])) {
            $studentsProgress[$id] = [
                'nombreUsuario' => $student['nombreUsuario'],
                'modulos' => [
                    1 => ['titulo' => 'Módulo 1', 'completado' => 0],
                    2 => ['titulo' => 'Módulo 2', 'completado' => 0],
                    3 => ['titulo' => 'Módulo 3', 'completado' => 0]
                ]
            ];
        }
        $studentsProgress[$id]['modulos'][$student['moduloId']] = [
            'titulo' => $student['titulo'],
            'completado' => $student['completado']
        ];
    }
}elseif ($action === "search"){
    $searchName = $_POST['search_name'];
    $progressStudent = $progressStudentService->searchProgressStudentByName($searchName);
    $studentsProgress = [];

    foreach ($progressStudent as $student){
        $id = $student['alumnoId'];
        if (!isset($studentsProgress[$id])){
            $studentsProgress[$id] = [
                'nombreUsuario' => $student['nombreUsuario'],
                'modulos' => [
                    1 => ['titulo' => 'Módulo 1', 'completado' => 0],
                    2 => ['titulo' => 'Módulo 2', 'completado' => 0],
                    3 => ['titulo' => 'Módulo 3', 'completado' => 0]
                ]
            ];
        }
        $studentsProgress[$id]['modulos'][$student['moduloId']] = [
            'titulo' => $student['titulo'],
            'completado' => $student['completado']
        ];
    }
} elseif ($action === 'questions'){
    $questions = $questionsService->getAllQuestions();
} elseif ($action === 'module1'){
    $questions = $questionsService->getFirstModuleQuestions();
} elseif ($action === 'module2'){
    $questions = $questionsService->getSecondModuleQuestions();
} elseif ($action === 'module3'){
    $questions = $questionsService->getThirdModuleQuestions();
} elseif ($action === 'delete_question') {
    $ejercicioId = $_POST['ejercicioId'] ?? '';
    if ($questionsService->deleteQuestion($ejercicioId)){
        $successMessage = 'Pregunta eliminada con exito.';
        $questions = $questionsService->getAllQuestions();
    } else {
        $errorMessage = 'Error al eliminar la pregunta.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Tutor - Java Academy</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../public/css/dashboard-style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <img src="../../public/images/logo.png" alt="Java Academy Logo">
        <div class="navbar-nav ml-auto">
            <span class="nav-item nav-link">Bienvenido, <?php echo htmlspecialchars($username); ?></span>
            <a class="nav-item nav-link" href="logout.php">Cerrar sesión</a>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mi perfil</h5>
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($username); ?></p>
                        <p><strong>Java Academy ID:</strong> <?php echo htmlspecialchars($user_id); ?></p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-tittle">Resumen de Actividades</h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Alumnos activos
                                <span class="badge badge-primary badge-pill"><?php echo  htmlspecialchars($totalAlumnos['alumnos_totales']); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Opciones del Tutor</h5>
                        <form method="post" action="">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <button type="submit" name="action" value="manage" class="btn btn-primary btn-block option-button" id="manage-students">
                                        <i class="bi bi-people-fill"></i> Ver Progeso Alumnos
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button type="submit" name="action" value="questions" class="btn btn-primary btn-block option-button" id=questions-manage>
                                        <i class="bi bi-question-circle-fill"></i> Gestionar preguntas
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Buscar Estudiantes</h5>
                        <form method="post" action="">
                            <input type="hidden" name="action" value="search">
                            <div class="form-group">
                                <input type="text" name="search_name" class="form-control" placeholder="Buscar por nombre" value="<?php echo htmlspecialchars($_POST['search_name'] ?? ''); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </form>
                    </div>
                </div>

                <?php if ($action === 'manage'): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Alumnos</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Progreso Módulo 1</th>
                                        <th>Progreso Módulo 2</th>
                                        <th>Progreso Módulo 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($studentsProgress as $id => $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($id); ?></td>
                                        <td><?php echo htmlspecialchars($student['nombreUsuario']); ?></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo htmlspecialchars($student['modulos'][1]['completado']); ?>%;" aria-valuenow="<?php echo htmlspecialchars($student['modulos'][1]['completado']); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo htmlspecialchars($student['modulos'][1]['completado']); ?>%</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo htmlspecialchars($student['modulos'][2]['completado']); ?>%;" aria-valuenow="<?php echo htmlspecialchars($student['modulos'][2]['completado']); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo htmlspecialchars($student['modulos'][2]['completado']); ?>%</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo htmlspecialchars($student['modulos'][3]['completado']); ?>%;" aria-valuenow="<?php echo htmlspecialchars($student['modulos'][3]['completado']); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo htmlspecialchars($student['modulos'][3]['completado']); ?>%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($action === 'search'): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Resultados de Búsqueda.</h5>
                        <div class="table-responsive">
                        <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Progreso Módulo 1</th>
                                        <th>Progreso Módulo 2</th>
                                        <th>Progreso Módulo 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($studentsProgress as $id => $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($id); ?></td>
                                        <td><?php echo htmlspecialchars($student['nombreUsuario']); ?></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo htmlspecialchars($student['modulos'][1]['completado']); ?>%;" aria-valuenow="<?php echo htmlspecialchars($student['modulos'][1]['completado']); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo htmlspecialchars($student['modulos'][1]['completado']); ?>%</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo htmlspecialchars($student['modulos'][2]['completado']); ?>%;" aria-valuenow="<?php echo htmlspecialchars($student['modulos'][2]['completado']); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo htmlspecialchars($student['modulos'][2]['completado']); ?>%</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo htmlspecialchars($student['modulos'][3]['completado']); ?>%;" aria-valuenow="<?php echo htmlspecialchars($student['modulos'][3]['completado']); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo htmlspecialchars($student['modulos'][3]['completado']); ?>%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif ?>

                <?php if ($action === 'questions' || $action === 'module1' || $action === 'module2' || $action === 'module3'): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-tittle">Preguntas de Modulos</h5>
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="add_question.html" class="btn btn-success btn-block option-button">
                                        <i class="bi bi-plus-circle"></i> Agregar pregunta
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button type="submit" name="action" value="module1" class="btn btn-info btn-block option-button" id="module1">
                                        <i class="bi bi-1-circle"></i> Módulo 1
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button type="submit" name="action" value="module2" class="btn btn-info btn-block option-button" id="module2">
                                        <i class="bi bi-2-circle"></i> Módulo 2
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button type="submit" name="action" value="module3" class="btn btn-info btn-block option-button" id="module3">
                                        <i class="bi bi-3-circle"></i> Módulo 3
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Módulo</th>
                                        <th>Pregunta</th>
                                        <th colspan="2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($questions as $question):?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($question['ejercicioId']); ?></td>
                                        <td><?php echo htmlspecialchars($question['moduloId']); ?></td>
                                        <td><?php echo htmlspecialchars($question['pregunta']); ?></td>
                                        <td>
                                            <a href="modify_question.php?ejercicioId=<?php echo htmlspecialchars($question['ejercicioId']); ?>" class="btn btn-warning btn-sm option-button">
                                            <i class="bi bi-pencil-square"></i> Modificar
                                            </a>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-sm delete-question" data-id="<?php echo htmlspecialchars($question['ejercicioId']); ?>">
                                                <i class="bi bi-trash-fill"></i> Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../../public/js/buscador.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('.delete-question').forEach(button => {
                button.addEventListener('click', function() {
                    const ejercicioId = this.getAttribute('data-id');

                    Swal.fire({
                        tittle: 'Confirmación',
                        text: '¿Estás seguro que deseas eliminar esta pregunta?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed){
                            const form = document.createElement('form');
                            form.method = 'post';
                            form.action = '';

                            const inputAction = document.createElement('input');
                            inputAction.type = 'hidden';
                            inputAction.name = 'action';
                            inputAction.value = 'delete_question';
                            form.appendChild(inputAction);

                            const inputId = document.createElement('input');
                            inputId.type = 'hidden';
                            inputId.name = 'ejercicioId';
                            inputId.value = ejercicioId;
                            form.appendChild(inputId);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    }); 
                });
            });
        });
    </script>
</body>
</html>


