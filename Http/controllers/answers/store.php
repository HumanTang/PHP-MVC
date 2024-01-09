<?php

use Core\App;
use Core\Validator;
use Core\Database;

$db = App::resolve(Database::class);
$errors = [];

if (! Validator::string($_POST['answer'], 1, 1000)) {
    $errors['body'] = 'A question of no more than 1,000 characters is required.';
}

if (! empty($errors)) {
    return view("answers/create.view.php", [
        'heading' => 'Create Answer',
        'errors' => $errors
    ]);
}

$db->query('INSERT INTO Answers(AnswerText, QuestionID, IsCorrect) VALUES(:answer, :questionid, :IsCorrect)', [
    'answer' => $_POST['answer'],
    'IsCorrect' => $_POST['iscorrect'],
    'questionid' => $_POST['questionid']
]);

$redirect = "location: /answers?id=" . $_POST["quizid"] . "&questionid=" . $_POST["questionid"];
header($redirect);
die();
