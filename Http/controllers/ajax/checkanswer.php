<?php
use Core\App;
use Core\Database;
use Http\Models\Quiz;

$formdata = $_POST;
echo print_r(json_encode($formdata));
//echo "<br>";
$quiz = new Quiz();
$questions = $quiz->get();
//print_r($formdata['questions']['question_1']['choice']);
foreach ($formdata as  $formdataKey => $formdataValue){

//    echo print_r($formdataValue['question_1']['choice']);
//    echo print_r($formdataValue['question_1']);
//    foreach ($formdataValue['choice'] as $answerData) {
//        echo print_r($answerData);
//        $answerText = $answerData['answer'];
//        $isCorrect = isset($answerData['isCorrect']);
//    }

}
//foreach ($questions as  $questionKey => $questionData){
//    if($questionData["IsCorrect"] == 1)
//        echo $questionData["AnswerText"];
//}





return;