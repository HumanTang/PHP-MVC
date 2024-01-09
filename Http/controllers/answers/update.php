<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$currentUserId = 1;

// find the corresponding note
$answer = $db->query('SELECT A.AnswerText, Q.QuestionID, Qz.UserID, A.AnswerID
FROM Answers A
JOIN QuizQuestions QQ ON A.QuestionID = QQ.QuestionID
JOIN Questions Q ON A.QuestionID = Q.QuestionID
JOIN Quizzes Qz ON QQ.QuizID = Qz.QuizID
WHERE A.AnswerID = :answerid', [
    'answerid' => $_POST['answerid'],
])->findOrFail();

// authorize that the current user can edit the note
authorize($answer['UserID'] === $currentUserId);

// validate the form
$errors = [];

if (! Validator::string($_POST['answer'], 1, 1000)) {
    $errors['body'] = 'A answer of no more than 1,000 characters is required.';
}

// if no validation errors, update the record in the quizzes database table.
if (count($errors)) {
    return view('answers/edit.view.php', [
        'heading' => 'Edit Answer',
        'errors' => $errors,
        'answer' => $answer
    ]);
}

$db->query('update Answers set AnswerText = :answer, IsCorrect = :iscorrect where AnswerID = :answerid', [
    'answerid' => $_POST['answerid'],
    'answer' => $_POST['answer'],
    'iscorrect' => $_POST['iscorrect']
]);

// redirect the user
$redirect = "location: /answers?id=" . $_POST["quizid"] . "&questionid=" . $_POST["questionid"];
header($redirect);
die();
