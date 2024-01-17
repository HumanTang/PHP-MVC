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

    public function checkAnswer($quizAnswers, $quizid){
        // Fetch correct answers from the database
        $correctAnswers = array();
        $questions = null;
        $score = 0;
        try {
            $db = App::resolve(Database::class);
            $db->beginTransaction();
        foreach ($quizAnswers['questions'] as $questionID => $userAnswer) {
            $answers = $db->query('SELECT AnswerID FROM Answers WHERE QuestionID = :questionID AND IsCorrect = 1',
                ['questionID' => $questionID]
            );


            $correctAnswers[$questionID] = $answers->fetchAll();
        }
            $db->commit();
        }catch (PDOException $e) {
            // Roll back the transaction if any step fails
            $db->rollBack();
            dd($e);
        }

        // Compare user answers with correct answers and calculate the score

        foreach ($quizAnswers['questions'] as $questionID => $userAnswer) {
            if (isset($correctAnswers[$questionID]) && array_diff($userAnswer['answers'], $correctAnswers[$questionID]) === array_diff($correctAnswers[$questionID], $userAnswer['answers'])) {
                $score++;
            }
        }

    }

    public function getCorrectAnswer($questionid){
        //"SELECT AnswerID FROM Answers WHERE QuestionID = :questionID AND IsCorrect = 1"
        $questions = null;
        try {
            $db = App::resolve(Database::class);
            $db->beginTransaction();

            $questions = $db->query('SELECT AnswerID FROM Answers WHERE QuestionID = :questionID AND IsCorrect = 1', [
                'questionID' => $questionid
            ])->get();

            $db->commit();
        }catch (PDOException $e) {
            // Roll back the transaction if any step fails
            $db->rollBack();
            dd($e);
        }

        return $questions;
    }
    public function get()
    {
        $questions = null;
        try {
            $db = App::resolve(Database::class);
            $db->beginTransaction();

            $questions = $db->query('SELECT questions.QuestionID, questions.QuestionText, AnswerID, AnswerText, IsCorrect
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