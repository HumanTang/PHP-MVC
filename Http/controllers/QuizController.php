<?php

namespace Http\Controllers;

use Core\View;
use Http\Models;

class QuizController
{
    public function getQuestion()
    {
        // Implementation for handling "getQuestion" in the QuizController
        echo "Get Question method in QuizController called!";
    }

    public function index($id = 1)
    {
        // Your logic for the home page (e.g., rendering a view)
        $view = new View(200, \Core\Utility::base_path('Http/controllers/quizzes/' . "index.php"));
        require $view->path;
    }

    public function show($id = 1)
    {
        $quizObj = new Models\Quiz();
        $result = $quizObj->show($id);
        $result["heading"] = "Quiz";
        $result = array_merge($result, $quizObj->getQuestionById($id));
        view("quizzes/show.view.php", $result);
    }

    public function edit($id = 1){
        $view = new View(200, \Core\Utility::base_path('Http/controllers/quizzes/' . "edit.php"));
        require $view->path;
    }

    public function start($id = 1){
        $quizObj = new Models\Quiz();
        $quiz = $quizObj->getQuestionById($id);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(["quizData" =>$quiz['quizData'], "quizQuestionID" => $quiz['questionIDs']]);
    }

    public function getAnswers($id = 1){
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }
        $quizObj = new Models\Quiz();
        $quiz = $quizObj->getAnswer($id);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(["quizData" =>$quiz]);
    }

    public function checkAnswers($id = 1){
        $data = [];
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }
        if(isset($_POST['data'])){
            $data = $_POST['data'];
        }
        $quizObj = new Models\Quiz();
        $quiz = $quizObj->checkAnswer($id, $data);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($quiz);
    }

    public function checkBox(){
        $quizObj = new Models\Quiz();
        echo $quiz = $quizObj->getCheckBox();
    }

    public function createAnswer(){
        $quizObj = new Models\Quiz();
        echo $quiz = $quizObj->getAnswerCreate();
    }

    public function textArea(){
        $quizObj = new Models\Quiz();
        echo $quiz = $quizObj->textArea();
    }

    public function form(){
        $quizObj = new Models\Quiz();
        $post = $_POST;
        echo $quiz = $quizObj->form($post);
    }


}