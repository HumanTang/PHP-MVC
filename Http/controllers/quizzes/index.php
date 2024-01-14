<?php
// Show Quizzes List
use Core\App;
use Core\Database;

$db = App::resolve(Database::class);
$quizzes = $db->query('select * from quizzes where UserID = 1')->get();


view("quizzes/index.view.php", [
    'heading' => 'My Quizzes',
    'quizzes' => $quizzes
]);