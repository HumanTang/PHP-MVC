<?php

$router->get('/', 'index.php');
$router->get('/about', 'about.php');
$router->get('/contact', 'contact.php');

$router->get('/quizzes', 'quizzes/index.php')->only('auth');
$router->get('/quiz', 'quizzes/show.php');
$router->delete('/quiz', 'quizzes/destroy.php');

$router->get('/quiz/edit', 'quizzes/edit.php');
$router->patch('/quiz', 'quizzes/update.php');

$router->get('/quizzes/create', 'quizzes/create.php');
$router->post('/quizzes', 'quizzes/store.php');

$router->get('/questions', 'questions/index.php');
$router->get('/questions/create', 'questions/create.php');
$router->post('/questions', 'questions/store.php');

$router->get('/question', 'questions/show.php');
$router->get('/question/edit', 'questions/edit.php');

$router->patch('/question', 'questions/update.php');
$router->delete('/question', 'questions/destroy.php');

$router->get('/answers', 'answers/index.php');
$router->get('/answers/create', 'answers/create.php');

$router->post('/answers', 'answers/store.php');
$router->get('/answer', 'answers/edit.php');
$router->patch('/answer', 'answers/update.php');
$router->delete('/answer', 'answers/destroy.php');

$router->get('/register', 'registration/create.php')->only('guest');
$router->post('/register', 'registration/store.php')->only('guest');

$router->get('/login', 'session/create.php')->only('guest');
$router->post('/session', 'session/store.php')->only('guest');
$router->delete('/session', 'session/destroy.php')->only('auth');

