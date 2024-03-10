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

        <footer class="mt-6">
            <a href="/quiz/edit?id=<?= $quiz['QuizID'] ?>" class="inline-flex justify-center rounded-md border border-transparent bg-gray-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Edit</a>
        </footer>

        <form method="post" action="<?= root_url($_SERVER['REQUEST_URI']) ?>">
            <input type="submit" name="button1"
                   class="button" value="Button1" />

            <input type="submit" name="button2"
                   class="button" value="Button2" />
        </form>

        <label for="Question">Choose a Question:</label>

        <select name="Question" id="Question">
            <?php foreach($questions as $question) : ?>
                <option value="<?= $question['QuestionID']; ?>"><?= $question['QuestionText']; ?></option>
            <?php endforeach; ?>
        </select>

        <?php
        $index = 0;
        foreach($quizData as $k => $question) : ?>
            <div class="questions question-<?= $index ?> hide" data-index="<?= $index ?>">
                <h1><?= $question["QuestionText"]; ?></h1>
                <?php foreach($question["Answers"] as $key => $answer) : ?>
                    <input name="<?= $key ?>" type="checkbox" value="<?= $answer["AnswerID"]; ?>" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    <?= $answer["AnswerText"]; ?>
                    <br>
                <?php endforeach; ?>
            </div>
            <?php
            $index++;
        endforeach; ?>


        <div>
            <button type="button" id="startQuizBtn" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Start</button>
            <button type="button" id="newAnswerBtn" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">New Answer</button>
            <button type="button" id="getAnswerBtn" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get Answer</button>
            <button type="button" id="addQuestionBtn" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add Question</button>
        </div>

        <div>
            <button type="button" id="getQuestionBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Load Question</button>
            <button type="button" id="getQuestionJsonBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Load Question Json</button>
        </div>

        <div>
            <button type="button" id="quizStartBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Start Quiz</button>
            <button type="button" id="prevQuestionBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Prev Question</button>
            <button type="button" id="nextQuestionBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Next Question</button>
        </div>

        <div>
            <button type="button" id="confirmAnswerBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirm Answer</button>
        </div>



        <form id="quiz-container"></form>

        <p>
            <button type="button" id="toggleCollapseBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Toggle Json Area</button>
        </p>
        <div id="collapseExample">
            <div class="card card-body">
                <pre id="json-area" class="json-container"></pre>
            </div>
        </div>




        <!--
          This example requires some changes to your config:

          ```
          // tailwind.config.js
          module.exports = {
            // ...
            plugins: [
              // ...
              require('@tailwindcss/forms'),
            ],
          }
          ```
        -->


        <form id="myForm" action="<?= root_url("/response") ?>" method="post">
            <input type="hidden" name="QuizID" value="<?= $quiz['QuizID'] ?>">

            <div class="space-y-12">

                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 space-y-10">

                        <div id="questions-container">

                        </div>

                    </div>
                </div>
            </div>
            <button type="button" id="checkAnswerBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Check Answer</button>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
            </div>
        </form>

        <form id="debugForm">
            <input type="hidden" name="QuizID" value="<?= $quiz['QuizID'] ?>">
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
        </form>

        <div id="dynamicElementsContainer"></div>
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


    document.getElementById("addQuestionBtn").addEventListener('click', function(){
        var questions = document.querySelector(".questions-cards");
        var index = 0;
        if(questions !== null){
            index = document.getElementsByClassName("questions-cards").length;
        }

        var newDivElement = document.createElement("div");
        newDivElement.setAttribute("id", "newQuestion-container");
        var newFormElement = document.createElement("form");
        newFormElement.setAttribute("id", "newQuestionForm");
        newDivElement.appendChild(newFormElement);

        document.querySelector('#content').appendChild(newDivElement);
        const newQuestionTemplate = `
        <h1>New Question</h1>
        <div class="questions-cards" class="flex gap-x-3">
            <div class="rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                <textarea name="questions[question_${index}][title]" rows="3" cols="150" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="New Question Text..."></textarea>
            </div>

            <div>
                <button type="button" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" onclick="addAnswerBtn(${index});">Add Answer</button>
            </div>

            <div id="newAnswer-container_${index}" class="sm:col-span-4">

            </div>
        </div>
        `;
        document.getElementById("newQuestion-container").innerHTML += newQuestionTemplate;


    })

    function addAnswerBtn(index) {
        console.log("addAnswerBtn");
        console.log(index);
        var ansIndex = document.getElementsByClassName("answers-cards-" + index).length;
        console.log("ansIndex: " + ansIndex);
        const newAnswerCardTemplate = `
        <div class="answers-cards answers-cards-${index} relative flex gap-x-3">
            <div class="flex h-6 items-center">
                <input name="questions[question_${index}][choice][answer_${ansIndex}][isCorrect]" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
            </div>
            <div class="text-sm leading-6">
                <label for="isCorrect" class="font-medium text-gray-900">Correct Answer</label>
            </div>
            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                <textarea name="questions[question_${index}][choice][answer_${ansIndex}][text]"  rows="3" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="${ansIndex}"></textarea>
             </div>
        </div>
        `;
        document.getElementById('newAnswer-container_' + index).innerHTML += newAnswerCardTemplate;
    }

    document.getElementById("newAnswerBtn").addEventListener('click', function (){
        var newAnswerArea = document.createElement('div');
        newAnswerArea.setAttribute('id', 'newAnswer');
        document.getElementById('content').appendChild(newAnswerArea);
        const newAnswerTemplate = `<label for="NewAnswer" class="block text-sm font-medium leading-6 text-gray-900">New Answer</label>
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
        </div>`;
        document.getElementById('newAnswer').innerHTML = newAnswerTemplate;
    })

</script>
<!--<script src="--><?php //echo root_url("/scripts/script.js")?><!--"></script>-->
<?php require base_path('views/partials/footer.php') ?>
