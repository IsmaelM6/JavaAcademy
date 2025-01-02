<?php
// services/tutor_service.php
class TutorService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllTutors() {
        $stmt = $this->pdo->prepare("SELECT tutorId, nombreTutor AS nombre, email, fechaCreacion FROM tutores");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteTutor($tutorId) {
        $query = "DELETE FROM tutores WHERE tutorId = :tutorId";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(['tutorId' => $tutorId]);
    }

    public function getTutorById($tutorId){
        $query = "SELECT * FROM tutores WHERE tutorId = :tutorId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['tutorId' => $tutorId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
