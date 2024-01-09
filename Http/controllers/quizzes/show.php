<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$currentUserId = 1;

$quiz = $db->query('select * from quizzes where QuizID = :id', [
    'id' => $_GET['id']
])->findOrFail();

authorize($quiz['UserID'] === $currentUserId);

view("quizzes/show.view.php", [
    'heading' => 'Quiz',
    'quiz' => $quiz
]);
