<?php
echo "hello";

use Core\App;
use Core\Database;
use Http\Models\Quiz;

$quizAnswers = $_POST;
echo print_r(json_encode($quizAnswers));
echo "<br>";


$correctAnswers = array();
$questions = null;
$score = 0;
try {
    $db = App::resolve(Database::class);
    $db->beginTransaction();
    foreach ($quizAnswers['questions'] as $questionID => $userAnswer) {
        echo "question id: ". $questionID . "<br>";
        $answers = $db->query('SELECT AnswerID FROM Answers WHERE QuestionID = :questionID AND IsCorrect = 1',
            ['questionID' => $questionID]
        )->fetchAll();

        print_r(json_encode($answers)) . "<br>";


        $correctAnswers[$questionID] = $answers;
    }
    $db->commit();
}catch (PDOException $e) {
    // Roll back the transaction if any step fails
    $db->rollBack();
    dd($e);
}
echo "correctAnswers" . "<br>";
echo "<pre>";
print_r(json_encode($correctAnswers)) ;
// Compare user answers with correct answers and calculate the score
echo "</pre>";
foreach ($quizAnswers['questions'] as $questionID => $userAnswer) {
    if (isset($correctAnswers[$questionID]) &&
        array_diff($userAnswer['answers'],
            $correctAnswers[$questionID]) === array_diff($correctAnswers[$questionID],
            $userAnswer['answers'])) {
        $score++;
    }
}

echo "your score is: ". $score;