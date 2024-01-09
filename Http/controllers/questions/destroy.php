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
    'id' => $_POST['id'],
    'userid' => $currentUserId,
    'questionid' => $_POST['questionid']
])->findOrFail();

authorize($quiz['UserID'] === $currentUserId);

$db->query('delete from Questions where QuestionID = :id', [
    'id' => $_POST['questionid']
]);
$redirect = "location: /questions?id=" . $_POST["id"];
header($redirect);
exit();
