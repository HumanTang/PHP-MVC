<?php
$data = $_POST;
$value = $data["answer"];
echo <<<EOL
<div class="relative flex gap-x-3">
    <div class="flex h-6 items-center">
        <input name="choices[]" type="checkbox" value="$value" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
    </div>
    <div class="text-sm leading-6">
        <label for="comments" class="font-medium text-gray-900">$value</label>        
    </div>
</div>
EOL;

return;