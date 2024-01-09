<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$currentUserId = 1;

// find the corresponding note
$quiz = $db->query('select * from quizzes where QuizID = :id', [
    'id' => $_POST['id']
])->findOrFail();

// authorize that the current user can edit the note
authorize($quiz['UserID'] === $currentUserId);

// validate the form
$errors = [];

if (! Validator::string($_POST['title'], 1, 10)) {
    $errors['body'] = 'A body of no more than 1,000 characters is required.';
}

// if no validation errors, update the record in the quizzes database table.
if (count($errors)) {
    return view('quizzes/edit.view.php', [
        'heading' => 'Edit Quiz',
        'errors' => $errors,
        'quiz' => $quiz
    ]);
}

$db->query('update quizzes set QuizTitle = :title where QuizID = :id', [
    'id' => $_POST['id'],
    'title' => $_POST['title']
]);

// redirect the user
header('location: /quizzes');
die();
