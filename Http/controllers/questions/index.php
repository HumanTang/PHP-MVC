<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$questions = $db->query('SELECT Q.QuestionID, Q.QuestionText
FROM Questions Q
JOIN QuizQuestions QQ ON Q.QuestionID = QQ.QuestionID
WHERE QQ.QuizID = :id;
',[
    'id' => $_GET['id']
])->get();


view("questions/index.view.php", [
    'heading' => 'My Questions',
    'questions' => $questions
]);