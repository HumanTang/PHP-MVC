<?php
$index = $_POST["index"];
echo <<<EOL
<h1>New Question</h1>
<div class="questions-cards" class="flex gap-x-3">
    <div class="rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
        <textarea name="questions[question_$index][title]" rows="3" cols="150" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="New Question Text..."></textarea>
    </div>
    
    <div>
        <button type="button" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" onclick="addAnswerBtn($index);">Add Answer</button>
    </div>
        
    <div id="newAnswer-container_$index" class="sm:col-span-4">

    </div>
</div>
EOL;