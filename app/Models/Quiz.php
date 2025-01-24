<?php

namespace App\Models;

use App\Models\DB;

class Quiz extends DB
{
    public  function create(int $userId, string $title, string $description, int $timeLimit): bool
    {
        $query = "INSERT INTO quizzes (user_id, title, description, time_limit ,updated_at, created_at) 
                                VALUES (:userId, :title, :description, :timeLimit, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
                ':userId' => $userId,
                ':title' => $title,
                ':description' => $description,
                ':timeLimit' => $timeLimit,
            ]);

    }
    public function find(int $quizId){
        $query = "SELECT * FROM quizzes WHERE id = :quizId";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['quizId' => $quizId]);
        return $stmt->fetch();
    }
    public function findByUniquValue(string $uniqueValue){
        $query = "SELECT * FROM quizzes WHERE unique_value = :uniqueValue";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['uniqueValue' => $uniqueValue]);
        return $stmt->fetch();
    }

    public function getByUserId(int $userId): array|bool{
        $query = "SELECT * FROM quizzes WHERE user_id = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll();
    }
    public function update(int $quizId, string $title, string $description, int $timeLimit){
        $query = "UPDATE quizzes SET title = :title, description = :description, time_limit = :timeLimit WHERE id = :quizId";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':timeLimit' => $timeLimit,
            ':quizId' => $quizId,
        ]);
    }

    public function delete(int $quizId){
        $query = "DELETE FROM quizzes WHERE id = :quizId";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':quizId' => $quizId]);
    }

}