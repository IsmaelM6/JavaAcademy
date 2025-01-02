<?php
$pdo = include 'database.php';

$token = isset($_GET['token']) ? $_GET['token'] : '';
$error = '';
$message = '';
$redirect = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y validar la entrada
    $new_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);

    // Verificar que las contraseñas coincidan
    if ($new_password !== $confirm_password) {
        $error = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
    } else {
        // Hashear la nueva contraseña
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $stmt = $pdo->prepare("UPDATE alumnos SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
        $stmt->execute([$hashed_password, $token]);

        if ($stmt->rowCount() > 0) {
            $message = "Tu contraseña ha sido actualizada exitosamente.";
            $redirect = "login.html";
        } else {
            $error = "Hubo un problema al actualizar tu contraseña. Por favor, inténtalo de nuevo.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../../public/css/reset-style.css">
</head>
<body>
    <div class="reset-container">
        <h2>Restablecer Contraseña</h2>
        
        <form method="post">
            <label for="password">Nueva Contraseña</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm_password">Confirmar Contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <button type="submit">Restablecer Contraseña</button>
        </form>
    </div>

    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        <?php if ($message): ?>
            Swal.fire({
                icon: 'success',
                title: '<?php echo htmlspecialchars($message); ?>',
                timer: 1100,
                timerProgressBar: true,
                didClose: () => {
                    window.location.href = '<?php echo $redirect; ?>';
                }
            });
        <?php elseif ($error): ?>
            Swal.fire({
                icon: 'error',
                title: '<?php echo htmlspecialchars($error); ?>',
                timer: 1100,
                timerProgressBar: true
            });
        <?php endif; ?>
    </script>
</body>
</html>
