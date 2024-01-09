<?php require base_path('views/partials/head.php') ?>
<?php require base_path('views/partials/nav.php') ?>
<?php require base_path('views/partials/banner.php') ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <ul>
            <?php foreach ($answers as $answer) : ?>
                <li>
                    <a href="/answer?answerid=<?= $answer['AnswerID'] ?>" class="text-blue-500 hover:underline">
                        <?= htmlspecialchars($answer['AnswerText']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="mt-6">
            <a href="/answers/create?id=<?= $_GET['id'] ?>&questionid=<?= $_GET['questionid'] ?>" class="text-blue-500 hover:underline">Create Answers</a>
        </p>
    </div>
</main>

<?php require base_path('views/partials/footer.php') ?>
