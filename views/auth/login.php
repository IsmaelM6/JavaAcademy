<?php
require_once __DIR__ . '/../../config/session_helper.php';
ensure_session_started();

require_once __DIR__ . '/../../services/auth_service.php';

// Inicializamos $error como vacío para evitar errores de variable no definida
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Llamamos al método de inicio de sesión
    $loginResult = $authService->login($email, $password);

    if ($loginResult['success']) {
        // Redirigir según el tipo de usuario
        switch ($_SESSION['user_type']) {
            case 'administrador':
                header('Location: ../dashboard/admin_dashboard.php');
                break;
            case 'alumno':
                header('Location: ../dashboard/index.php');
                break;
            case 'tutor':
                header('Location: ../dashboard/tutor_dashboard.php');
                break;
            default:
                header('Location: ../dashboard/index.php');
        }
        exit;
    } else {
        // Manejar el error de inicio de sesión
        $error = $loginResult['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../../public/css/login-style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php if (!empty($error)): ?>
        <script>
            Swal.fire({
                title: 'Error',
                text: '<?php echo $error; ?>',
                icon: 'error',
                confirmButtonText: 'Intentar de nuevo'
            });
        </script>
    <?php endif; ?>

    <div class="login-container">
        <div class="login-box">
            <div class="login-left">
                <img src="../../public/images/logov2.png" alt="Logo" class="login-image">
                <em>"Empoderando a la próxima generación de desarrolladores con conocimientos profundos en programación orientada a objetos en Java, a través de una educación innovadora y accesible".</em>
            </div>
            
            <div class="login-right">
                <button class="back-button" onclick="window.location.href='../../index.html'">Volver</button>
                <div class="login-form">
                    <h2>¡Bienvenido!</h2>
                    <p>Inicie sesión en su cuenta</p>
                    <form action="login.php" method="post">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" placeholder="Ingrese su correo" required>
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                        <a href="#" class="forgot-password">¿Olvidó su contraseña?</a>
                        <button type="submit" class="login-button">Iniciar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
