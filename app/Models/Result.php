<?php

namespace App\Models;

use App\Models\DB;
use Dotenv\Parser\Value;

class Result extends DB
{
    public function find(int $id){
        $query = "SELECT * FROM `results` WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    public function create(int $userId, int $quizId, int $limit)
    {
        $query = "INSERT INTO results (user_id, quiz_id, started_at, finished_at) VALUES (:userId, :quizId, NOW(), :finishedAt)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'userId' =>$userId,
            'quizId' =>$quizId,
            'finishedAt' => date('Y-m-d H:i:s', strtotime("+{$limit} minutes")),
        ]);
        $resultId = $this->conn->lastInsertId();
        return $this->find($resultId);
    }

    public function getUserResult(int $userId, int $quizId)
    {
        $query = "SELECT * FROM results WHERE user_id = :userId AND quiz_id = :quizId";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['userId' => $userId, 'quizId' => $quizId]);
        return $stmt->fetch();
    }

}