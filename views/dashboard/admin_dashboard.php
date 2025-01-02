<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../services/student_service.php';
require_once __DIR__ . '/../../services/tutor_service.php';

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'administrador') {
    header('Location: ../auth/login.php');
    exit();
}

$username = $_SESSION['user_name'] ?? '@user';
$user_id = $_SESSION['user_id'] ?? 'N/A';

// Inicializar servicios
$studentService = new StudentService($pdo);
$tutorService = new TutorService($pdo);

// Inicializar variables
$activeStudents = [];
$inactiveStudents = [];
$filteredStudents = [];
$tutors = [];
$students = [];
$successMessage = '';
$errorMessage = '';

// Determinar qué datos mostrar basados en la acción del usuario
$action = $_POST['action'] ?? '';

if ($action === 'view_active') {
    $activeStudents = $studentService->getActiveStudents();
} elseif ($action === 'view_inactive') {
    $inactiveStudents = $studentService->getInactiveStudents();
} elseif ($action === 'search') {
    $searchName = $_POST['search_name'] ?? '';
    $filteredStudents = $studentService->searchStudentsByName($searchName);
} elseif ($action === 'enable' || $action === 'disable') {
    $alumnoId = $_POST['alumno_id'] ?? '';
    if ($action === 'enable') {
        if ($studentService->enableStudent($alumnoId)) {
            $successMessage = 'Alumno habilitado con éxito.';
        } else {
            $errorMessage = 'Error al habilitar al alumno.';
        }
    } elseif ($action === 'disable') {
        if ($studentService->disableStudent($alumnoId)) {
            $successMessage = 'Alumno deshabilitado con éxito.';
        } else {
            $errorMessage = 'Error al deshabilitar al alumno.';
        }
    }
    // Volver a cargar los resultados de búsqueda después de la acción
    $searchName = $_POST['search_name'] ?? '';
    $filteredStudents = $studentService->searchStudentsByName($searchName);
} elseif ($action === 'view_all_tutors') {
    $tutors = $tutorService->getAllTutors();
} elseif ($action === 'delete_tutor') {
    $tutorId = $_POST['tutor_id'] ?? '';
    if ($tutorService->deleteTutor($tutorId)) {
        $successMessage = 'Tutor eliminado con éxito.';
        $tutors = $tutorService->getAllTutors(); // Recargar la lista de tutores después de eliminar
    } else {
        $errorMessage = 'Error al eliminar el tutor.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Java Academy</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../public/css/dashboard-style.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Opciones de Administrador</h5>
                        <form method="post" action="">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="add_tutor.html" class="btn btn-success btn-block">
                                        <i class="bi bi-person-plus"></i> Nuevo Tutor
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button type="submit" name="action" value="view_active" class="btn btn-primary btn-block">
                                        <i class="bi bi-eye"></i> Alumno Activo
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button type="submit" name="action" value="view_inactive" class="btn btn-danger btn-block">
                                        <i class="bi bi-eye-slash"></i> Alumno Inactivo
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button type="submit" name="action" value="search" class="btn btn-primary btn-block">
                                        <i class="bi bi-search"></i> Alumnos
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button type="submit" name="action" value="view_all_tutors" class="btn btn-info btn-block">
                                        <i class="bi bi-people"></i> Mostrar Tutores
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
                        <?php if ($successMessage): ?>
                            <div class="alert alert-success mt-3"><?php echo htmlspecialchars($successMessage); ?></div>
                        <?php endif; ?>
                        <?php if ($errorMessage): ?>
                            <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($errorMessage); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($action === 'view_active'): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Alumnos Activos</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Última Conexión</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($activeStudents as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['alumnoId']); ?></td>
                                        <td><?php echo htmlspecialchars($student['nombreUsuario']); ?></td>
                                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                                        <td><?php echo htmlspecialchars($student['ultimaConexion'] ? (new DateTime($student['ultimaConexion']))->format('d/m/Y H:i:s') : 'Nunca'); ?></td>
                                        <td>
                                            <button class="btn btn-info btn-sm delete-student" data-id="<?php echo htmlspecialchars($student['alumnoId']); ?>" data-action="disable">
                                                Deshabilitar
                                            </button>
                                        </td>
                                        <td>
                                            <a href="modify_students.php?alumnoId=<?php echo htmlspecialchars($student['alumnoId']); ?>" class="btn btn-warning btn-sm option-button">
                                                <i class="bi bi-pencil-square"></i> Modificar
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($action === 'view_inactive'): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Alumnos Inactivos</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Última Conexión</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inactiveStudents as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['alumnoId']); ?></td>
                                        <td><?php echo htmlspecialchars($student['nombreUsuario']); ?></td>
                                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                                        <td><?php echo htmlspecialchars($student['ultimaConexion'] ? (new DateTime($student['ultimaConexion']))->format('d/m/Y H:i:s') : 'Nunca'); ?></td>
                                        <td>
                                            <button class="btn btn-success btn-sm enable-student" data-id="<?php echo htmlspecialchars($student['alumnoId']); ?>" data-action="enable">
                                                Habilitar
                                            </button>
                                        </td>
                                        <td>
                                            <a href="modify_students.php?alumnoId=<?php echo htmlspecialchars($student['alumnoId']); ?>" class="btn btn-warning btn-sm option-button">
                                                <i class="bi bi-pencil-square"></i> Modificar
                                            </a>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-sm delete-students" data-id="<?php echo htmlspecialchars($student['alumnoId']); ?>">
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
                <?php endif; ?>

                <?php if ($action === 'search'): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Resultados de Búsqueda</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre Usuario</th>
                                        <th>Email</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($filteredStudents): ?>
                                        <?php foreach ($filteredStudents as $student): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($student['alumnoId']); ?></td>
                                            <td><?php echo htmlspecialchars($student['nombreUsuario']); ?></td>
                                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                                            <td><?php echo $student['estado'] ? 'Activo' : 'Inactivo'; ?></td>
                                            <td>
                                                <button class="btn <?php echo $student['estado'] ? 'btn-info' : 'btn-success'; ?> btn-sm toggle-student" data-id="<?php echo htmlspecialchars($student['alumnoId']); ?>" data-action="<?php echo $student['estado'] ? 'disable' : 'enable'; ?>">
                                                    <?php echo $student['estado'] ? 'Deshabilitar' : 'Habilitar'; ?>
                                                </button>
                                            </td>
                                            <td>
                                                <a href="modify_students.php?alumnoId=<?php echo htmlspecialchars($student['alumnoId']); ?>" class="btn btn-warning btn-sm option-button">
                                                    <i class="bi bi-pencil-square"></i> Modificar
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="5">No se encontraron resultados.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($action === 'view_all_tutors'): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Lista de Tutores</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Fecha de Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($tutors): ?>
                                        <?php foreach ($tutors as $tutor): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($tutor['tutorId']); ?></td>
                                            <td><?php echo htmlspecialchars($tutor['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($tutor['email']); ?></td>
                                            <td><?php echo htmlspecialchars((new DateTime($tutor['fechaCreacion']))->format('d/m/Y H:i:s')); ?></td>
                                            <td>
                                                <a href="modify_tutor.php?tutorId=<?php echo htmlspecialchars($tutor['tutorId']); ?>" class="btn btn-warning btn-sm option-button">
                                                <i class="bi bi-pencil-square"></i> Modificar
                                                </a>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-sm delete-tutor" data-id="<?php echo htmlspecialchars($tutor['tutorId']); ?>">
                                                    <i class="bi bi-trash-fill"></i> Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="5">No se encontraron tutores.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- Script de confirmación -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirmación para alumnos
            document.querySelectorAll('.delete-student,.enable-student, .toggle-student').forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-id');
                    const action = this.getAttribute('data-action');
                    const actionText = action === 'enable' ? 'habilitar' : 'deshabilitar';
                    
                    Swal.fire({
                        title: 'Confirmación',
                        text: `¿Estás seguro de que deseas ${actionText} a este alumno?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, proceder',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'post';
                            form.action = '';
                            
                            const inputAction = document.createElement('input');
                            inputAction.type = 'hidden';
                            inputAction.name = 'action';
                            inputAction.value = action;
                            form.appendChild(inputAction);
                            
                            const inputId = document.createElement('input');
                            inputId.type = 'hidden';
                            inputId.name = 'alumno_id';
                            inputId.value = studentId;
                            form.appendChild(inputId);
                            
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });

            // Confirmación para tutores
            document.querySelectorAll('.delete-tutor').forEach(button => {
                button.addEventListener('click', function() {
                    const tutorId = this.getAttribute('data-id');
                    
                    Swal.fire({
                        title: 'Confirmación',
                        text: '¿Estás seguro de que deseas eliminar a este tutor?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'post';
                            form.action = '';
                            
                            const inputAction = document.createElement('input');
                            inputAction.type = 'hidden';
                            inputAction.name = 'action';
                            inputAction.value = 'delete_tutor';
                            form.appendChild(inputAction);
                            
                            const inputId = document.createElement('input');
                            inputId.type = 'hidden';
                            inputId.name = 'tutor_id';
                            inputId.value = tutorId;
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
