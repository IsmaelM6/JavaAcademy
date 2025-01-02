<?php
require_once __DIR__ . '/../../config/session_helper.php';
ensure_session_started();

require_once __DIR__ . '/../../services/auth_service.php';

$authService->logout();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cierre de Sesión - Java Academy</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
    Swal.fire({
        title: '¡Hasta luego!',
        text: 'Has cerrado sesión correctamente.',
        icon: 'success',
        confirmButtonText: 'Entendido'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../../index.html';
        }
    });
    </script>
</body>
</html>