<?php
class StudentService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener estudiantes activos
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

    // Obtener estudiantes inactivos
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

    // Buscar estudiantes por nombre
   // services/student_service.php
public function searchStudentsByName($name) {
    $stmt = $this->pdo->prepare("
        SELECT a.alumnoId, a.nombreUsuario, a.email, a.estado, MAX(c.finSesion) AS ultimaConexion
        FROM alumnos a
        LEFT JOIN conexiones c ON a.alumnoId = c.alumnoId
        WHERE a.nombreUsuario LIKE :name
        GROUP BY a.alumnoId, a.nombreUsuario, a.email, a.estado
    ");
    $stmt->execute([':name' => "%$name%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Habilitar estudiante
    public function enableStudent($alumnoId) {
        $stmt = $this->pdo->prepare("UPDATE alumnos SET estado = 1 WHERE alumnoId = ?");
        return $stmt->execute([$alumnoId]);
    }

    // Deshabilitar estudiante
    public function disableStudent($alumnoId) {
        $stmt = $this->pdo->prepare("UPDATE alumnos SET estado = 0 WHERE alumnoId = ?");
        return $stmt->execute([$alumnoId]);
    }

    public function deleteStudent($alumnoId){
        $query = "DELETE FROM alumnos WHERE alumnoId = :alumnoId";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(['alumnoId' => $alumnoId]);
    }

    public function getAllStudents() {
        $stmt = $this->pdo->prepare("SELECT * FROM alumnos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentById($alumnoId){
        $query = "SELECT * FROM alumnos WHERE alumnoId = :alumnoId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['alumnoId' => $alumnoId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
