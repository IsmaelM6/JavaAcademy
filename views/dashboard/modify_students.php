<?php
$alumnoId = $_GET['alumnoId'] ?? '';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../services/student_service.php';

$getStudentService = new StudentService($pdo);
$getStudents = [];
$getStudents = $getStudentService->getStudentById($alumnoId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Alumno - Java Academy</title>
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
                        <h5 class="card-title">Modificar Alumno</h5>
                        <form id="modStudentForm" action="../../services/update_students.php" method="post">
                            <?php foreach ($getStudents as $student): ?>
                            <input type="hidden" name="alumnoId" id="alumnoId" value="<?php echo htmlspecialchars($student['alumnoId']); ?>">
                            <div class="form-group">
                                <label for="nombreUsuario">Nombre del alumno</label>
                                <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" value="<?php echo htmlspecialchars($student['nombreUsuario']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($student['password']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Reingresa la contraseña</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" value="<?php echo htmlspecialchars($student['password']); ?>" required>
                            </div>
                            <input type="hidden" name="estado" id="estado" value="<?php echo htmlspecialchars($student['estado']); ?>">
                            <input type="hidden" name="fechaCreacion" id="fechaCreacion" value="<?php echo htmlspecialchars($student['fechaCreacion']); ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-primary">Modificar Alumno</button>
                            <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Volver</a> <!-- Botón Volver agregado -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#modStudentForm').on('submit', function(e) {
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
                            window.location.href = 'admin_dashboard.php'; // Redirigir al dashboard
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