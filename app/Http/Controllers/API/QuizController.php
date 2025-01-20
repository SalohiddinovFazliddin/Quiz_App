<?php

namespace App\Http\Controllers\API;



use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Traits\Validator;
use Src\Auth;

class QuizController
{
    use Validator;

    public function index() {
        $quizzes = (new Quiz())->getByUserId(Auth::user()->id);
        apiResponse(['quizzes' => $quizzes]);
    }



    public function store(): void
    {
        $quizItems=$this->validate([
            'title' => 'string',
            'description' => 'string',
            'timeLimit' => 'int',
            'questions' => 'array'
        ]);
        $quiz = new Quiz();
        $question =new Question();
        $options = new Option();

        $quiz_id=$quiz->create(Auth::user()->id,
            $quizItems['title'],
            $quizItems['description'],
            $quizItems['timeLimit']
        );
        $questions=$quizItems['questions'];

        foreach ($questions as $questionItem){
            $question_id=$question->create($quiz_id, $questionItem['quiz']);
            $correct=$questionItem['correct'];
            foreach ($questionItem['options'] as $key=>$optionItem){
                $options->create($question_id, $optionItem, $correct==$key);
            }
        }
        apiResponse(['message' =>'Quiz created successfully',],201);


    }
    public function update(int $quizId){
        $quizItems=$this->validate([
            'title' =>'string',
            'description' => 'string',
            'timeLimit' => 'int',
            'questions' => 'array'
        ]);
        $quiz = new Quiz();
        $question =new Question();
        $options = new Option();
        // update quiz
        $quiz->update($quizId,
            $quizItems['title'],
            $quizItems['description'],
            $quizItems['timeLimit']
        );
        // destroy all questions and options
        $question->deleteByQuizId($quizId);

        $questions=$quizItems['questions'];

        foreach ($questions as $questionItem){
            $question_id=$question->create($quizId, $questionItem['quiz']);
            $correct=$questionItem['correct'];
            foreach ($questionItem['options'] as $key=>$optionItem){
                $options->create($question_id, $optionItem, $correct==$key);
            }
        }
        apiResponse(['message' => 'Quiz updated successfully',],200);
    }
    public function destroy(int $quizId){
        $quiz = new Quiz();
        $quiz->delete($quizId);
        apiResponse(['message' => 'Quiz deleted successfully',],200);
    }
}
