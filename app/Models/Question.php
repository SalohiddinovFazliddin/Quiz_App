<?php

namespace App\Models;

use App\Models\DB;

class Question extends DB
{
    public function create(int $quiz_id, string $question_text):int
    {
        $query = "INSERT INTO questions (quiz_id,question_text, updated_at, created_at) 
                    VALUES (:quiz_id, :question_text, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ":quiz_id" => $quiz_id,
            ":question_text" => $question_text,

        ]);
        return $this->conn->lastInsertId();
    }


}