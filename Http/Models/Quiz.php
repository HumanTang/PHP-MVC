<?php

namespace Http\Models;
use Core\App;
use Core\Database;
use PDOException;

class Quiz
{
    public function getQuiz($id = 1)
    {
        $result = null;
        try {
            $db = App::resolve(Database::class);
            $result = $db->query('select * from quizzes where QuizID = :id', [
                'id' => $id
            ])->findOrFail();
        }catch (PDOException $e) {
            dd($e);
        }
        return $result;
    }

    public function createQuestion($questionText, $quizId){
        $db = App::resolve(Database::class);
        $result = array(
            "success" => false,
            "message" => ""
        );
        try {
            // Create a PDO instance

            // Start a transaction
            $db->beginTransaction();

            // Step 1: Insert the question into the Questions table
            $db->query('INSERT INTO Questions(QuestionText) VALUES(:question)', [
                'question' => $questionText
            ]);

            // Step 2: Insert the association with QuizID = 1 into the QuizQuestions table
            $questionID = $db->lastInsertId();

            $db->query("INSERT INTO QuizQuestions (QuizID, QuestionID) VALUES (:quizID, :questionID)", [
                'quizID' => $quizId,
                'questionID' => $questionID
            ]);
            // Commit the transaction if both steps succeed
            $db->commit();
            $result["success"] = true;
            $result["message"] = "question inserted successfully";
        } catch (PDOException $e) {
            // Roll back the transaction if any step fails
            $db->rollBack();
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        return $result;
    }

    public function createAnswer($answerText,$questionId,$isCorrect){
        $result = array(
            "success" => false,
            "message" => ""
        );
        $db = App::resolve(Database::class);
        try {
            // Create a PDO instance

            // Start a transaction
            $db->beginTransaction();
            $db->query('INSERT INTO Answers(AnswerText, QuestionID, IsCorrect) VALUES(:answer, :questionid, :IsCorrect)', [
                'answer' => $answerText,
                'IsCorrect' => $isCorrect,
                'questionid' => $questionId
            ]);
            $db->commit();
            $result["message"] = "answer inserted successfully";
        } catch (PDOException $e) {
            // Roll back the transaction if any step fails
            $db->rollBack();
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        return $result;
    }

    public function show($id){
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

        return ["quiz"=> $quiz, "questions" => $questions];
    }

    public function getQuestionById($id = 1){
        $question = [];
        try {
            $db = App::resolve(Database::class);
            $db->beginTransaction();
            //    $questions = $db->query('SELECT questions.QuestionID, QuestionText
            //    FROM Questions
            //    Join quizquestions on questions.QuestionID = quizquestions.QuestionID
            //    WHERE quizquestions.QuizID = :quizid', [
            //        'quizid' => 1
            //    ])->get();

            $questions = $db->query('SELECT questions.QuestionID, QuestionText, AnswerID, AnswerText, IsCorrect
            FROM Questions
                     Join answers on answers.QuestionID = questions.QuestionID
            WHERE answers.QuestionID in (SELECT questions.QuestionID
                                         FROM Questions
                                                  Join quizquestions on questions.QuestionID = quizquestions.QuestionID
                                         WHERE quizquestions.QuizID = :quizid)', [
                'quizid' => $id
            ])->get();

            // SQL query to retrieve quiz questions and answers

            $result = $db->query('SELECT
                qq.QuestionID,
                q.QuestionText,
                a.AnswerID,
                a.AnswerText,
                a.IsCorrect
              FROM QuizQuestions qq
              INNER JOIN Questions q ON qq.QuestionID = q.QuestionID
              LEFT JOIN Answers a ON q.QuestionID = a.QuestionID
              WHERE qq.QuizID = :quizid
              ORDER BY qq.QuestionID, a.AnswerID',[
                'quizid' => $id
            ]);

            $questionID_arr = [];
            while ($row = $result->fetch_assoc()) {
                $questionID = $row['QuestionID'];
                $answer = array(
                    'AnswerID' => $row['AnswerID'],
                    'AnswerText' => $row['AnswerText'],
                    'IsCorrect' => $row['IsCorrect']
                );

                if (!isset($quizData[$questionID])) {
                    $quizData[$questionID] = array(
                        'QuestionText' => $row['QuestionText'],
                        'Answers' => array()
                    );
                    $questionID_arr[] = $questionID;
                }

                $quizData[$questionID]['Answers'][] = $answer;

            }
            $question['quizData'] = $quizData;
            $question['questionIDs'] = $questionID_arr;
            $db->commit();
        }catch (PDOException $e) {
            // Roll back the transaction if any step fails
            $db->rollBack();
            dd($e);
        }
        return $question;
    }

    public function getAnswer($id){
        $db = App::resolve(Database::class);
        $answers = $db->query('SELECT A.AnswerText, Q.QuestionID, A.AnswerID
        FROM Answers A
        JOIN QuizQuestions QQ ON A.QuestionID = QQ.QuestionID
        JOIN Questions Q ON A.QuestionID = Q.QuestionID 
        WHERE Q.QuestionID = :questionid',[
                    'questionid' => $id
        ])->get();
        return $answers;
    }

    public function checkAnswer($id, $data){
        $db = App::resolve(Database::class);
        $answers = $db->query('SELECT A.AnswerID
        FROM Answers A
        JOIN QuizQuestions QQ ON A.QuestionID = QQ.QuestionID
        JOIN Questions Q ON A.QuestionID = Q.QuestionID 
        WHERE Q.QuestionID = :questionid
        AND A.IsCorrect = 1',[
            'questionid' => $id
        ])->fetchAll();
        $answerId = [];
        $result = [];
        for($i = 0 ; $i < count($answers); $i++){
            $answerId[] =  $answers[$i];
        }

        foreach($data as $key => $item){
            //$result[] = array_search((int)$item['value'],$answerId);
            if(array_search((int)$item['value'],$answerId) !== false){
                $result[] = ["index"=> $key , "correct"=>true];
            }else{
                $result[] = ["index"=> $key , "correct"=>false];
            }
        }

        return $result;
    }

    public function getAnswerCreate(){
        echo <<< EOL
        <label for="NewAnswer" class="block text-sm font-medium leading-6 text-gray-900">New Answer</label>
        <div class="mt-2">
            <div class="relative flex gap-x-3">
                <div class="flex h-6 items-center">
                    <input id="isCorrect" name="isCorrect" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                </div>
                <div class="text-sm leading-6">
                    <label for="isCorrect" class="font-medium text-gray-900">Correct Answer</label>
                    <!--                        <p class="text-gray-500">Get notified when someones posts a comment on a posting.</p>-->
                </div>
                <div class="rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                    <textarea name="answer" rows="3" cols="150" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="New Answer Text..."></textarea>
                </div>
                <div>
                    <button type="button" id="addAnswerBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add Answer</button>
                </div>
        
            </div>
        </div>
        EOL;
        return "getAnswerCreate";
    }

    public function getCheckBox(){
        $data = $_POST;
        $value = isset($data["answer"]) ?? "";
        $index = $data["index"];
        $ansIndex = $data["ansIndex"];
        echo <<<EOL
        <div class="answers-cards answers-cards-$index relative flex gap-x-3">
            <div class="flex h-6 items-center">
                <input name="questions[question_$index][choice][answer_$ansIndex][isCorrect]" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
            </div>
            <div class="text-sm leading-6">
                <label for="isCorrect" class="font-medium text-gray-900">Correct Answer</label>        
            </div>
            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                <textarea name="questions[question_$index][choice][answer_$ansIndex][text]"  rows="3" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="$ansIndex"></textarea>
             </div>
            
        </div>
        EOL;
        return "getCheckBox";
    }

    public function form($post){
        return $post["QuizID"];
    }

}