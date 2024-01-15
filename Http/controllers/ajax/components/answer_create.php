<?php
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