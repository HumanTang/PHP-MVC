<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$currentUserId = 1;

$quiz = $db->query('select * from quizzes where QuizID = :id', [
    'id' => $_POST['id']
])->findOrFail();

authorize($quiz['UserID'] === $currentUserId);

$db->query('delete from quizzes where QuizID = :id', [
    'id' => $_POST['id']
]);

header('location: /quizzes');
exit();
