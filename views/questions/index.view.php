<?php require base_path('views/partials/head.php') ?>
<?php require base_path('views/partials/nav.php') ?>
<?php require base_path('views/partials/banner.php') ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <ul>
            <?php foreach ($questions as $question) : ?>
                <li>
                    <a href="/question?id=<?= $_GET['id'] ?>&questionid=<?= $question['QuestionID'] ?>" class="text-blue-500 hover:underline">
                        <?= htmlspecialchars($question['QuestionText']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="mt-6">
            <a href="/questions/create?id=<?= $_GET['id'] ?>" class="text-blue-500 hover:underline">Create Question</a>
        </p>
    </div>
</main>

<?php require base_path('views/partials/footer.php') ?>
