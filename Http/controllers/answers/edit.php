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
    'answerid' => $_GET['answerid']
])->findOrFail();

authorize($answer['UserID'] === $currentUserId);

view("answers/edit.view.php", [
    'heading' => 'Edit Question Answer',
    'errors' => [],
    'answer' => $answer
]);