<?php

// sleep(1);

include(__DIR__ . '/vendor/autoload.php');

use App\Router\Router;
use App\View\View;

$router = new Router;
include(__DIR__ . '/App/Router/routes.php');
echo View::render('template', [
    'home' => ROOT,
    'cadastrar' => CADASTRAR,
    'sobre' => ABOUT,
    'admin' => ADMIN,
    'content' => $router->run(),
    'root' => ROOT,
]);
// echo "<pre>";
// print_r($this->getUri());
// echo "</pre>";
// exit;
