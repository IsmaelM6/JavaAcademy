<?php
$tutorId = $_GET['tutorId'] ?? '';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../services/tutor_service.php';

$getTutorService = new TutorService($pdo);
$getTutors = [];
$getTutors = $getTutorService->getTutorById($tutorId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Tutor - Java Academy</title>
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
                        <h5 class="card-title">Modificar Tutor</h5>
                        <form id="modTutorForm" action="../../services/update_tutors.php" method="post">
                            <?php foreach ($getTutors as $tutor): ?>
                            <input type="hidden" name="tutorId" id="tutorId" value="<?php echo htmlspecialchars($tutor['tutorId']); ?>">
                            <div class="form-group">
                                <label for="nombreTutor">Nombre del tutor</label>
                                <input type="text" class="form-control" id="nombreTutor" name="nombreTutor" value="<?php echo htmlspecialchars($tutor['nombreTutor']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($tutor['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($tutor['password']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Reingresa la contraseña</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" value="<?php echo htmlspecialchars($tutor['password']); ?>" required>
                            </div>
                            <input type="hidden" name="fechaCreacion" id="fechaCreacion" value="<?php echo htmlspecialchars($tutor['fechaCreacion']); ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-primary">Modificar Tutor</button>
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
        $('#modTutorForm').on('submit', function(e) {
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
