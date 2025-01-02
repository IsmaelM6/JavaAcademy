<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../services/module_service.php';

// Verificar si el usuario está autenticado y es del tipo correcto
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumno') {
    header('Location: ../auth/login.html');
    exit;
}

$userId = $_SESSION['user_id'];

// Crear una instancia del servicio de módulos
$moduleService = new ModuleService($pdo);

// Obtener módulos y progreso
$modules = $moduleService->getModules();
$progress = $moduleService->getProgress($userId);

// Crear una estructura para los módulos con progreso
$modulesUnique = $moduleService->getUniqueModulesWithProgress($modules, $progress);

// Calcular el progreso general del curso
$overallProgress = $moduleService->calculateOverallProgress($modulesUnique);

// Verificar si los módulos 1, 2 y 3 están al 100%
$modulesComplete = true;
foreach ($modulesUnique as $module) {
    if (in_array($module['moduloId'], [1, 2, 3]) && $module['progreso'] < 100) {
        $modulesComplete = false;
        break;
    }
}

if ($modulesComplete) {
    $overallProgress = 100;
}

// Verificar si es la primera vez que se carga la página
$showWelcomeMessage = !isset($_SESSION['welcome_message_shown']);
if ($showWelcomeMessage) {
    $_SESSION['welcome_message_shown'] = true;
}

// Obtener el perfil del usuario
$userName = htmlspecialchars($_SESSION['user_name']);
$userId = htmlspecialchars($_SESSION['user_id']);

// Obtener el correo y la fecha de creación del usuario desde la base de datos
$stmt = $pdo->prepare("SELECT email, fechaCreacion FROM alumnos WHERE alumnoId = ?");
$stmt->execute([$userId]);
$userData = $stmt->fetch();

$userEmail = $userData['email'] ?? 'No disponible';
$userCreationDate = $userData['fechaCreacion'] ?? 'No disponible';

// Verificar si el examen final ha sido completado
$finalExamCompleted = isset($_SESSION['final_exam_completed']) && $_SESSION['final_exam_completed'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Alumno - Java Academy</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="../../public/css/dashboard-style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/progressBar-style.css">
    <style>
        .progress-bar {
            width: 0%;
        }
        .btn-module {
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-module.active {
            background-color: #007bff; /* Color del botón activo */
        }
        .btn-module.inactive {
            background-color: #6c757d; /* Color del botón inactivo */
        }
        .btn-success-custom {
            background-color: #28a745; /* Color verde para el botón de certificado */
            border-color: #28a745;
            color: #fff;
            font-weight: bold;
            border-radius: 50px; /* Botón redondeado */
            padding: 15px 25px; /* Tamaño del botón */
            box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* Sombra del botón */
            transition: background-color 0.3s, transform 0.3s; /* Transición de color y tamaño */
        }
        .btn-success-custom:hover {
            background-color: #218838; /* Color más oscuro en hover */
            transform: scale(1.05); /* Efecto de aumento en hover */
        }
        .btn-warning-custom {
            background-color: #ffc107; /* Color amarillo para el botón de examen final */
            border-color: #ffc107;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <img src="../../public/images/logo.png" alt="Java Academy Logo">
        <div class="navbar-nav ml-auto">
            <span class="nav-item nav-link">Bienvenido, <?php echo $userName; ?></span>
            <a class="nav-item nav-link" href="logout.php">Cerrar sesión</a>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mi perfil</h5>
                        <p><strong>Nombre:</strong> <?php echo $userName; ?></p>
                        <p><strong>Java Academy ID:</strong> <?php echo $userId; ?></p>
                        <p><strong>Correo electrónico:</strong> <?php echo $userEmail; ?></p>
                        <p><strong>Fecha de creación:</strong> <?php echo $userCreationDate; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Curso: Programación orientada a objetos</h5>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $overallProgress; ?>%;" aria-valuenow="<?php echo $overallProgress; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $overallProgress; ?>%</div>
                        </div>
                        
                        <?php if ($finalExamCompleted): ?>
                            <br>
                            <a href="certificado.php" class="btn btn-success-custom mt-3">Ver Certificado</a>
                        <?php elseif ($overallProgress == 100 && !$finalExamCompleted): ?>
                            <br>
                            
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row mt-4">
                    <?php foreach ($modulesUnique as $index => $module) : ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Módulo <?php echo htmlspecialchars($module['moduloId']); ?></h6>
                                <p><?php echo htmlspecialchars($module['titulo']); ?></p>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo htmlspecialchars($module['progreso']); ?>%;" aria-valuenow="<?php echo htmlspecialchars($module['progreso']); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo htmlspecialchars($module['progreso']); ?>%</div>
                                </div>
                                <a href="module<?php echo $module['moduloId']; ?>.php" class="btn-module <?php echo $module['activo'] ? 'active' : 'inactive'; ?>">
                                    Ver módulo
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($modulesComplete && !$finalExamCompleted): ?>
                <div class="text-center mt-4">
                    <button id="final-exam-button" class="btn btn-warning-custom">Examen Final</button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
    // Mostrar mensaje de bienvenida con SweetAlert2 solo si es la primera vez
    <?php if ($showWelcomeMessage): ?>
    Swal.fire({
        title: '¡Bienvenido!',
        text: 'Has iniciado sesión correctamente.',
        icon: 'success',
        confirmButtonText: 'Entendido'
    });
    <?php endif; ?>

    // Mostrar alerta al presionar el botón de Examen Final
    document.getElementById('final-exam-button').addEventListener('click', function() {
        Swal.fire({
            title: 'Examen Final',
            text: 'Debe de tener 45 de 45 preguntas correctas para conseguir su certificado',
            icon: 'info',
            input: 'checkbox',
            inputPlaceholder: 'Sí, estoy seguro',
            showCancelButton: true,
            confirmButtonText: 'Iniciar Examen',
            inputValidator: (result) => {
                return !result && 'Debe confirmar para continuar';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Lógica para mostrar el examen final
                window.location.href = 'final_exam.php';
            }
        });
    });
    </script>
</body>
</html>
