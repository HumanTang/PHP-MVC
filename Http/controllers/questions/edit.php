<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$currentUserId = 1;

$quiz = $db->query('SELECT Q.QuestionID, Q.QuestionText, Qz.UserID, QQ.QuizID, Qz.QuizTitle
FROM Questions Q
         JOIN QuizQuestions QQ ON Q.QuestionID = QQ.QuestionID
         JOIN Quizzes Qz ON QQ.QuizID = Qz.QuizID
WHERE Q.QuestionID = :questionid AND Qz.QuizID = :id AND Qz.UserID = :userid;', [
    'id' => $_GET['id'],
    'userid' => $currentUserId,
    'questionid' => $_GET['questionid']
])->findOrFail();

authorize($quiz['UserID'] === $currentUserId);

view("questions/edit.view.php", [
    'heading' => 'Edit Quiz Question',
    'errors' => [],
    'quiz' => $quiz
]);