<?php

namespace Http\Models;
use Core\App;
use Core\Database;

class Quiz
{
    public function test(): void
    {
        echo "hello";
    }
    public function get()
    {
        $questions = null;
        try {


            $db = App::resolve(Database::class);
            $db->beginTransaction();

            $questions = $db->query('SELECT questions.QuestionID, QuestionText, AnswerID, AnswerText, IsCorrect
FROM Questions
         Join answers on answers.QuestionID = questions.QuestionID
WHERE answers.QuestionID in (SELECT questions.QuestionID
                             FROM Questions
                                      Join quizquestions on questions.QuestionID = quizquestions.QuestionID
                             WHERE quizquestions.QuizID = :quizid)', [
                'quizid' => 1
            ])->get();


            $db->commit();
        }catch (PDOException $e) {
            // Roll back the transaction if any step fails
            $db->rollBack();
            dd($e);
        }

        return $questions;
    }

}