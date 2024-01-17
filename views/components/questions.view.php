<?php
$index = 0;
//print_r($questions);
$questionArr = [];
$answerIndex = 0;
$questionIndex = 0;
?>
<legend class="text-sm font-semibold leading-6 text-gray-900">Questions</legend>
<?php foreach ($questions as  $questionKey => $questionData) : ?>

        <?php if (!array_key_exists($questionData['QuestionID'], $questionArr)) :
            $questionArr[$questionData['QuestionID']] = $questionData['QuestionText'];
            $questionIndex++;
            $answerIndex = 1;
        ?>
        <div class="relative flex gap-x-3">
            <h1>Q<?= $questionKey + 1 ?>. <?= $questionData['QuestionText'] ?></h1>

        </div>
       <?php

            endif; ?>
    <?php view("components/quiz/checkbox.view.php", [
            "index" => $questionIndex,
            "questionID" => $questionData['QuestionID'],
            "answer" => $questionData['AnswerText'],
            "IsCorrect" => $questionData['IsCorrect'],
            "answerIndex" => $answerIndex,
            "answerID" => $questionData['AnswerID']
    ]);
    $answerIndex++;
    ?>


<?php endforeach; ?>
<?php //= print_r($questionArr) ?>
<?php

// Display the HTML form
//echo '<form action="submit_quiz.php" method="post">'; // Change "submit_quiz.php" to the actual submission script
//    foreach ($quizData as $questionID => $questionData) {
//    echo '<fieldset>';
//        echo '<legend>' . $questionData['QuestionText'] . '</legend>';
//
//        foreach ($questionData['Answers'] as $answer) {
//        echo '<label>';
//            echo '<input type="checkbox" name="answers[' . $questionID . '][]" value="' . $answer['AnswerID'] . '">';
//            echo $answer['AnswerText'];
//            echo '</label><br>';
//        }
//
//        echo '</fieldset>';
//    }
//
//    echo '<input type="submit" value="Submit">';
//    echo '</form>';
//?>
