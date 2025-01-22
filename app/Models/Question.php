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
    public function deleteByQuizId(int $questionId):bool{
        $query = "DELETE FROM questions WHERE quiz_id = :questionId";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':questionId' => $questionId]);

    }

    public function getWithOptions (int $quizId){

        $stmt = $this->conn->prepare("SELECT * FROM questions WHERE quiz_id = :quizId");
        $stmt->execute([':quizId' => $quizId]);
        $questions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $questionsIds = array_column($questions, 'id');
        $placeholders = rtrim(str_repeat('?,', count($questionsIds) - 1), ',');

        $query = "SELECT * FROM options WHERE question_id IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($questionsIds);
        $options = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $groupedOptions = [];
        foreach ($options as $option){
            $groupedOptions[$option['question_id']][] = $option;

        foreach ($questions as $question){
            $question['options'] = $groupedOptions[$question['id']] ?? [];
        }
        }
        apiResponse($questions);
    }


}