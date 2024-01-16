<?php

use Core\App;
use Core\Database;

try {


    $db = App::resolve(Database::class);
    $db->beginTransaction();
//    $questions = $db->query('SELECT questions.QuestionID, QuestionText
//    FROM Questions
//    Join quizquestions on questions.QuestionID = quizquestions.QuestionID
//    WHERE quizquestions.QuizID = :quizid', [
//        'quizid' => 1
//    ])->get();

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

view("components/questions.view.php", [
    'heading' => 'My Questions',
    'questions' => $questions
]);



