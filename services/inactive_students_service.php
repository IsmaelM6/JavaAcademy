<?php
require_once __DIR__ . '/../config/database.php';
//inactive_students_service.php
class InactiveStudentsService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

   public function getInactiveStudents() {
    $stmt = $this->pdo->prepare("
        SELECT a.alumnoId, a.nombreUsuario, a.email, MAX(c.finSesion) AS ultimaConexion
        FROM alumnos a
        LEFT JOIN conexiones c ON a.alumnoId = c.alumnoId
        WHERE a.estado = 0
        GROUP BY a.alumnoId, a.nombreUsuario, a.email
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>
