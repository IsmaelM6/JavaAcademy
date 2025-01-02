<?php
class QuestionsService {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAllQuestions(){
        $stmt = $this->pdo->prepare("SELECT * FROM ejercicios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteQuestion($ejercicioId){
        $query = "DELETE FROM ejercicios WHERE ejercicioId = :ejercicioId";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(['ejercicioId' => $ejercicioId]);
    }

    public function getFirstModuleQuestions(){
        $stmt = $this->pdo->prepare("SELECT * FROM ejercicios WHERE moduloId = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSecondModuleQuestions(){
        $stmt = $this->pdo->prepare("SELECT * FROM ejercicios WHERE moduloId = 2");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getThirdModuleQuestions(){
        $stmt = $this->pdo->prepare("SELECT * FROM ejercicios WHERE moduloId = 3");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuestionById($ejercicioId){
        $query = "SELECT * FROM ejercicios WHERE ejercicioId = :ejercicioId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['ejercicioId' => $ejercicioId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // Nuevo método para obtener preguntas aleatorias por módulo
    public function getRandomQuestionsByModule($moduloId, $limit = 20){
        $query = "SELECT * FROM ejercicios WHERE moduloId = :moduloId ORDER BY RAND() LIMIT :limit";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':moduloId', $moduloId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
