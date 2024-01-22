function log(text){
    console.log(text);
}

function addAnswerBtn(index) {
    console.log("addAnswerBtn");
    console.log(index);
    var ansIndex = document.querySelector(".answers-cards-" + index).length + 1;
    console.log("ansIndex: " + ansIndex);
    $.ajax({
        url: 'http://127.0.0.1:8080/checkbox',
        type: 'POST',
        data: {index: index, ansIndex: ansIndex},
        dataType: 'html',
        success: function (response) {
            // Append the received HTML to the form
            document.querySelector('#newAnswer-container_' + index).appendChild(response);
        },
        error: function () {
            console.log('Error in AJAX request');
        }
    });
}

function getQueryParams(url) {
    const paramArr = url.slice(url.indexOf('?') + 1).split('&');
    const params = {};
    paramArr.map(param => {
        const [key, val] = param.split('=');
        params[key] = decodeURIComponent(val);
    })
    return params;
}
window.addEventListener('load', function () {

        var url = getQueryParams(window.location.href);
        quizid = url['id'];
        console.log("quizid", quizid);
        const quizID = localStorage.setItem('quizID', quizid);


        const form = document.querySelector('#myForm')
        form.addEventListener( 'submit' ,function(event) {
            event.preventDefault();
            console.log("submitted");
            // submitQuiz();
        });

        document.querySelector('#toggleCollapseBtn').addEventListener('click', function () {
            document.querySelector('#collapseExample').classList.toggle("hidden");

        });

        document.querySelector('#quizStartBtn').addEventListener('click', function () {
            var quizData = JSON.parse(localStorage.getItem('quizData'));
            var quizQuestionID = JSON.parse(localStorage.getItem('quizQuestionID'));
            localStorage.setItem('currentQuestionIndex', 0);
            var currentQuestionIndex = parseInt(localStorage.getItem('currentQuestionIndex'));
            console.log(quizData);
            displayQuestion(quizQuestionID[currentQuestionIndex]);

        });
    document.querySelector('#nextQuestionBtn').addEventListener('click', function () {
            var quizQuestionID = JSON.parse(localStorage.getItem('quizQuestionID'));
            var currentQuestionIndex = parseInt(localStorage.getItem('currentQuestionIndex'));
            currentQuestionIndex = currentQuestionIndex + 1;
            localStorage.setItem('currentQuestionIndex', currentQuestionIndex);
            if(parseInt(localStorage.getItem('currentQuestionIndex')) < quizQuestionID.length){
                displayQuestion(quizQuestionID[parseInt(localStorage.getItem('currentQuestionIndex'))]);
            }

        });
    document.querySelector('#checkAnswerBtn').addEventListener('click', function () {
            submitAnswer();
        });


    document.querySelector('#getQuestionBtn').addEventListener('click', function () {
            var quizID = JSON.parse(localStorage.getItem('quizID'));
            getQuestions(parseInt(quizID));
        });

    document.querySelector('#confirmAnswerBtn').addEventListener('click', function () {
            var quizData = JSON.parse(localStorage.getItem('quizData'));
            var currentQuestionIndex = parseInt(localStorage.getItem('currentQuestionIndex'));
            var quizQuestionID = JSON.parse(localStorage.getItem('quizQuestionID'));
            console.log("currentQuestionIndex = " + currentQuestionIndex)
            const formData = document.querySelector('#quiz-container').serializeArray();
            var correctAnswerArr = Array();
            for (var j = 0; j < quizData[quizQuestionID[currentQuestionIndex]].Answers.length; j++){
                if(quizData[quizQuestionID[currentQuestionIndex]].Answers[j].IsCorrect === 1)
                    correctAnswerArr.push(quizData[quizQuestionID[currentQuestionIndex]].Answers[j].AnswerID);
            }

            console.log("correctAnswerArr = ",correctAnswerArr);

            for(var i = 0; i < formData.length; i++){
                if (correctAnswerArr.includes(parseInt(formData[i].value))){
                    console.log(formData[i].value + " isCorrect!");
                    document.querySelector("#answer_" + formData[i].value).style.color = "green"
                }else{
                    document.querySelector("#answer_" + formData[i].value).style.color = "red"
                }
            }
        });

    document.querySelector('#getQuestionJsonBtn').addEventListener('click', function () {
            var quizID = JSON.parse(localStorage.getItem('quizID'));
            getQuestionsJson(parseInt(quizID));
        });



        function submitAnswer() {
            const formData = document.querySelector('#myForm').serializeArray();
            console.log('FormData:', formData);
            $.ajax({
                url: 'http://127.0.0.1:8080/checkanswer',
                type: 'post',
                data: formData,
                // dataType: 'json',
                success: function(response) {
                    // Save user answers to cookies
                    console.log('response', response)
                    document.querySelector('#dynamicElementsContainer').appendChild(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error check answer:', status, error);
                }
            });
    }





    document.querySelector('#addQuestionBtn').addEventListener('click', function () {
            // AJAX request to form.php
            var index = document.querySelector(".questions-cards").length + 1;
            $.ajax({
                url: 'http://127.0.0.1:8080/textarea',
                type: 'POST',
                data: {index: index, mode: "edit"},
                dataType: 'html',
                success: function (response) {
                    // Append the received HTML to the form
                    document.querySelector('#newQuestion-container').appendChild(response);
                },
                error: function () {
                    console.log('Error in AJAX request');
                }
            });
        });

        function submitQuiz() {
            const formData = document.querySelector('#myForm').serializeArray();
            console.log('FormData:', formData);
            $.ajax({
                url: 'http://127.0.0.1:8080/form',
                type: 'post',
                data: formData,
                // dataType: 'json',
                success: function(response) {
                    // Save user answers to cookies
                    console.log('response', response)
                    document.querySelector('#dynamicElementsContainer').appendChild(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error submitting quiz:', status, error);
                }
            });
        }




        function getQuestions(quizid) {
            $.ajax({
                url: 'http://127.0.0.1:8080/getQuestions',
                type: 'post',
                data: {id: quizid , mode: "view"},
                // dataType: 'json',
                success: function(response) {
                    console.log('response', response)
                    document.querySelector('#questions-container').innerHTML = response;
                },
                error: function(xhr, status, error) {
                    console.error('Error getQuestions:', status, error);
                }
            });
        }

        function getQuestionsJson(quizid) {
            $.ajax({
                url: 'http://127.0.0.1:8080/getQuestionsJson',
                type: 'post',
                data: {id: quizid , mode: "json"},


                success: function(response) {
                    console.log('response', response)
                    document.querySelector('#json-area').innerHTML = prettyPrintJson.toHtml(response);
                    localStorage.setItem('quizData', JSON.stringify(response.quizData));
                    localStorage.setItem('quizQuestionID', JSON.stringify(response.quizQuestionID));
                    //displayQuestion(1);
                },
                error: function(xhr, status, error) {
                    console.error('Error getQuestions:', status, error);
                }
            });
        }



        // Function to display question and answers
        function displayQuestion(questionID) {
            var quizData = JSON.parse(localStorage.getItem('quizData'));
            var questionContainer = document.getElementById('quiz-container');
            var questionData = quizData[questionID];

            // Create question element
            var questionElement = document.createElement('div');
            questionElement.innerHTML = '<p>' + questionData.QuestionText + '</p>';

            // Create answer elements
            for (var i = 0; i < questionData.Answers.length; i++) {
                var answer = questionData.Answers[i];
                var answerElement = document.createElement('input');
                answerElement.type = 'checkbox';
                answerElement.name = 'answer_' + answer.AnswerID;
                answerElement.value = answer.AnswerID;
                answerElement.className = 'answersClass'
                answerElement.id = 'answer_' + answer.AnswerID;

                var labelElement = document.createElement('label');
                labelElement.innerHTML = answer.AnswerText;
                labelElement.setAttribute('for', 'answer_' + answer.AnswerID);

                questionElement.appendChild(answerElement);
                questionElement.appendChild(labelElement);
                questionElement.appendChild(document.createElement('br'));
            }

            // Append question element to the container
            questionContainer.innerHTML = questionElement.innerHTML;
        }


        function getAnswers() {
            $.ajax({
                url: 'http://127.0.0.1:8080/getAnswers',
                type: 'post',
                // dataType: 'json',
                success: function(response) {
                    console.log('response', response)
                    document.querySelector('#answers-container').appendChild(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error getAnswers:', status, error);
                }
            });
        }








})

