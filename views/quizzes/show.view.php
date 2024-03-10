<?php require base_path('views/partials/head.php') ?>
<?php require base_path('views/partials/nav.php') ?>
<?php require base_path('views/partials/banner.php') ?>

<?php //print_r($_SERVER); ?>
<main id="content">
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a href="/quizzes" class="text-blue-500 underline">go back...</a>
        </p>

        <p><?= htmlspecialchars($quiz['QuizTitle']) ?></p>


        <div>
            <button type="button" id="startQuizBtn" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Start</button>
        </div>

        <?php
        $index = 0;
        foreach($quizData as $questionId => $question) : ?>
        <form id="questions-<?= $questionId ?>" class="questions hide" data-index="<?= $index ?>">
            <h1><?= $question["QuestionText"]; ?></h1>
            <?php foreach($question["Answers"] as $key => $answer) : ?>
                <input id="<?= $answer["AnswerID"]; ?>" name="<?= $key ?>" type="checkbox" value="<?= $answer["AnswerID"]; ?>" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 answers-<?=$questionId?>">
                <?= $answer["AnswerText"]; ?>
                <br>
            <?php endforeach; ?>

            <button onclick="checkAnswer(<?= $questionId ?>)" type="button" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirm</button>
            <pre id="answer-area-<?= $questionId ?>"></pre>
        </form>

        <?php
        $index++;
        endforeach; ?>
        <button type="button" id="prevQuestionBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Prev Question</button>
        <button type="button" id="nextQuestionBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Next Question</button>
    </div>
</main>

<script>
    document.getElementById("startQuizBtn").addEventListener('click', function (){
        var index = 0;
        document.getElementsByClassName("questions")[index].classList.remove("hide");
        document.getElementsByClassName("questions")[index].classList.add("active");
    })

    document.getElementById("nextQuestionBtn").addEventListener('click', function (){
        var active = document.getElementsByClassName("active")[0];
        var currentIdx = active.getAttribute("data-index");
        document.getElementsByClassName("questions")[currentIdx].classList.remove("active");
        document.getElementsByClassName("questions")[currentIdx].classList.add("hide");
        if(currentIdx < document.getElementsByClassName("questions").length - 1)
            currentIdx++;
        document.getElementsByClassName("questions")[currentIdx].classList.remove("hide");
        document.getElementsByClassName("questions")[currentIdx].classList.add("active");
    })

    document.getElementById("prevQuestionBtn").addEventListener('click', function (){
        var active = document.getElementsByClassName("active")[0];
        var currentIdx = active.getAttribute("data-index");
        document.getElementsByClassName("questions")[currentIdx].classList.remove("active");
        document.getElementsByClassName("questions")[currentIdx].classList.add("hide");
        if(currentIdx - 1 >= 0)
            currentIdx--;
        document.getElementsByClassName("questions")[currentIdx].classList.remove("hide");
        document.getElementsByClassName("questions")[currentIdx].classList.add("active");
    })

    function checkAnswer(questionid){
        var answers = document.getElementsByClassName('answers-' + questionid);
        var formdata = [];
        for(var i = 0; i < answers.length; i++){
            formdata.push({name: answers[i].name, value: answers[i].value, selected: answers[i].checked})
        }
        console.log(formdata)
        $.ajax({
            url: 'http://127.0.0.1:8080/quiz/checkAnswers',
            type: 'post',
            data: {id: questionid, data: formdata},
            dataType: 'json',
            success: function(res) {

                for(var i = 0; i < res.length; i++){
                    if(res[i].correct == false){
                        answers[res[i].index].style.color = "red";
                    }else{
                        answers[res[i].index].style.color = "green";
                    }
                }
                //document.getElementById('answer-area-' + questionid).innerHTML = prettyPrintJson.toHtml(response);

            },
            error: function(xhr, status, error) {
                console.error('Error getAnswers:', status, error);
            }
        });
    }

    function getAnswer(questionid){

        $.ajax({
            url: 'http://127.0.0.1:8080/quiz/getAnswers',
            type: 'post',
            data: {id: questionid},
            // dataType: 'json',
            success: function(response) {
                document.getElementById('answer-area-' + questionid).innerHTML = prettyPrintJson.toHtml(response);
            },
            error: function(xhr, status, error) {
                console.error('Error getAnswers:', status, error);
            }
        });
    }


    // document.getElementById("addQuestionBtn").addEventListener('click', function(){
    //     var questions = document.querySelector(".questions-cards");
    //     var index = 0;
    //     if(questions !== null){
    //         index = document.getElementsByClassName("questions-cards").length;
    //     }
    //
    //     var newDivElement = document.createElement("div");
    //     newDivElement.setAttribute("id", "newQuestion-container");
    //     var newFormElement = document.createElement("form");
    //     newFormElement.setAttribute("id", "newQuestionForm");
    //     newDivElement.appendChild(newFormElement);
    //
    //     document.querySelector('#content').appendChild(newDivElement);
    //     const newQuestionTemplate = `
    //     <h1>New Question</h1>
    //     <div class="questions-cards" class="flex gap-x-3">
    //         <div class="rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
    //             <textarea name="questions[question_${index}][title]" rows="3" cols="150" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="New Question Text..."></textarea>
    //         </div>
    //
    //         <div>
    //             <button type="button" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" onclick="addAnswerBtn(${index});">Add Answer</button>
    //         </div>
    //
    //         <div id="newAnswer-container_${index}" class="sm:col-span-4">
    //
    //         </div>
    //     </div>
    //     `;
    //     document.getElementById("newQuestion-container").innerHTML += newQuestionTemplate;
    //
    //
    // })
    //
    // function addAnswerBtn(index) {
    //     console.log("addAnswerBtn");
    //     console.log(index);
    //     var ansIndex = document.getElementsByClassName("answers-cards-" + index).length;
    //     console.log("ansIndex: " + ansIndex);
    //     const newAnswerCardTemplate = `
    //     <div class="answers-cards answers-cards-${index} relative flex gap-x-3">
    //         <div class="flex h-6 items-center">
    //             <input name="questions[question_${index}][choice][answer_${ansIndex}][isCorrect]" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
    //         </div>
    //         <div class="text-sm leading-6">
    //             <label for="isCorrect" class="font-medium text-gray-900">Correct Answer</label>
    //         </div>
    //         <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
    //             <textarea name="questions[question_${index}][choice][answer_${ansIndex}][text]"  rows="3" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="${ansIndex}"></textarea>
    //          </div>
    //     </div>
    //     `;
    //     document.getElementById('newAnswer-container_' + index).innerHTML += newAnswerCardTemplate;
    // }
    //
    // document.getElementById("newAnswerBtn").addEventListener('click', function (){
    //     var newAnswerArea = document.createElement('div');
    //     newAnswerArea.setAttribute('id', 'newAnswer');
    //     document.getElementById('content').appendChild(newAnswerArea);
    //     const newAnswerTemplate = `<label for="NewAnswer" class="block text-sm font-medium leading-6 text-gray-900">New Answer</label>
    //     <div class="mt-2">
    //         <div class="relative flex gap-x-3">
    //             <div class="flex h-6 items-center">
    //                 <input id="isCorrect" name="isCorrect" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
    //             </div>
    //             <div class="text-sm leading-6">
    //                 <label for="isCorrect" class="font-medium text-gray-900">Correct Answer</label>
    //                 <!--                        <p class="text-gray-500">Get notified when someones posts a comment on a posting.</p>-->
    //             </div>
    //             <div class="rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
    //                 <textarea name="answer" rows="3" cols="150" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="New Answer Text..."></textarea>
    //             </div>
    //             <div>
    //                 <button type="button" id="addAnswerBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add Answer</button>
    //             </div>
    //
    //         </div>
    //     </div>`;
    //     document.getElementById('newAnswer').innerHTML = newAnswerTemplate;
    // })

</script>
<!--<script src="--><?php //echo root_url("/scripts/script.js")?><!--"></script>-->
<?php require base_path('views/partials/footer.php') ?>
