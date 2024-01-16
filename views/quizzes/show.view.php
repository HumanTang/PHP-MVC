<?php require base_path('views/partials/head.php') ?>
<?php require base_path('views/partials/nav.php') ?>
<?php require base_path('views/partials/banner.php') ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a href="/quizzes" class="text-blue-500 underline">go back...</a>
        </p>

        <p><?= htmlspecialchars($quiz['QuizTitle']) ?></p>

        <footer class="mt-6">
            <a href="/quiz/edit?id=<?= $quiz['QuizID'] ?>" class="inline-flex justify-center rounded-md border border-transparent bg-gray-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Edit</a>
        </footer>

        <div>
            <button type="button" id="addQuestionBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add Question</button>
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
        <form id="myForm">
            <input type="hidden" name="QuizID" value="<?= $quiz['QuizID'] ?>">

            <div class="space-y-12">

                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 space-y-10">
                        <!-- Container for dynamic elements -->
                        <div id="dynamicElementsContainer">

                        </div>

                        <div id="newQuestion-container">

                        </div>

                        <div id="questions-container">

                        </div>

                        <fieldset>

                            <div class="mt-6 space-y-6" id="answers-container">
                                <!-- loop answers-->
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <button type="button" id="checkAnswerBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Check Answer</button>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
            </div>
        </form>


    </div>
</main>
<!-- JavaScript to append elements on the client side -->
<script>
    function addInputElement() {
        var container = document.getElementById('dynamicElementsContainer');
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'dynamic_element[]'; // Use an array if you expect multiple dynamic elements
        container.appendChild(input);
    }
</script>

<script>
    function log(text){
        console.log(text);
    }

    function addAnswerBtn(index) {
        console.log("addAnswerBtn");
        console.log(index);
        var ansIndex = $(".answers-cards-" + index).length + 1;
        console.log("ansIndex: " + ansIndex);
        $.ajax({
            url: 'http://127.0.0.1:8080/checkbox',
            type: 'POST',
            data: {index: index, ansIndex: ansIndex},
            dataType: 'html',
            success: function (response) {
                // Append the received HTML to the form
                $('#newAnswer-container_' + index).append(response);
            },
            error: function () {
                console.log('Error in AJAX request');
            }
        });
    }

    $(document).ready(function () {
        const form = $('#myForm')
        form.submit(function(event) {
            event.preventDefault();
            console.log("submitted");
            submitQuiz();
        });

        $('#checkAnswerBtn').on('click', function () {
            submitAnswer();
        });

        function submitAnswer() {
            const formData = $('#myForm').serializeArray();
            console.log('FormData:', formData);
            $.ajax({
                url: 'http://127.0.0.1:8080/checkanswer',
                type: 'post',
                data: formData,
                // dataType: 'json',
                success: function(response) {
                    // Save user answers to cookies
                    console.log('response', response)
                    $('#dynamicElementsContainer').append(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error check answer:', status, error);
                }
            });
        }



        // Click event for the "Add Element" button
        $('#addAnswerBtn').on('click', function () {
            // AJAX request to form.php
            console.log("addAnswerBtn");
            var index = $(".answers-cards").length + 1;
            console.log(index);
            $.ajax({
                url: 'http://127.0.0.1:8080/checkbox',
                type: 'POST',
                data: {index: index},
                dataType: 'html',
                success: function (response) {
                    // Append the received HTML to the form
                    $('#dynamicElementsContainer').append(response);
                },
                error: function () {
                    console.log('Error in AJAX request');
                }
            });
        });

        $('#addQuestionBtn').on('click', function () {
            // AJAX request to form.php
            var index = $(".questions-cards").length + 1;
            $.ajax({
                url: 'http://127.0.0.1:8080/textarea',
                type: 'POST',
                data: {index: index, mode: "edit"},
                dataType: 'html',
                success: function (response) {
                    // Append the received HTML to the form
                    $('#newQuestion-container').append(response);
                },
                error: function () {
                    console.log('Error in AJAX request');
                }
            });
        });

        function submitQuiz() {
            const formData = $('#myForm').serializeArray();
            console.log('FormData:', formData);
            $.ajax({
                url: 'http://127.0.0.1:8080/form',
                type: 'post',
                data: formData,
                // dataType: 'json',
                success: function(response) {
                    // Save user answers to cookies
                    console.log('response', response)
                    $('#dynamicElementsContainer').append(response);
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
                    $('#questions-container').append(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error getQuestions:', status, error);
                }
            });
        }
        getQuestions(<?= $_GET['id'] ?>);

        function getAnswers() {
            $.ajax({
                url: 'http://127.0.0.1:8080/getAnswers',
                type: 'post',
                // dataType: 'json',
                success: function(response) {
                    console.log('response', response)
                    $('#answers-container').append(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error getAnswers:', status, error);
                }
            });
        }




    });
</script>
<?php require base_path('views/partials/footer.php') ?>
