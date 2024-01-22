<?php
use Core\App;
use Core\Database;
$post = $_POST;
echo print_r($post);
$questions = $_POST["questions"];

//{
//    "question_1": {
//        "title": "test",
//        "choice": {
//            "answer_1": {
//                "text": "1"
//             },
//            "answer_2": {
//                "isCorrect": "1",
//                "text": "2"
//            }
//        }
//    }
//}

//echo json_encode($post);

$db = App::resolve(Database::class);

// JSON data


// Decode JSON data

try {
// Process and insert data into the database
    $db->beginTransaction();
    foreach ($questions as $questionData) {
        // Insert question into Questions table
        $questionTitle = $questionData['title'];
        $db->query('INSERT INTO Questions(QuestionText) VALUES(:question)', [
            'question' => $questionTitle
        ]);
        // Get the inserted question ID
        $questionID = $db->lastInsertId();

        // Insert answers into Answers table
        foreach ($questionData['choice'] as $answerData) {
            $answerText = $answerData['text'];
            $isCorrect = isset($answerData['isCorrect']) && $answerData['isCorrect'] == "1" ? 1 : 0;

            $db->query('INSERT INTO Answers(AnswerText, QuestionID, IsCorrect) VALUES(:answer, :questionid, :IsCorrect)', [
                'answer' => $answerText,
                'IsCorrect' => $isCorrect,
                'questionid' => $questionID
            ]);
        }

        // Insert question into QuizQuestions table (assuming a quiz with ID 1)
        $quizID = 1; // Replace with the actual quiz ID
        $insertQuizQuestionQuery = "INSERT INTO QuizQuestions (QuizID, QuestionID) VALUES ($quizID, $questionID)";
        $db->query($insertQuizQuestionQuery);
    }
    $db->commit();
}catch (PDOException $e) {
    // Roll back the transaction if any step fails
    $db->rollBack();
    dd($e);
}

//echo json_encode($questions["question_1"]);
//echo json_encode($questions["question_1"]["choice"]["answer_1"]);

return;