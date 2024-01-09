<?php require base_path('views/partials/head.php') ?>
<?php require base_path('views/partials/nav.php') ?>
<?php require base_path('views/partials/banner.php') ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <ul>
            <?php foreach ($quizzes as $quiz) : ?>
                <li>
                    <a href="/quiz?id=<?= $quiz['QuizID'] ?>" class="text-blue-500 hover:underline">
                        <?= htmlspecialchars($quiz['QuizTitle']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="mt-6">
            <a href="/quizzes/create" class="text-blue-500 hover:underline">Create Quiz</a>
        </p>
    </div>
</main>

<?php require base_path('views/partials/footer.php') ?>
