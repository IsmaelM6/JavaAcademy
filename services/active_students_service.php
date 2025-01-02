<?php
require_once __DIR__ . '/../config/database.php';
//active_students_service.php
class ActiveStudentsService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getActiveStudents() {
    $stmt = $this->pdo->prepare("
        SELECT a.alumnoId, a.nombreUsuario, a.email, MAX(c.finSesion) AS ultimaConexion
        FROM alumnos a
        LEFT JOIN conexiones c ON a.alumnoId = c.alumnoId
        WHERE a.estado = 1
        GROUP BY a.alumnoId, a.nombreUsuario, a.email
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalActiveStudents(){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS alumnos_totales FROM alumnos WHERE estado = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
