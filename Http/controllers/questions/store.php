<?php

use Core\App;
use Core\Validator;
use Core\Database;

$db = App::resolve(Database::class);
$errors = [];

if (! Validator::string($_POST['question'], 1, 1000)) {
    $errors['body'] = 'A question of no more than 1,000 characters is required.';
}

if (! empty($errors)) {
    return view("questions/create.view.php", [
        'heading' => 'Create Question',
        'errors' => $errors
    ]);
}

try {
    // Create a PDO instance


    // Start a transaction
    $db->beginTransaction();

    // Step 1: Insert the question into the Questions table
    $db->query('INSERT INTO Questions(QuestionText) VALUES(:question)', [
        'question' => $_POST['question']
    ]);

    // Step 2: Insert the association with QuizID = 1 into the QuizQuestions table
    $questionID = $db->lastInsertId();

    $db->query("INSERT INTO QuizQuestions (QuizID, QuestionID) VALUES (:quizID, :questionID)", [
        'quizID' => $_POST['id'],
        'questionID' => $questionID
    ]);
    // Commit the transaction if both steps succeed
    $db->commit();

} catch (PDOException $e) {
    // Roll back the transaction if any step fails
    $db->rollBack();
    dd($e);
}
$redirect = "location: /questions?id=" . $_POST["id"];
header($redirect);
die();
