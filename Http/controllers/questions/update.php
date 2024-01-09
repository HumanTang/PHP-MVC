<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$currentUserId = 1;

// find the corresponding note
$quiz = $db->query('SELECT Q.QuestionID, Q.QuestionText, Qz.UserID, QQ.QuizID, Qz.QuizTitle
FROM Questions Q
         JOIN QuizQuestions QQ ON Q.QuestionID = QQ.QuestionID
         JOIN Quizzes Qz ON QQ.QuizID = Qz.QuizID
WHERE Q.QuestionID = :questionid AND Qz.QuizID = :id AND Qz.UserID = :userid', [
    'id' => $_POST['id'],
    'userid' => $currentUserId,
    'questionid' => $_POST['questionid']
])->findOrFail();

// authorize that the current user can edit the note
authorize($quiz['UserID'] === $currentUserId);

// validate the form
$errors = [];

if (! Validator::string($_POST['question'], 1, 1000)) {
    $errors['body'] = 'A question of no more than 1,000 characters is required.';
}

// if no validation errors, update the record in the quizzes database table.
if (count($errors)) {
    return view('questions/edit.view.php', [
        'heading' => 'Edit Questions',
        'errors' => $errors,
        'quiz' => $quiz
    ]);
}

$db->query('update Questions set QuestionText = :question where QuestionID = :id', [
    'id' => $_POST['questionid'],
    'question' => $_POST['question']
]);

// redirect the user
header('location: /questions');
die();
