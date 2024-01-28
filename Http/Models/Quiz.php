<?php

namespace Http\Models;
use Core\App;
use Core\Database;
use PDOException;

class Quiz
{
    public function getQuiz($id = 1)
    {
        $result = null;
        try {
            $db = App::resolve(Database::class);
            $result = $db->query('select * from quizzes where QuizID = :id', [
                'id' => $id
            ])->findOrFail();
        }catch (PDOException $e) {
            dd($e);
        }
        return $result;
    }

    public function checkAllAnswers($quizAnswers, $quizid){
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
                )->fetchAll();


                $correctAnswers[$questionID] = $answers;
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

    function getCorrectAnswerIDs($questionId){
        $result = null;
        try{
            $db = App::resolve(Database::class);
            $result = $db->query('SELECT
                                    q.QuestionID,
                                    q.QuestionText,
                                    GROUP_CONCAT(a.AnswerID ORDER BY a.AnswerID) AS AllAnswerIDs,
                                    GROUP_CONCAT(a.IsCorrect ORDER BY a.AnswerID) AS AllIsCorrect
                                FROM
                                    Questions q
                                JOIN
                                    Answers a ON q.QuestionID = a.QuestionID
                                GROUP BY
                                    q.QuestionID, q.QuestionText;
                                  
                                    ',['questionId' => $questionId])->get();

        }catch (PDOException $e) {
            dd($e);
        }
        return $result;

    }

    public function getCorrectAnswer($questionId){
        $result = null;
        try {
            $db = App::resolve(Database::class);

            $result = $db->query('SELECT * FROM Answers WHERE QuestionID = :questionID AND IsCorrect = 1', [
                'questionID' => $questionId
            ])->get();


        }catch (PDOException $e) {
            dd($e);
        }

        return $result;
    }
    public function getQuizInfoById($id)
    {
        $result = null;
        try {
            $db = App::resolve(Database::class);
            $result = $db->query('select * from quizzes where QuizID = :id', [
                'id' => $id
            ])->findOrFail();
        }catch (PDOException $e) {
            dd($e);
        }
        return $result;
    }

    function getCorrectAnswerID($questionId){
        $result = null;
        try{
            $db = App::resolve(Database::class);
            $result = $db->query('SELECT
                                    q.QuestionID,
                                    q.QuestionText,
                                    GROUP_CONCAT(a.AnswerID ORDER BY a.AnswerID) AS AllAnswerIDs
                                FROM
                                    Questions q
                                JOIN
                                    Answers a ON q.QuestionID = a.QuestionID
                                WHERE q.QuestionID = :questionId
                                GROUP BY
                                    q.QuestionID, q.QuestionText                                    
                                    ',['questionId' => $questionId])->get();

        }catch (PDOException $e) {
            dd($e);
        }
        return $result;

    }

}