<?php
namespace Http\Models;
use Core\Utility;
use Http\Models\Quiz;
class UploadHandler {
    public array $file;
    protected array $allowed = array('json');
    public function __construct()
    {
        $this->file = array(
            "target_dir" => doc_root("/upload/"),

        );
    }

    function upload(){
        $isValid = false;
        $result = array(
            "isValid" => false,
            "success" => false,
            "message" => "");
        $target_dir = $this->file["target_dir"];
        $allowed = $this->allowed;
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $filename = $_FILES['fileToUpload']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, $allowed)) {
            $isValid = false;
            return $result;
        }else{
            $result["isValid"] = true;
        }

        if($result["isValid"]){
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $result["message"] = "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";;
                $result["success"] = true;
            } else {
                $success = false;
                $result["message"] = "Sorry, there was an error uploading your file.";
            }
        }

        return $result;
    }

    function importJson($json){
        $quizObj = new Quiz();        
        foreach ($json as $key => $value){
            $quizObj->createQuestion($value["questionText"], $value["quizID"]);
            foreach ($value["answers"] as $ans_key => $answer_value){
                $quizObj->createAnswer($answer_value["answerText"],$answer_value["questionID"],$answer_value["isCorrect"]);
            }
        }
    }

    function importMDJson($json, $quizId){
        $quizObj = new Quiz();
        for($i = 1; $i <= 65; $i++){
            $res = $quizObj->createQuestion($json["Question".$i]["Problem"], $quizId);
            $answers = $json["Question".$i]["Answers"];
            for($n = 0; $n < count($answers); $n++){
                if(is_array($answers[$n])){
                    continue;
                }
                if($n + 1 < count($answers)){
                    if(is_array($answers[$n + 1]) == 1){
                        $quizObj->createAnswer($answers[$n], $res["questionId"], True);    
                    }else{                       
                        $quizObj->createAnswer($answers[$n], $res["questionId"], False);
                    }
                }

                if($n == count($answers) - 1){                    
                    $quizObj->createAnswer($answers[$n], $res["questionId"], False);
                }
            }
        }
    }


    function getJson($filename){
        $json = file_get_contents(Utility::doc_root("/upload/") . $filename);

        // Decode the JSON file
        $json_data = json_decode($json,true);

        // Display data
        return $json_data;
    }
}
