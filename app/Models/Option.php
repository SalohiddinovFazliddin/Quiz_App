<?php

namespace App\Models;

use App\Models\DB;

class Option extends DB
{
    public function create(int $question_id, string $question_text,bool $is_correct):int {
        $query = "INSERT INTO options (question_id,option_text, is_correct,updated_at, created_at) 
                    VALUES (:question_id, :option_text,:is_correct, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ":question_id" => $question_id,
            ":option_text" => $question_text,
            ":is_correct" => $is_correct ?1:0 ,

        ]);
        return $this->conn->lastInsertId();
    }


}