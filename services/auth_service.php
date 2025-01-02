<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/conexion_service.php';

class AuthService {
    private $pdo;
    private $conexionService;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        // Inicializa ConexionService dentro del constructor de AuthService
        $this->conexionService = new ConexionService($pdo);
    }

    public function login($email, $password) {
        // Intenta autenticar como alumno
        $stmt = $this->pdo->prepare("SELECT alumnoId, nombreUsuario, estado, password FROM alumnos WHERE email = ?");
        $stmt->execute([$email]);
        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $user['password'])) {
                if ($user['estado'] == 1) {
                    $this->setSessionData($user['alumnoId'], $user['nombreUsuario'], 'alumno');
                    // Registrar inicio de sesión para alumnos
                    $this->conexionService->registrarInicioSesion($user['alumnoId']);
                    return ['success' => true];
                } else {
                    // Si el estado es 0, la cuenta está suspendida
                    return ['success' => false, 'message' => 'Cuenta suspendida. Comuníquese con el administrador.'];
                }
            } else {
                // Contraseña incorrecta
                return ['success' => false, 'message' => 'Credenciales incorrectas.'];
            }
        }

        // Si no es alumno, intenta autenticar como administrador
        $stmt = $this->pdo->prepare("SELECT adminId, nombre, nombre2, password FROM administradores WHERE nombre = ?");
        $stmt->execute([$email]);
        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $user['password'])) {
                $this->setSessionData($user['adminId'], $user['nombre2'], 'administrador');
                return ['success' => true];
            } else {
                // Contraseña incorrecta
                return ['success' => false, 'message' => 'Credenciales incorrectas.'];
            }
        }

        // Intenta autenticar como tutor
        $stmt = $this->pdo->prepare("SELECT tutorId, nombreTutor, password FROM tutores WHERE email = ?");
        $stmt->execute([$email]);
        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $user['password'])) {
                $this->setSessionData($user['tutorId'], $user['nombreTutor'], 'tutor');
                return ['success' => true];
            } else {
                // Contraseña incorrecta
                return ['success' => false, 'message' => 'Credenciales incorrectas.'];
            }
        }

        // Si no se autenticó, retorna false
        return ['success' => false, 'message' => 'Credenciales incorrectas.'];
    }

    private function setSessionData($id, $name, $type) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_type'] = $type;
    }

    public function logout() {
        if (session_status() == PHP_SESSION_ACTIVE) {
            // Si el usuario es un alumno, registrar fin de sesión
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'alumno') {
                $this->conexionService->registrarFinSesion($_SESSION['user_id']);
            }

            // Limpia todas las variables de sesión
            $_SESSION = array();

            // Si se está usando un cookie de sesión, destruye la cookie
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // Finalmente, destruye la sesión
            session_destroy();
        }
    }
}

// Inicializa el servicio de autenticación
$authService = new AuthService($pdo);
?>
