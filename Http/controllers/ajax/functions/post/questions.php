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





// SQL query to retrieve quiz questions and answers


    $result = $db->query('SELECT
            qq.QuestionID,
            q.QuestionText,
            a.AnswerID,
            a.AnswerText,
            a.IsCorrect
          FROM QuizQuestions qq
          INNER JOIN Questions q ON qq.QuestionID = q.QuestionID
          LEFT JOIN Answers a ON q.QuestionID = a.QuestionID
          WHERE qq.QuizID = :quizid
          ORDER BY qq.QuestionID, a.AnswerID',[
        'quizid' => 1
    ]);

    $questionID_arr = [];
    while ($row = $result->fetch_assoc()) {
        $questionID = $row['QuestionID'];
        $answer = array(
            'AnswerID' => $row['AnswerID'],
            'AnswerText' => $row['AnswerText'],
            'IsCorrect' => $row['IsCorrect']
        );

        if (!isset($quizData[$questionID])) {
            $quizData[$questionID] = array(
                'QuestionText' => $row['QuestionText'],
                'Answers' => array()
            );
            $questionID_arr[] = $questionID;
        }

        $quizData[$questionID]['Answers'][] = $answer;

    }


    $db->commit();
}catch (PDOException $e) {
    // Roll back the transaction if any step fails
    $db->rollBack();
    dd($e);
}

if($_POST["mode"] === "view"){
    view("components/questions.view.php", [
        'heading' => 'My Questions',
        'questions' => $questions,
        'quizData' => $quizData,
    ]);
}

if($_POST["mode"] === "json"){

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["quizData" =>$quizData, "quizQuestionID" => $questionID_arr]);
}



