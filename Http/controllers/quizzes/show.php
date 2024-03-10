<?php
// Show a Single Quiz information
use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$currentUserId = 1;
if(isset($_POST['id'])){
    $id =  $_POST['id'];
}
if(isset($_GET['id'])){
    $id =  $_GET['id'];
}

$quiz = $db->query('select * from quizzes where QuizID = :id', [
    'id' => $id
])->findOrFail();

$questions = $db->query('SELECT *
                             FROM Questions
                                      Join quizquestions on questions.QuestionID = quizquestions.QuestionID
                             WHERE quizquestions.QuizID = :quizid
', ['quizid' => $id])->get();

authorize($quiz['UserID'] === $currentUserId);

//view("quizzes/show.view.php", [
//    'heading' => 'Quiz',
//    'quiz' => $quiz,
//    'questions' => $questions
//]);
