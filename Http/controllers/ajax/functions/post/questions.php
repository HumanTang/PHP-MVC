<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$questions = $db->query('SELECT Q.QuestionID, Q.QuestionText, A.AnswerText, Q.QuestionID, A.AnswerID, A.IsCorrect
FROM Questions Q, Answers A
JOIN QuizQuestions QQ ON Q.QuestionID = QQ.QuestionID
JOIN QuizQuestions QQ ON A.QuestionID = QQ.QuestionID
WHERE QQ.QuizID = :id;
',[
    'id' => 1
])->get();


view("components/answers.view.php", [
    'heading' => 'My Questions',
    'questions' => $questions
]);