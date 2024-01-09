<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$currentUserId = 1;

$answer = $db->query('SELECT A.AnswerText, Q.QuestionID, Qz.UserID, A.AnswerID
FROM Answers A
JOIN QuizQuestions QQ ON A.QuestionID = QQ.QuestionID
JOIN Questions Q ON A.QuestionID = Q.QuestionID
JOIN Quizzes Qz ON QQ.QuizID = Qz.QuizID
WHERE A.AnswerID = :answerid
', [
    'answerid' => $_POST['answerid']
])->findOrFail();

authorize($answer['UserID'] === $currentUserId);

$db->query('delete from Answers where AnswerID = :answerid', [
    'answerid' => $_POST['answerid']
]);
$redirect = "location: /questions?id=" . $_POST["quizid"] . "&questionid=" . $_POST["questionid"];
header($redirect);
exit();
